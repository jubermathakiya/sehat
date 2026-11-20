<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends TenantModel
{
    protected $fillable = ['name', 'address', 'logo', 'remark', 'status'];

    public function contractors(): BelongsToMany
    {
        return $this->belongsToMany(Contractor::class);
    }
}
