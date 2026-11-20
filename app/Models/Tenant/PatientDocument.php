<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientDocument extends TenantModel
{
    protected $fillable = [
        'patient_id', 'medical_history_id', 'title', 'file_path', 'file_type',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicalHistory(): BelongsTo
    {
        return $this->belongsTo(MedicalHistory::class);
    }
}
