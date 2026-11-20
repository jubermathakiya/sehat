<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'tenant_code', 'clinic_name', 'doctor_name', 'email', 'mobile', 'address',
        'city', 'state', 'country', 'username', 'password', 'account_status',
        'subscription_plan_id', 'billing_cycle', 'subscription_start_date',
        'subscription_expiry_date', 'grace_period_days', 'subscription_status',
        'provisioning_status', 'provisioning_error', 'last_login_at',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'subscription_start_date' => 'date',
            'subscription_expiry_date' => 'date',
            'last_login_at' => 'datetime',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function database(): HasOne
    {
        return $this->hasOne(TenantDatabase::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(CustomerSubscription::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(SubscriptionTransaction::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AccountLog::class);
    }

    public function isAccessAllowed(): bool
    {
        if (! in_array($this->account_status, ['active'], true)) {
            return false;
        }

        if (! $this->subscription_expiry_date) {
            return true;
        }

        $graceEnd = $this->subscription_expiry_date->copy()->addDays((int) $this->grace_period_days);

        return now()->startOfDay()->lte($graceEnd);
    }

    public function isInGracePeriod(): bool
    {
        if (! $this->subscription_expiry_date) {
            return false;
        }

        $today = now()->startOfDay();

        return $today->gt($this->subscription_expiry_date)
            && $today->lte($this->subscription_expiry_date->copy()->addDays((int) $this->grace_period_days));
    }
}
