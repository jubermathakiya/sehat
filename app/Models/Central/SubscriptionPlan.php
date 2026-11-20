<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'name', 'slug', 'monthly_price', 'quarterly_price', 'half_yearly_price', 'yearly_price',
        'trial_days', 'max_doctors', 'max_staff', 'max_patients', 'available_modules',
        'description', 'status',
    ];

    protected function casts(): array
    {
        return [
            'monthly_price' => 'decimal:2',
            'quarterly_price' => 'decimal:2',
            'half_yearly_price' => 'decimal:2',
            'yearly_price' => 'decimal:2',
            'available_modules' => 'array',
        ];
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function priceForCycle(string $cycle): float
    {
        return match ($cycle) {
            'monthly' => (float) $this->monthly_price,
            'quarterly' => (float) $this->quarterly_price,
            'half_yearly' => (float) $this->half_yearly_price,
            'yearly' => (float) $this->yearly_price,
            default => 0,
        };
    }
}
