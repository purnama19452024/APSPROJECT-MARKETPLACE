<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id', 'label', 'recipient_name', 'recipient_phone',
        'address', 'province', 'city', 'district', 'postal_code',
        'province_code', 'city_code', 'district_code', 'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function provinceData(): BelongsTo
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'province_code', 'code');
    }

    public function cityData(): BelongsTo
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'city_code', 'code');
    }

    public function districtData(): BelongsTo
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\District::class, 'district_code', 'code');
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([$this->address, $this->district, $this->city, $this->province]);
        return implode(', ', $parts);
    }
}
