<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperBarcode
 */
class Barcode extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the product that this barcode belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope a query to only include active barcodes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to find a barcode by its code.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $code The barcode string.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Scope a query to only include primary barcodes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrimary($query)
    {
        return $query->where('type', 'primary');
    }

    /**
     * Find the product associated with a specific barcode code.
     *
     * @param string $code The barcode string to search for.
     * @return ?Product The product model, or null if not found.
     */
    public static function findProductByCode(string $code): ?Product
    {
        $barcode = static::active()
            ->byCode($code)
            ->with('product')
            ->first();

        return $barcode?->product;
    }

    /**
     * Get the primary barcode string for a given product ID.
     *
     * @param int $productId The ID of the product.
     * @return ?string The primary barcode string, or null if not found.
     */
    public static function getPrimaryCodeForProduct(int $productId): ?string
    {
        return static::where('product_id', $productId)
            ->primary()
            ->active()
            ->value('code');
    }

    /**
     * Check if a given barcode code already exists (excluding an optional barcode ID).
     *
     * @param string $code The barcode string to check.
     * @param ?int $excludeBarcodeId An optional ID of a barcode to exclude from the check (e.g., when updating).
     * @return bool True if the code exists, false otherwise.
     */
    public static function codeExists(string $code, ?int $excludeBarcodeId = null): bool
    {
        $query = static::where('code', $code);

        if ($excludeBarcodeId) {
            $query->where('id', '!=', $excludeBarcodeId);
        }

        return $query->exists();
    }
}
