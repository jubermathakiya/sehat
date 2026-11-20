<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialization extends TenantModel
{
    protected $fillable = ['name'];

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }
}
