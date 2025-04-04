<?php

namespace App\Filament\Clusters;

use App\Filament\Clusters\Traits\DefaultClusterConfig;
use Filament\Clusters\Cluster;

class Inventory extends Cluster
{
    use DefaultClusterConfig;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
}
