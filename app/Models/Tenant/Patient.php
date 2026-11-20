<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends TenantModel
{
    protected $fillable = [
        'patient_code', 'name', 'gender', 'date_of_birth', 'age', 'mobile', 'email',
        'address', 'blood_group', 'allergies', 'contractor_id', 'charges',
        'registration_date', 'parent_patient_id', 'family_relation', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'registration_date' => 'date',
            'charges' => 'decimal:2',
        ];
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_patient_id');
    }

    public function familyMembers(): HasMany
    {
        return $this->hasMany(self::class, 'parent_patient_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function medicalHistories(): HasMany
    {
        return $this->hasMany(MedicalHistory::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PatientDocument::class);
    }
}
