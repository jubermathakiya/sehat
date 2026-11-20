<?php

namespace App\Jobs;

use App\Models\Central\Customer;
use App\Services\Tenant\TenantProvisioner;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenantCredentialsMail;

class ProvisionTenantJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public int $customerId,
        public string $plainPassword
    ) {}

    public function handle(TenantProvisioner $provisioner): void
    {
        $customer = Customer::with('plan')->findOrFail($this->customerId);
        $provisioner->provision($customer, $this->plainPassword);

        $customer->refresh();

        if (config('mail.default') && config('mail.default') !== 'log') {
            try {
                Mail::to($customer->email)->send(new TenantCredentialsMail($customer, $this->plainPassword));
            } catch (\Throwable) {
                // Mail is optional during local setup
            }
        }
    }
}
