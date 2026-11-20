<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicineCategory extends TenantModel
{
    protected $fillable = ['name'];

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }
}
