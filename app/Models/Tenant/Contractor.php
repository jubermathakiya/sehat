<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contractor extends TenantModel
{
    protected $fillable = [
        'name', 'contractor_code', 'mobile', 'alternate_mobile',
        'email', 'address', 'status', 'remarks',
    ];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }
}
