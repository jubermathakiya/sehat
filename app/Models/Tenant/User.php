<?php

namespace App\Models\Tenant;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'tenant';

    protected $fillable = [
        'subscription_id', 'first_name', 'last_name', 'email', 'mobile', 'password',
        'role_id', 'profile_status', 'subscription_plan', 'subscription_start_date',
        'subscription_expiry_date', 'subscription_status', 'grace_period_days', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'subscription_start_date' => 'date',
            'subscription_expiry_date' => 'date',
            'last_login_at' => 'datetime',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function getNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function hasPermission(string $slug): bool
    {
        if (! $this->role) {
            return false;
        }

        if ($this->role->slug === 'clinic-admin') {
            return true;
        }

        return $this->role->permissions->contains('slug', $slug);
    }
}
