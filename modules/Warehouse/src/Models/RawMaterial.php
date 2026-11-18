<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Warehouse\Observers\RawMaterialObserver;

/**
 * One purchased ingredient, costed the way a recipe sheet does it:
 *
 *   bahan | input_qty input_unit | harga_total | base_qty base_unit | harga_per_base_unit | porsi_qty porsi_satuan | harga_porsi
 *   Ayam  | 1 kg                 | 37000       | 1000 g                | 37                  | 83.33 g               | 3083
 *
 * `price_per_base_unit` is the single source of truth for cost; every recipe
 * line prices itself as `price_per_base_unit * convert(porsi_qty)`.
 */
#[ObservedBy([RawMaterialObserver::class])]
class RawMaterial extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'input_qty' => 'float',
        'harga_total' => 'float',
        'base_qty' => 'float',
        'price_per_base_unit' => 'float',
        'quantity' => 'float',
    ];

    /** Fallback factors, used before the units table exists (migrations). */
    public const DEFAULT_UNIT_FACTORS = [
        'mg' => ['family' => 'mass', 'factor' => 0.001],
        'g' => ['family' => 'mass', 'factor' => 1.0],
        'dag' => ['family' => 'mass', 'factor' => 10.0],
        'hg' => ['family' => 'mass', 'factor' => 100.0],
        'kg' => ['family' => 'mass', 'factor' => 1000.0],
        'ml' => ['family' => 'volume', 'factor' => 1.0],
        'cl' => ['family' => 'volume', 'factor' => 10.0],
        'dl' => ['family' => 'volume', 'factor' => 100.0],
        'l' => ['family' => 'volume', 'factor' => 1000.0],
        'pcs' => ['family' => 'count', 'factor' => 1.0],
        'pack' => ['family' => 'count', 'factor' => 1.0],
        'tabung' => ['family' => 'count', 'factor' => 1.0],
        'masakan' => ['family' => 'count', 'factor' => 1.0],
    ];

    /**
     * Common spellings mapped onto a canonical unit name.
     */
    private const UNIT_ALIASES = [
        'gram' => 'g',
        'gr' => 'g',
        'kilo' => 'kg',
        'kilogram' => 'kg',
        'liter' => 'l',
        'litre' => 'l',
        'mililiter' => 'ml',
        'milliliter' => 'ml',
        'pcs' => 'pcs',
        'pc' => 'pcs',
        'piece' => 'pcs',
        'butir' => 'pcs',
        'biji' => 'pcs',
        'bungkus' => 'pack',
        'sachet' => 'pcs',
    ];

    public static function normalizeUnit(?string $unit): ?string
    {
        if ($unit === null) {
            return null;
        }

        $unit = strtolower(trim($unit));

        return self::UNIT_ALIASES[$unit] ?? $unit;
    }

    /**
     * How many base units make one of `$unit`, e.g. kg => 1000 grams.
     */
    public static function unitFactor(?string $unit): ?float
    {
        $unit = self::normalizeUnit($unit);

        if ($unit === null) {
            return null;
        }

        $fromTable = Unit::query()->where('name', $unit)->value('factor');

        if ($fromTable !== null) {
            return (float) $fromTable;
        }

        return self::DEFAULT_UNIT_FACTORS[$unit]['factor'] ?? null;
    }

    public static function unitFamily(?string $unit): ?string
    {
        $unit = self::normalizeUnit($unit);

        if ($unit === null) {
            return null;
        }

        $fromTable = Unit::query()->where('name', $unit)->value('family');

        if ($fromTable !== null) {
            return $fromTable;
        }

        return self::DEFAULT_UNIT_FACTORS[$unit]['family'] ?? null;
    }

    /**
     * Units a recipe line may be expressed in: those measuring the same thing
     * as this material's base unit.
     *
     * @return array<string, string>
     */
    public static function unitOptions(?int $rawMaterialId): array
    {
        $own = self::normalizeUnit(self::query()->find($rawMaterialId)?->base_unit);
        $family = self::unitFamily($own);

        if ($own === null || $family === null) {
            return $own === null ? [] : [$own => strtoupper($own)];
        }

        $options = Unit::query()
            ->where('family', $family)
            ->orderBy('factor')
            ->pluck('name')
            ->all();

        if ($options === []) {
            $options = array_keys(array_filter(
                self::DEFAULT_UNIT_FACTORS,
                fn (array $definition) => $definition['family'] === $family,
            ));
        }

        return collect($options)
            ->mapWithKeys(fn (string $unit) => [$unit => strtoupper($unit)])
            ->all();
    }

    /**
     * Convert `$quantity` from `$fromUnit` into this material's base unit.
     *
     * Returns null when the units cannot be reconciled, so callers can refuse
     * to price rather than inventing a number.
     */
    public function toBaseUnits(float $quantity, ?string $fromUnit): ?float
    {
        $from = self::unitFactor($fromUnit);
        $to = self::unitFactor($this->base_unit);

        if ($from === null || $to === null || $to == 0.0) {
            return null;
        }

        if (self::unitFamily($fromUnit) !== self::unitFamily($this->base_unit)) {
            return null;
        }

        return $quantity * $from / $to;
    }

    /**
     * Cost of one recipe line: porsi of this material, in `$unit`.
     *
     * Returns null when the unit is incompatible with the material, which the
     * form surfaces as a validation error instead of a wrong price.
     */
    public function costFor(float $quantity, ?string $unit = null): ?float
    {
        $unit ??= $this->base_unit;

        $inBase = $this->toBaseUnits($quantity, $unit);

        if ($inBase === null) {
            return null;
        }

        return (float) $this->price_per_base_unit * $inBase;
    }

    /**
     * Recompute base quantity and unit price from the purchase figures.
     *
     * @return bool whether the purchase could be costed
     */
    public function recalculateBaseUnitPrice(): bool
    {
        $baseUnit = $this->base_unit
            ?: self::baseUnitFor(self::unitFamily($this->input_unit))
            ?: 'pcs';

        // A package declares its own unpacked size (1 pack = 24 pcs); anything
        // else derives it from the unit factor (1 kg = 1000 g).
        $override = $this->base_quantity_override;
        $factor = self::unitFactor($this->input_unit);

        $baseQty = $override !== null && (float) $override > 0
            ? (float) $override
            : ($factor !== null ? (float) $this->input_qty * $factor : null);

        if ($baseQty === null || $baseQty <= 0 || (float) $this->harga_total <= 0) {
            return false;
        }

        $this->base_qty = $baseQty;
        $this->base_unit = $baseUnit;
        $this->price_per_base_unit = (float) $this->harga_total / $baseQty;

        return true;
    }

    public static function baseUnitFor(?string $family): ?string
    {
        return match ($family) {
            'mass' => 'g',
            'volume' => 'ml',
            'count' => 'pcs',
            default => null,
        };
    }
}
