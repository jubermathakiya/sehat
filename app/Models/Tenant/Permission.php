<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends TenantModel
{
    protected $fillable = ['name', 'slug', 'module'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
