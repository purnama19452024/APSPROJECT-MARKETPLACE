<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequest extends Model
{
    protected $fillable = [
        'user_id', 'transaction_id', 'transaction_item_id', 'reason',
        'description', 'images', 'status', 'refund_amount', 'response', 'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'responded_at' => 'datetime',
            'refund_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(TransactionItem::class, 'transaction_item_id');
    }
}
