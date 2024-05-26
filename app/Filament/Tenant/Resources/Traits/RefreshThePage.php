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
        return ['refreshExampleRelationManager' => '$refresh'];
    }
}
