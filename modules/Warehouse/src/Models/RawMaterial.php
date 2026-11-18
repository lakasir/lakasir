<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Warehouse\Observers\RawMaterialObserver;

#[ObservedBy([RawMaterialObserver::class])]
class RawMaterial extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Base units each supported unit converts to, and what they measure.
     *
     * Mass is expressed in grams, volume in millilitres, count in pieces.
     * `price_per_unit` is the price of one whole unit (one kg, one litre),
     * so a recipe asking for 200 g of an kg-priced material must pay a
     * fraction of the kg price.
     */
    private const UNIT_FACTORS = [
        'kg' => ['base' => 'g', 'factor' => 1000.0],
        'hg' => ['base' => 'g', 'factor' => 100.0],
        'dag' => ['base' => 'g', 'factor' => 10.0],
        'g' => ['base' => 'g', 'factor' => 1.0],
        'mg' => ['base' => 'g', 'factor' => 0.001],
        'l' => ['base' => 'ml', 'factor' => 1000.0],
        'dl' => ['base' => 'ml', 'factor' => 100.0],
        'cl' => ['base' => 'ml', 'factor' => 10.0],
        'ml' => ['base' => 'ml', 'factor' => 1.0],
        'pcs' => ['base' => 'pcs', 'factor' => 1.0],
        'piece' => ['base' => 'pcs', 'factor' => 1.0],
        'butir' => ['base' => 'pcs', 'factor' => 1.0],
        'sachet' => ['base' => 'pcs', 'factor' => 1.0],
    ];

    /**
     * Units a recipe line may be expressed in, limited to those measuring the
     * same thing as the material's own unit.
     *
     * @return array<string, string>
     */
    public static function unitOptions(?int $rawMaterialId): array
    {
        $own = RawMaterial::query()->find($rawMaterialId)?->unit;

        if ($own === null) {
            return [];
        }

        $base = self::UNIT_FACTORS[strtolower(trim($own))]['base'] ?? null;

        if ($base === null) {
            return [$own => strtoupper($own)];
        }

        $options = [];

        foreach (self::UNIT_FACTORS as $unit => $definition) {
            if ($definition['base'] === $base) {
                $options[$unit] = strtoupper($unit);
            }
        }

        return $options;
    }

    /**
     * Cost of a recipe line, converting the quantity into the material's
     * own unit first.
     *
     * `$quantity` is expressed in `$unit`, defaulting to the material's own
     * unit so existing recipes that stored a bare quantity keep working.
     */
    public function costFor(float $quantity, ?string $unit = null): float
    {
        return (float) $this->price_per_unit * $this->toUnits($quantity, $unit ?? $this->unit);
    }

    /**
     * Convert `$quantity` from `$fromUnit` into the material's own unit.
     *
     * When either unit is unknown or the two measure different things there
     * is no safe conversion, so the quantity is returned unchanged; that is
     * the previous behaviour and keeps misconfigured data from silently
     * changing price by a magic factor.
     */
    public function toUnits(float $quantity, string $fromUnit): float
    {
        $from = self::UNIT_FACTORS[strtolower(trim($fromUnit))] ?? null;
        $to = self::UNIT_FACTORS[strtolower(trim((string) $this->unit))] ?? null;

        if ($from === null || $to === null || $from['base'] !== $to['base']) {
            return $quantity;
        }

        return $quantity * $from['factor'] / $to['factor'];
    }
}

