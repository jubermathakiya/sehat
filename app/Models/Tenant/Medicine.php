<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medicine extends TenantModel
{
    protected $fillable = [
        'name', 'medicine_category_id', 'composition', 'manufacturer',
        'unit', 'stock', 'price', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MedicineCategory::class, 'medicine_category_id');
    }
}
