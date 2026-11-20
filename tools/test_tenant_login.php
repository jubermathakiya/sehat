<?php

use App\Models\Central\Customer;
use App\Models\Tenant\User;
use App\Services\Tenant\TenantConnectionManager;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$customer = Customer::with('database')->where('email', 'demo@clinic.test')->firstOrFail();
$app->make(TenantConnectionManager::class)->connect($customer);
$user = User::where('email', 'demo@clinic.test')->first();

echo 'Tenant: '.$customer->tenant_code.PHP_EOL;
echo 'User found: '.($user ? $user->email.' / '.$user->subscription_id : 'NO').PHP_EOL;
echo 'Password ok: '.($user && Hash::check('Clinic@12345', $user->password) ? 'YES' : 'NO').PHP_EOL;
echo 'Access allowed: '.($customer->isAccessAllowed() ? 'YES' : 'NO').PHP_EOL;
