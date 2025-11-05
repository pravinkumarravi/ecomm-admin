<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'coupon_id',
        'address_id',
        'order_number',
        'currency',
        'status',
        'subtotal',
        'discount',
        'tax',
        'shipping_cost',
        'total',
        'coupon_code',
        'coupon_value',
        'tracking_number',
        'carrier',
        'shipped_at',
        'delivered_at',
        'customer_notes',
        'admin_notes',
        'ordered_at',
        'cancelled_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
            'coupon_value' => 'decimal:2',
            'ordered_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    // Relationships - All verified with correct foreign keys
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'order_id');
    }

    // Helper methods
    public function getLatestTransaction(): ?Transaction
    {
        return $this->transactions()->latest('created_at')->first();
    }

    public function getSuccessfulTransaction(): ?Transaction
    {
        return $this->transactions()
            ->where('type', 'payment')
            ->where('status', 'success')
            ->latest('completed_at')
            ->first();
    }

    public function getPaymentStatus(): ?string
    {
        return $this->getLatestTransaction()?->status;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->getLatestTransaction()?->payment_method;
    }

    public function isPaid(): bool
    {
        return $this->transactions()
            ->where('type', 'payment')
            ->where('status', 'success')
            ->exists();
    }

    public function isRefunded(): bool
    {
        return $this->transactions()
            ->whereIn('type', ['refund', 'partial_refund'])
            ->where('status', 'success')
            ->exists();
    }

    public function getTotalRefunded(): float
    {
        return (float) $this->transactions()
            ->whereIn('type', ['refund', 'partial_refund'])
            ->where('status', 'success')
            ->sum('amount');
    }
}
