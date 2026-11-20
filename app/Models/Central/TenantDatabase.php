<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class TenantDatabase extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'customer_id', 'db_name', 'db_host', 'db_port', 'db_username',
        'db_password', 'status', 'migrated_at',
    ];

    protected function casts(): array
    {
        return [
            'migrated_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function setDbPasswordAttribute($value): void
    {
        $this->attributes['db_password'] = Crypt::encryptString((string) $value);
    }

    public function getDecryptedPasswordAttribute(): string
    {
        try {
            return Crypt::decryptString($this->db_password);
        } catch (\Throwable) {
            return (string) $this->db_password;
        }
    }
}
