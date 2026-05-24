<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'invoice', 'total', 'shipping_cost', 'grand_total',
        'payment_method', 'status', 'shipping_address', 'shipping_courier',
        'shipping_service', 'shipping_receipt', 'notes', 'received_at'
    ];

    protected function casts(): array
    {
        return [
            'received_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function complaint(): HasOne
    {
        return $this->hasOne(Complaint::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->total ?? 0;
    }
}
