<?php

namespace App\Filament\Tenant\Resources\Traits;

trait RefreshThePage
{
    protected function refreshPage()
    {
        $this->dispatch('refreshExampleRelationManager');
    }

    public function getListeners()
    {
        if (method_exists($this, 'calculateTotalPrice')) {
            $this->calculateTotalPrice();
        }

        return ['refreshExampleRelationManager' => '$refresh'];
    }
}
