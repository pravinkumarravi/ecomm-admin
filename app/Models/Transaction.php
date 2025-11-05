<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'parent_transaction_id',
        'transaction_number',
        'gateway_transaction_id',
        'payment_method',
        'type',
        'status',
        'amount',
        'currency',
        'gateway_fee',
        'gateway',
        'gateway_response',
        'card_last_four',
        'card_brand',
        'bank_name',
        'ip_address',
        'notes',
        'failure_reason',
        'attempted_at',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_fee' => 'decimal:2',
        'gateway_response' => 'array',
        'attempted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships - All verified with correct foreign keys
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parentTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'parent_transaction_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Transaction::class, 'parent_transaction_id');
    }

    // Helper methods
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isPayment(): bool
    {
        return $this->type === 'payment';
    }

    public function isRefund(): bool
    {
        return in_array($this->type, ['refund', 'partial_refund']);
    }

    public function isFullRefund(): bool
    {
        return $this->type === 'refund';
    }

    public function isPartialRefund(): bool
    {
        return $this->type === 'partial_refund';
    }

    public function getNetAmount(): float
    {
        return (float) ($this->amount - $this->gateway_fee);
    }

    public function getMaskedCardNumber(): ?string
    {
        return $this->card_last_four ? "**** **** **** {$this->card_last_four}" : null;
    }

    public function getTotalRefunded(): float
    {
        if (!$this->isPayment()) {
            return 0;
        }

        return (float) $this->refunds()
            ->where('status', 'success')
            ->sum('amount');
    }

    public function canBeRefunded(): bool
    {
        return $this->isPayment() 
            && $this->isSuccessful() 
            && $this->getTotalRefunded() < $this->amount;
    }
}
