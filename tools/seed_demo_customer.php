<?php

use App\Models\Central\SubscriptionPlan;
use App\Services\Central\CustomerService;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$svc = $app->make(CustomerService::class);
$plan = SubscriptionPlan::firstOrFail();

$customer = $svc->create([
    'clinic_name' => 'Demo Care Clinic',
    'doctor_name' => 'Dr Demo',
    'email' => 'demo@clinic.test',
    'mobile' => '9999999999',
    'address' => 'Demo Street',
    'city' => 'Mumbai',
    'state' => 'MH',
    'country' => 'India',
    'username' => 'democlinic',
    'password' => 'Clinic@12345',
    'subscription_plan_id' => $plan->id,
    'billing_cycle' => 'monthly',
    'subscription_start_date' => now()->toDateString(),
    'grace_period_days' => 3,
    'discount' => 0,
], true);

$customer->load('database');

echo 'OK '.$customer->tenant_code.' status='.$customer->account_status.' db='.optional($customer->database)->db_name.PHP_EOL;
