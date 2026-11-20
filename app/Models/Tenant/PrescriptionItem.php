<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionItem extends TenantModel
{
    protected $fillable = [
        'prescription_id', 'medicine_id', 'dosage', 'frequency', 'duration', 'instructions',
    ];

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
