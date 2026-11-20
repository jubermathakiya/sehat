<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends TenantModel
{
    protected $fillable = [
        'prescription_no', 'patient_id', 'doctor_id', 'appointment_id',
        'prescribed_date', 'diagnosis', 'instructions',
    ];

    protected function casts(): array
    {
        return [
            'prescribed_date' => 'date',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
