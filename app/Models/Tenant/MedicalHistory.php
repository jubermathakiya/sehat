<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalHistory extends TenantModel
{
    protected $fillable = [
        'patient_id', 'doctor_id', 'visit_date', 'diagnosis',
        'treatment_details', 'allergies', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
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

    public function documents(): HasMany
    {
        return $this->hasMany(PatientDocument::class);
    }
}
