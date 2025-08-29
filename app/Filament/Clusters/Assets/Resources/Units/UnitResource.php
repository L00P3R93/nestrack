<?php

namespace App\Filament\Clusters\Assets\Resources\Units;

use App\Filament\Clusters\Assets\AssetsCluster;
use App\Filament\Clusters\Assets\Resources\Units\Pages\CreateUnit;
use App\Filament\Clusters\Assets\Resources\Units\Pages\EditUnit;
use App\Filament\Clusters\Assets\Resources\Units\Pages\ListUnits;
use App\Filament\Clusters\Assets\Resources\Units\Schemas\UnitForm;
use App\Filament\Clusters\Assets\Resources\Units\Tables\UnitsTable;
use App\Models\Unit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $cluster = AssetsCluster::class;

    protected static ?string $recordTitleAttribute = 'unit_code';
    protected static ?string $navigationParentItem = 'Properties';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return UnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUnits::route('/'),
            'create' => CreateUnit::route('/create'),
            'edit' => EditUnit::route('/{record}/edit'),
        ];
    }
}
