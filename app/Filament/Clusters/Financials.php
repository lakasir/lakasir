<?php

namespace App\Filament\Clusters;

use App\Filament\Clusters\Traits\DefaultClusterConfig;
use Filament\Clusters\Cluster;

class Financials extends Cluster
{
    use DefaultClusterConfig;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
}
