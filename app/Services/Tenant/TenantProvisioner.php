<?php

namespace App\Services\Tenant;

use App\Models\Central\AccountLog;
use App\Models\Central\Customer;
use App\Models\Central\CustomerSubscription;
use App\Models\Central\TenantDatabase;
use App\Models\Tenant\Permission;
use App\Models\Tenant\Role;
use App\Models\Tenant\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TenantProvisioner
{
    public function __construct(
        protected TenantConnectionManager $connections
    ) {}

    public function provision(Customer $customer, string $plainPassword): void
    {
        $customer->update([
            'account_status' => 'provisioning',
            'provisioning_status' => 'running',
            'provisioning_error' => null,
        ]);

        $dbName = config('tenancy.db_prefix').str_pad((string) $customer->id, 3, '0', STR_PAD_LEFT);
        $host = config('tenancy.db_host');
        $port = (int) config('tenancy.db_port');
        $username = config('tenancy.db_username');
        $password = config('tenancy.db_password');

        $created = false;

        try {
            $this->createDatabase($dbName);
            $created = true;

            $tenantDb = TenantDatabase::updateOrCreate(
                ['customer_id' => $customer->id],
                [
                    'db_name' => $dbName,
                    'db_host' => $host,
                    'db_port' => $port,
                    'db_username' => $username,
                    'db_password' => $password,
                    'status' => 'pending',
                ]
            );

            $this->connections->configure($host, $port, $dbName, $username, $password);
            $this->runTenantMigrations();
            $this->seedTenantBasics($customer, $plainPassword);

            $tenantDb->update([
                'status' => 'ready',
                'migrated_at' => now(),
            ]);

            $customer->update([
                'account_status' => 'active',
                'provisioning_status' => 'completed',
                'subscription_status' => $customer->billing_cycle === 'trial' ? 'trial' : 'active',
            ]);

            AccountLog::create([
                'customer_id' => $customer->id,
                'action' => 'tenant_provisioned',
                'description' => "Database {$dbName} provisioned successfully",
                'ip_address' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Tenant provisioning failed', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);

            if ($created) {
                try {
                    $this->dropDatabase($dbName);
                } catch (\Throwable $dropError) {
                    Log::error('Failed to drop tenant database after error', [
                        'db' => $dbName,
                        'error' => $dropError->getMessage(),
                    ]);
                }
            }

            TenantDatabase::where('customer_id', $customer->id)->update(['status' => 'failed']);

            $customer->update([
                'account_status' => 'failed',
                'provisioning_status' => 'failed',
                'provisioning_error' => $e->getMessage(),
            ]);

            AccountLog::create([
                'customer_id' => $customer->id,
                'action' => 'tenant_provision_failed',
                'description' => $e->getMessage(),
                'ip_address' => request()->ip(),
            ]);

            throw $e;
        } finally {
            $this->connections->disconnect();
        }
    }

    protected function createDatabase(string $dbName): void
    {
        $safe = preg_replace('/[^a-zA-Z0-9_]/', '', $dbName);
        DB::connection('mysql')->statement("CREATE DATABASE IF NOT EXISTS `{$safe}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    protected function dropDatabase(string $dbName): void
    {
        $safe = preg_replace('/[^a-zA-Z0-9_]/', '', $dbName);
        DB::connection('mysql')->statement("DROP DATABASE IF EXISTS `{$safe}`");
    }

    protected function runTenantMigrations(): void
    {
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);
    }

    protected function seedTenantBasics(Customer $customer, string $plainPassword): void
    {
        $permissions = [
            ['name' => 'Manage Companies', 'slug' => 'companies.manage', 'module' => 'companies'],
            ['name' => 'Manage Contractors', 'slug' => 'contractors.manage', 'module' => 'contractors'],
            ['name' => 'Manage Patients', 'slug' => 'patients.manage', 'module' => 'patients'],
            ['name' => 'Manage Doctors', 'slug' => 'doctors.manage', 'module' => 'doctors'],
            ['name' => 'Manage Appointments', 'slug' => 'appointments.manage', 'module' => 'appointments'],
            ['name' => 'Manage Medicines', 'slug' => 'medicines.manage', 'module' => 'medicines'],
            ['name' => 'Manage Prescriptions', 'slug' => 'prescriptions.manage', 'module' => 'prescriptions'],
            ['name' => 'Manage Medical History', 'slug' => 'medical_histories.manage', 'module' => 'medical_histories'],
            ['name' => 'View Reports', 'slug' => 'reports.view', 'module' => 'reports'],
            ['name' => 'Manage Users', 'slug' => 'users.manage', 'module' => 'users'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $roles = [
            'clinic-admin' => 'Clinic Admin',
            'doctor' => 'Doctor',
            'receptionist' => 'Receptionist',
            'staff' => 'Staff',
        ];

        $createdRoles = [];
        foreach ($roles as $slug => $name) {
            $createdRoles[$slug] = Role::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $name.' role',
            ]);
        }

        $allPermissionIds = Permission::pluck('id');
        $createdRoles['clinic-admin']->permissions()->sync($allPermissionIds);

        $createdRoles['doctor']->permissions()->sync(
            Permission::whereIn('slug', [
                'patients.manage', 'appointments.manage', 'prescriptions.manage', 'medical_histories.manage', 'reports.view',
            ])->pluck('id')
        );

        $createdRoles['receptionist']->permissions()->sync(
            Permission::whereIn('slug', [
                'patients.manage', 'appointments.manage', 'companies.manage', 'contractors.manage',
            ])->pluck('id')
        );

        $createdRoles['staff']->permissions()->sync(
            Permission::whereIn('slug', [
                'patients.manage', 'medicines.manage',
            ])->pluck('id')
        );

        User::create([
            'subscription_id' => $this->uniqueSubscriptionId(),
            'first_name' => Str::before($customer->doctor_name, ' ') ?: $customer->doctor_name,
            'last_name' => Str::after($customer->doctor_name, ' ') ?: 'Admin',
            'email' => $customer->email,
            'mobile' => $customer->mobile,
            'password' => $plainPassword,
            'role_id' => $createdRoles['clinic-admin']->id,
            'profile_status' => 'active',
            'subscription_plan' => $customer->plan?->name,
            'subscription_start_date' => $customer->subscription_start_date,
            'subscription_expiry_date' => $customer->subscription_expiry_date,
            'subscription_status' => 'active',
            'grace_period_days' => $customer->grace_period_days,
        ]);
    }

    protected function uniqueSubscriptionId(): string
    {
        do {
            $id = str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (User::where('subscription_id', $id)->exists());

        return $id;
    }
}
