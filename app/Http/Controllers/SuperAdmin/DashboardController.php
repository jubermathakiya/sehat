<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\Customer;
use App\Models\Central\SubscriptionTransaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('account_status', 'active')->count(),
            'expired_customers' => Customer::where('subscription_status', 'expired')->count(),
            'trial_customers' => Customer::where('subscription_status', 'trial')->count(),
            'total_subscriptions' => Customer::whereNotNull('subscription_plan_id')->count(),
            'monthly_revenue' => SubscriptionTransaction::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->sum('amount'),
            'yearly_revenue' => SubscriptionTransaction::whereYear('created_at', now()->year)->sum('amount'),
        ];

        $recentCustomers = Customer::with('plan')->latest()->take(8)->get();
        $upcomingExpiries = Customer::with('plan')
            ->whereBetween('subscription_expiry_date', [now()->toDateString(), now()->addDays(15)->toDateString()])
            ->orderBy('subscription_expiry_date')
            ->take(8)
            ->get();

        return view('superadmin.dashboard.index', compact('stats', 'recentCustomers', 'upcomingExpiries'));
    }
}
