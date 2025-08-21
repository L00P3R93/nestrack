<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->getStateUsing(fn ($record): ?string => $record->gravatar_url)
                    ->circular()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Full name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->formatStateUsing(function (string $state): string {
                        $parts = explode('@', $state);
                        if(count($parts) !== 2) return $state;

                        $username = $parts[0];
                        $domainParts = explode('.', $parts[1]);
                        $tld = array_pop($domainParts); // Get the TLD (com, net, org, etc.)
                        $domain = implode('.', $domainParts);
                        $maskedUsername = Str::mask($username, '*', 1, -1);
                        $maskedDomain = $domain ? Str::mask($domain, '*', 1, -1).'.'.$tld : $tld;
                        return $maskedUsername . '@' . $maskedDomain;
                    }),
                TextColumn::make('phone')
                    ->label('Phone number')
                    ->formatStateUsing(function (string $state): string {
                        $cleanNumber = preg_replace('/[^0-9]/', '', $state);
                        if(str_starts_with($cleanNumber, '254')) return '254'.Str::mask(substr($cleanNumber, 3), '*', 1, 5);
                        elseif(str_starts_with($cleanNumber, '0')) return '0'.Str::mask(substr($cleanNumber, 2), '*', 1, 4);
                        return Str::mask($state, '*', 3, 4);
                    }),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('roles.name')
                    ->badge()
                    ->label('Role')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('User Status')
                    ->sortable()
                    ->badge(),
                IconColumn::make('is_admin')
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
                SelectFilter::make('roles.name')
                    ->relationship('roles', 'name')
                    ->label('Role')
                    ->native(false)
                    ->preload(),

                SelectFilter::make('status')
                    ->label('User Status')
                    ->native(false)
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'deleted' => 'Deleted',
                        'blocked' => 'Blocked',
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
