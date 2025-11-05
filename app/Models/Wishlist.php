<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public static function addToWishlist(User $user, Product $product): bool
    {
        return self::firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ])->wasRecentlyCreated;
    }

    public static function removeFromWishlist(User $user, Product $product): bool
    {
        return self::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete() > 0;
    }

    public static function isInWishlist(User $user, Product $product): bool
    {
        return self::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();
    }

    public static function getWishlistCount(User $user): int
    {
        return self::where('user_id', $user->id)->count();
    }

    public static function toggleWishlist(User $user, Product $product): bool
    {
        $wishlist = self::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return false; // Removed from wishlist
        }

        self::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return true; // Added to wishlist
    }
}
