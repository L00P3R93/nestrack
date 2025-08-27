<?php

namespace App\Filament\Clusters\Assets\Resources\Properties\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PropertiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Property Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('organization.name')
                    ->label('Organization')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('town')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('county')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('type')
                    ->label('Property Type')
                    ->sortable()
                    ->searchable()
                    ->badge(),
                TextColumn::make('units')
                    ->label('Total Units')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_visible')
                    ->label('Visible To Users')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_visible')->label('Visible To Users')->options([
                    true => 'Yes',
                    false => 'No',
                ]),
                SelectFilter::make('type')->label('Property Type')->options([
                    'residential' => 'Residential',
                    'commercial' => 'Commercial',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
