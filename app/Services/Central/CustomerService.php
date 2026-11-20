<?php

namespace App\Services\Central;

use App\Jobs\ProvisionTenantJob;
use App\Models\Central\AccountLog;
use App\Models\Central\Customer;
use App\Models\Central\CustomerSubscription;
use App\Models\Central\SubscriptionPlan;
use App\Models\Central\SubscriptionTransaction;
use App\Services\Tenant\TenantProvisioner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CustomerService
{
    public function __construct(
        protected TenantProvisioner $provisioner
    ) {}

    public function create(array $data, bool $syncProvision = true): Customer
    {
        $plainPassword = $data['password'];

        // Keep central record creation transactional. Provisioning uses DDL
        // (CREATE DATABASE / migrations) which implicitly commits in MySQL,
        // so it must run outside this transaction.
        $customer = DB::connection('mysql')->transaction(function () use ($data) {
            $plan = SubscriptionPlan::findOrFail($data['subscription_plan_id']);
            $cycle = $data['billing_cycle'] ?? 'monthly';
            $start = Carbon::parse($data['subscription_start_date'] ?? now());
            $expiry = $this->calculateExpiry($start, $cycle, $plan);
            $grace = (int) ($data['grace_period_days'] ?? 0);

            $customer = Customer::create([
                'tenant_code' => $this->uniqueTenantCode(),
                'clinic_name' => $data['clinic_name'],
                'doctor_name' => $data['doctor_name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'country' => $data['country'] ?? null,
                'username' => $data['username'],
                'password' => $data['password'],
                'account_status' => 'pending',
                'subscription_plan_id' => $plan->id,
                'billing_cycle' => $cycle,
                'subscription_start_date' => $start,
                'subscription_expiry_date' => $expiry,
                'grace_period_days' => $grace,
                'subscription_status' => $cycle === 'trial' ? 'trial' : 'scheduled',
                'provisioning_status' => 'pending',
            ]);

            $subscription = CustomerSubscription::create([
                'customer_id' => $customer->id,
                'subscription_plan_id' => $plan->id,
                'billing_cycle' => $cycle,
                'amount' => $plan->priceForCycle($cycle === 'trial' ? 'monthly' : $cycle),
                'discount' => (float) ($data['discount'] ?? 0),
                'start_date' => $start,
                'expiry_date' => $expiry,
                'grace_period_days' => $grace,
                'status' => $cycle === 'trial' ? 'trial' : 'active',
                'notes' => $data['notes'] ?? null,
            ]);

            SubscriptionTransaction::create([
                'customer_id' => $customer->id,
                'customer_subscription_id' => $subscription->id,
                'transaction_type' => $cycle === 'trial' ? 'trial' : 'assign',
                'amount' => max(0, $subscription->amount - $subscription->discount),
                'notes' => 'Initial subscription assignment',
            ]);

            AccountLog::create([
                'customer_id' => $customer->id,
                'super_admin_id' => auth('superadmin')->id(),
                'action' => 'customer_created',
                'description' => 'Customer created and queued for provisioning',
                'ip_address' => request()->ip(),
            ]);

            return $customer;
        });

        if ($syncProvision) {
            $this->provisioner->provision($customer->fresh('plan'), $plainPassword);
        } else {
            ProvisionTenantJob::dispatch($customer->id, $plainPassword);
        }

        return $customer->fresh(['plan', 'database', 'subscriptions']);
    }

    public function renew(Customer $customer, string $cycle, ?float $discount = 0, ?string $notes = null): Customer
    {
        $plan = $customer->plan;
        $start = $customer->subscription_expiry_date && $customer->subscription_expiry_date->isFuture()
            ? $customer->subscription_expiry_date->copy()->addDay()
            : now();
        $expiry = $this->calculateExpiry($start, $cycle, $plan);

        $subscription = CustomerSubscription::create([
            'customer_id' => $customer->id,
            'subscription_plan_id' => $plan->id,
            'billing_cycle' => $cycle,
            'amount' => $plan->priceForCycle($cycle),
            'discount' => $discount ?? 0,
            'start_date' => $start,
            'expiry_date' => $expiry,
            'grace_period_days' => $customer->grace_period_days,
            'status' => 'active',
            'notes' => $notes,
        ]);

        SubscriptionTransaction::create([
            'customer_id' => $customer->id,
            'customer_subscription_id' => $subscription->id,
            'transaction_type' => 'renew',
            'amount' => max(0, $subscription->amount - $subscription->discount),
            'notes' => $notes,
        ]);

        $customer->update([
            'billing_cycle' => $cycle,
            'subscription_start_date' => $start,
            'subscription_expiry_date' => $expiry,
            'subscription_status' => 'active',
            'account_status' => 'active',
        ]);

        AccountLog::create([
            'customer_id' => $customer->id,
            'super_admin_id' => auth('superadmin')->id(),
            'action' => 'subscription_renewed',
            'description' => "Renewed until {$expiry->toDateString()}",
            'ip_address' => request()->ip(),
        ]);

        return $customer->fresh();
    }

    public function calculateExpiry(Carbon $start, string $cycle, SubscriptionPlan $plan): Carbon
    {
        return match ($cycle) {
            'trial' => $start->copy()->addDays(max(1, (int) $plan->trial_days)),
            'monthly' => $start->copy()->addMonth(),
            'quarterly' => $start->copy()->addMonths(3),
            'half_yearly' => $start->copy()->addMonths(6),
            'yearly' => $start->copy()->addYear(),
            default => $start->copy()->addMonth(),
        };
    }

    protected function uniqueTenantCode(): string
    {
        do {
            $code = 'CLN'.strtoupper(Str::random(5));
        } while (Customer::where('tenant_code', $code)->exists());

        return $code;
    }
}
