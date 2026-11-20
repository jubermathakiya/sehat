<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\AccountLog;
use App\Models\Central\Customer;
use App\Models\Central\SubscriptionPlan;
use App\Services\Central\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function __construct(protected CustomerService $customers) {}

    public function index(Request $request)
    {
        $query = Customer::with('plan', 'database')->latest();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('clinic_name', 'like', "%{$search}%")
                    ->orWhere('doctor_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('tenant_code', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('account_status', $status);
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('superadmin.customers.index', compact('customers'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::where('status', 'active')->orderBy('name')->get();

        return view('superadmin.customers.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clinic_name' => ['required', 'string', 'max:255'],
            'doctor_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:customers,email'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:100', 'unique:customers,username'],
            'password' => ['required', 'string', 'min:8'],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'billing_cycle' => ['required', Rule::in(['trial', 'monthly', 'quarterly', 'half_yearly', 'yearly'])],
            'subscription_start_date' => ['nullable', 'date'],
            'grace_period_days' => ['nullable', 'integer', 'min:0', 'max:30'],
            'discount' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            $customer = $this->customers->create($data, true);

            return redirect()->route('superadmin.customers.show', $customer)
                ->with('success', 'Customer created and database provisioned successfully. Tenant code: '.$customer->tenant_code);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Provisioning failed: '.$e->getMessage());
        }
    }

    public function show(Customer $customer)
    {
        $customer->load(['plan', 'database', 'subscriptions.plan', 'transactions', 'logs']);

        return view('superadmin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $plans = SubscriptionPlan::orderBy('name')->get();

        return view('superadmin.customers.edit', compact('customer', 'plans'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'clinic_name' => ['required', 'string', 'max:255'],
            'doctor_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)],
            'mobile' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:100', Rule::unique('customers', 'username')->ignore($customer->id)],
            'account_status' => ['required', Rule::in(['active', 'inactive', 'suspended', 'expired'])],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'grace_period_days' => ['nullable', 'integer', 'min:0', 'max:30'],
        ]);

        $customer->update($data);

        AccountLog::create([
            'customer_id' => $customer->id,
            'super_admin_id' => auth('superadmin')->id(),
            'action' => 'customer_updated',
            'description' => 'Customer details updated',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('superadmin.customers.show', $customer)->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->update(['account_status' => 'inactive']);
        $customer->delete();

        return redirect()->route('superadmin.customers.index')->with('success', 'Customer archived (soft deleted). Database retained.');
    }

    public function renew(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'billing_cycle' => ['required', Rule::in(['monthly', 'quarterly', 'half_yearly', 'yearly'])],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->customers->renew($customer, $data['billing_cycle'], $data['discount'] ?? 0, $data['notes'] ?? null);

        return back()->with('success', 'Subscription renewed and access restored.');
    }

    public function resetPassword(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer->update(['password' => $data['password']]);

        // Best-effort update tenant admin password if DB ready
        try {
            app(\App\Services\Tenant\TenantConnectionManager::class)->connect($customer->load('database'));
            \App\Models\Tenant\User::where('email', $customer->email)->update([
                'password' => Hash::make($data['password']),
            ]);
        } catch (\Throwable) {
            // ignore if DB not ready
        }

        AccountLog::create([
            'customer_id' => $customer->id,
            'super_admin_id' => auth('superadmin')->id(),
            'action' => 'password_reset',
            'description' => 'Customer login password reset by super admin',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Password reset successfully.');
    }
}
