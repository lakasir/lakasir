<?php

namespace App\Filament\Tenant\Resources\Traits;

use App\Models\Tenants\StockOpname;
use Illuminate\Database\Eloquent\Model;

trait HasItemData
{
    protected function fillFormWithDataAndCallHooks(Model|StockOpname $record, array $extraData = []): void
    {
        $this->callHook('beforeFill');
        $record->load($this->loads());

        $data = $this->mutateFormDataBeforeFill([
            ...$record->toArray(),
            ...$extraData,
        ]);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }
}
