<?php

namespace App\Filament\Clusters\Assets\Resources\Properties\Pages;

use App\Filament\Clusters\Assets\Resources\Properties\PropertyResource;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListProperties extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = PropertyResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return PropertyResource::getWidgets();
    }
}
