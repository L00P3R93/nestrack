<?php

namespace App\Filament\Clusters\Assets\Resources\Organizations;

use App\Filament\Clusters\Assets\AssetsCluster;
use App\Filament\Clusters\Assets\Resources\Organizations\Pages\CreateOrganization;
use App\Filament\Clusters\Assets\Resources\Organizations\Pages\EditOrganization;
use App\Filament\Clusters\Assets\Resources\Organizations\Pages\ListOrganizations;
use App\Filament\Clusters\Assets\Resources\Organizations\Schemas\OrganizationForm;
use App\Filament\Clusters\Assets\Resources\Organizations\Tables\OrganizationsTable;
use App\Models\Organization;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;
    protected static ?string $cluster = AssetsCluster::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice;
    protected static ?string $navigationParentItem = 'Properties';
    protected static ?int $navigationSort = 0;



    public static function form(Schema $schema): Schema
    {
        return OrganizationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganizationsTable::configure($table);
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
            'index' => ListOrganizations::route('/'),
            'create' => CreateOrganization::route('/create'),
            'edit' => EditOrganization::route('/{record}/edit'),
        ];
    }
}
