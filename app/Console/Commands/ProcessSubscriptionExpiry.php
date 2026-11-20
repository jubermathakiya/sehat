<?php

namespace App\Console\Commands;

use App\Models\Central\AccountLog;
use App\Models\Central\Customer;
use Illuminate\Console\Command;

class ProcessSubscriptionExpiry extends Command
{
    protected $signature = 'subscriptions:process-expiry';

    protected $description = 'Mark expired customer subscriptions and suspend access after grace period';

    public function handle(): int
    {
        $customers = Customer::whereIn('account_status', ['active'])
            ->whereNotNull('subscription_expiry_date')
            ->whereDate('subscription_expiry_date', '<', now()->toDateString())
            ->get();

        foreach ($customers as $customer) {
            if ($customer->isInGracePeriod()) {
                if ($customer->subscription_status !== 'expired') {
                    $customer->update(['subscription_status' => 'expired']);
                    AccountLog::create([
                        'customer_id' => $customer->id,
                        'action' => 'entered_grace_period',
                        'description' => 'Subscription expired; grace period active',
                    ]);
                }
                continue;
            }

            if (! $customer->isAccessAllowed()) {
                $customer->update([
                    'subscription_status' => 'expired',
                    'account_status' => 'expired',
                ]);

                AccountLog::create([
                    'customer_id' => $customer->id,
                    'action' => 'subscription_expired',
                    'description' => 'Access suspended due to subscription expiry',
                ]);

                $this->info("Suspended customer #{$customer->id} ({$customer->clinic_name})");
            }
        }

        return self::SUCCESS;
    }
}
