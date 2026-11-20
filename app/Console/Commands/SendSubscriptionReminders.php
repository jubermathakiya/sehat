<?php

namespace App\Console\Commands;

use App\Models\Central\AccountLog;
use App\Models\Central\Customer;
use Illuminate\Console\Command;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'subscriptions:send-reminders';

    protected $description = 'Log subscription expiry reminder notifications for 7/3/1 days';

    public function handle(): int
    {
        $days = config('tenancy.reminder_days', [7, 3, 1]);

        foreach ($days as $day) {
            $target = now()->addDays((int) $day)->toDateString();
            $customers = Customer::where('account_status', 'active')
                ->whereDate('subscription_expiry_date', $target)
                ->get();

            foreach ($customers as $customer) {
                AccountLog::create([
                    'customer_id' => $customer->id,
                    'action' => 'expiry_reminder',
                    'description' => "Subscription expires in {$day} day(s)",
                    'meta' => ['days' => $day, 'expiry' => $customer->subscription_expiry_date?->toDateString()],
                ]);

                // Hook mail/notification here when SMTP is configured
                $this->info("Reminder queued for {$customer->email} ({$day} days)");
            }
        }

        return self::SUCCESS;
    }
}
