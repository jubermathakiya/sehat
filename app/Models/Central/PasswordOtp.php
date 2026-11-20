<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class PasswordOtp extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'tenant_code', 'email', 'otp', 'expires_at', 'verified_at', 'attempts',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function isValid(): bool
    {
        return $this->verified_at === null && $this->expires_at->isFuture() && $this->attempts < 5;
    }
}
