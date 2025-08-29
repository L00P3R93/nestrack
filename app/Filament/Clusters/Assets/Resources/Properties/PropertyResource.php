<?php

namespace App\Filament\Clusters\Assets\Resources\Properties;

use App\Filament\Clusters\Assets\AssetsCluster;
use App\Filament\Clusters\Assets\Resources\Properties\Pages\CreateProperty;
use App\Filament\Clusters\Assets\Resources\Properties\Pages\EditProperty;
use App\Filament\Clusters\Assets\Resources\Properties\Pages\ListProperties;
use App\Filament\Clusters\Assets\Resources\Properties\Schemas\PropertyForm;
use App\Filament\Clusters\Assets\Resources\Properties\Tables\PropertiesTable;
use App\Filament\Clusters\Assets\Resources\Properties\Widgets\PropertyStats;
use App\Models\Property;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $cluster = AssetsCluster::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;


    protected static ?string $navigationLabel = 'Properties';
    protected static ?int $navigationSort = 1;



    public static function form(Schema $schema): Schema
    {
        return PropertyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PropertiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            PropertyStats::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProperties::route('/'),
            'create' => CreateProperty::route('/create'),
            'edit' => EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getNavigationBadge(): ?string
    {
        $modelClass = static::$model;
        return (string) $modelClass::query()->where('is_visible', true)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Active Properties';
    }

}
