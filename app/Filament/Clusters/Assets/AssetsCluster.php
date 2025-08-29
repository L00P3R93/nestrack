<?php

namespace App\Filament\Clusters\Assets;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class AssetsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static string | UnitEnum | null $navigationGroup = 'Manage';
    protected static ?int $navigationSort = 0;
    protected static ?string $slug = 'manage/assets';
}
