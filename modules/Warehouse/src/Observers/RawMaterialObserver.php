<?php

namespace Modules\Warehouse\Observers;

use Illuminate\Support\Str;
use Modules\Warehouse\Models\RawMaterial;

class RawMaterialObserver
{
    public function creating(RawMaterial $rawMaterial)
    {
        if (! $rawMaterial->sku) {
            $rawMaterial->sku = $this->generateSku($rawMaterial);
        }
    }

    private function generateSku(RawMaterial $rawMaterial): string
    {
        $lastId = (RawMaterial::query()
            ->select('id')
            ->latest()
            ->first()
            ?->id ?? 1) + 1;

        return Str::of($rawMaterial->name)->substr(0, 3)->upper().'-'.Str::of($lastId)->padLeft(4, 0)->value();
    }
}
