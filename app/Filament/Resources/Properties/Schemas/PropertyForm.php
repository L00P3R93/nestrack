<?php

namespace App\Filament\Resources\Properties\Schemas;

use App\Models\Property;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->label('Property Name')
                        ->prefixIcon(Heroicon::OutlinedBuildingOffice2)
                        ->prefixIconColor('primary')
                        ->placeholder('Enter property name')
                        ->maxLength(255)
                        ->required(),

                    Select::make('organization_id')
                        ->label('Organization')
                        ->prefixIcon(Heroicon::BuildingOffice)
                        ->prefixIconColor('primary')
                        ->placeholder('Select organization')
                        ->relationship('organization', 'name', fn ($query) => $query->where('is_visible', true)->orderByDesc('created_at'))
                        ->native(false)
                        ->searchable()
                        ->required(),


                    TextInput::make('county')
                        ->label('County Name')
                        ->prefixIcon(Heroicon::MapPin)
                        ->prefixIconColor('primary')
                        ->helperText('Enter the county where the property is located')
                        ->required(),
                    TextInput::make('town')
                        ->label('Town Name')
                        ->prefixIcon(Heroicon::MapPin)
                        ->prefixIconColor('primary')
                        ->helperText('Enter the town where the property is located')
                        ->required(),

                    Select::make('type')
                        ->label('Property Type')
                        ->prefixIcon(Heroicon::OutlinedBuildingStorefront)
                        ->prefixIconColor('primary')
                        ->placeholder('Select property type')
                        ->options([
                            'residential' => 'Residential',
                            'commercial' => 'Commercial',
                        ])
                        ->native(false)
                        ->required(),
                    TextInput::make('units')
                        ->label('Total Units')
                        ->prefixIcon(Heroicon::Hashtag)
                        ->prefixIconColor('primary')
                        ->helperText('Enter the total number of units available in the building.')
                        ->required()
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_visible')
                        ->label('Visible To Users')
                        ->onIcon(Heroicon::ShieldCheck)
                        ->onColor('success')
                        ->offIcon(Heroicon::ShieldExclamation)
                        ->offColor('danger')
                        ->default(fn (?Property $record) => $record === null ? true : $record->is_visible)
                        ->helperText('If not active, the property will not be visible to users')
                        ->required()
                ])->columnSpanFull(),
                Section::make()->schema([
                    MarkdownEditor::make('address')
                        ->label('Property Address')
                        ->placeholder('Enter property postal or physical address')
                        ->default(null)
                        ->columnSpanFull(),
                    MarkdownEditor::make('google_map')
                        ->label('Google Map Url/Link')
                        ->default(null)
                        ->columnSpanFull(),
                    MarkdownEditor::make('description')
                        ->label('Property Description')
                        ->default(null)
                        ->columnSpanFull(),
                ])->columnSpanFull()->collapsed(),

            ])->columns(2)->columnSpan(['lg' => fn (?Property $record) => $record === null ? 3 : 2]),
            Section::make()->schema([
                TextEntry::make('created_at')->state(fn (Property $record): ?string => $record->created_at?->diffForHumans()),
                TextEntry::make('updated_at')->label('Last modified at')->state(fn (Property $record): ?string => $record->updated_at?->diffForHumans()),
            ])->columnSpan(['lg' => 1])->hidden(fn (?Property $record) => $record === null),

        ])->columns(3);
    }
}
