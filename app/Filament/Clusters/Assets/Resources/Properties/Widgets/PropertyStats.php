<?php

namespace App\Filament\Clusters\Assets\Resources\Properties\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Clusters\Assets\Resources\Properties\Pages\ListProperties;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PropertyStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListProperties::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Properties', $this->getPageTableQuery()->count()),
            Stat::make('Active Properties', $this->getPageTableQuery()->where('is_visible', true)->count()),
            Stat::make('Total Units', $this->getPageTableQuery()->where('is_visible', true)->sum('units'))
        ];
    }
}
