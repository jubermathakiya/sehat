<?php

namespace Database\Seeders;

use App\Models\Central\SubscriptionPlan;
use App\Models\Central\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        SuperAdmin::updateOrCreate(
            ['email' => 'admin@sehat.test'],
            [
                'name' => 'Sehat Super Admin',
                'password' => Hash::make('Admin@12345'),
                'is_active' => true,
            ]
        );

        $modules = [
            'companies', 'contractors', 'patients', 'doctors', 'appointments',
            'medicines', 'prescriptions', 'medical_histories', 'reports',
        ];

        SubscriptionPlan::updateOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter',
                'monthly_price' => 999,
                'quarterly_price' => 2699,
                'half_yearly_price' => 4999,
                'yearly_price' => 8999,
                'trial_days' => 14,
                'max_doctors' => 2,
                'max_staff' => 5,
                'max_patients' => 500,
                'available_modules' => $modules,
                'description' => 'Ideal for small clinics',
                'status' => 'active',
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['slug' => 'professional'],
            [
                'name' => 'Professional',
                'monthly_price' => 2499,
                'quarterly_price' => 6999,
                'half_yearly_price' => 12999,
                'yearly_price' => 23999,
                'trial_days' => 14,
                'max_doctors' => 10,
                'max_staff' => 25,
                'max_patients' => 5000,
                'available_modules' => $modules,
                'description' => 'For growing multi-doctor clinics',
                'status' => 'active',
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['slug' => 'enterprise'],
            [
                'name' => 'Enterprise',
                'monthly_price' => 4999,
                'quarterly_price' => 13999,
                'half_yearly_price' => 25999,
                'yearly_price' => 47999,
                'trial_days' => 30,
                'max_doctors' => 100,
                'max_staff' => 200,
                'max_patients' => null,
                'available_modules' => $modules,
                'description' => 'Hospitals and large networks',
                'status' => 'active',
            ]
        );
    }
}
