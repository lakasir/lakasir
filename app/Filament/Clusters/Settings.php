<?php

namespace App\Filament\Clusters;

use App\Filament\Clusters\Traits\DefaultClusterConfig;
use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    use DefaultClusterConfig;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
}
