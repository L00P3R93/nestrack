<?php

namespace App\Filament\Clusters\Assets\Resources\Organizations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrganizationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Organization Name')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Organization Owner')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Org Phone Number')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Org Email address')
                    ->searchable(),
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
                SelectFilter::make('is_visible')->label('Visible Organizations')->options([
                    true => 'Visible',
                    false => 'Hidden',
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
