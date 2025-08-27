<?php

namespace App\Filament\Clusters\Assets\Resources\Organizations\Schemas;

use App\Models\Organization;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class OrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->prefixIcon(Heroicon::BuildingOffice)
                        ->prefixIconColor('primary')
                        ->label('Organization Name')
                        ->placeholder('Enter organization name')
                        ->maxLength(255)
                        ->required(),
                    Select::make('user_id')
                        ->label('Organization Owner')
                        ->prefixIcon(Heroicon::User)
                        ->prefixIconColor('primary')
                        ->relationship('user', 'name', fn ($query) => $query->role(['Manager', 'Landlord'])->orderByDesc('created_at'))
                        ->native(false)
                        //->createOptionAction(fn (Action $action) => $action->visible(auth()->user()->can('manage users')))
                        /*->createOptionForm(function () {
                            return UserForm::configure(Schema::wrap())->getComponents();
                        })*/
                        ->searchable()
                        ->required(),

                    TextInput::make('email')
                        ->label('Organization Email address')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->prefixIcon(Heroicon::AtSymbol)
                        ->prefixIconColor('primary')
                        ->validationMessages([
                            'email' => 'Invalid email address.',
                            'required' => 'Email address is required.',
                            'unique' => 'This email address is already in use.'
                        ])
                        ->required(),
                    TextInput::make('phone')
                        ->label('Organization Phone number')
                        ->tel()
                        ->telRegex('/^(?:\+254|254|0)(7\d{8}|1\d{8})$/')
                        ->prefixIcon(Heroicon::Phone)
                        ->prefixIconColor('primary')
                        ->validationMessages([
                            'required' => 'Phone number is required.',
                            'regex' => 'Invalid phone number.'
                        ])
                        ->required(),
                    TextInput::make('county')
                        ->label('County Name')
                        ->prefixIcon(Heroicon::MapPin)
                        ->prefixIconColor('primary')
                        ->helperText('Enter the county where the organization is located')
                        ->required(),
                    TextInput::make('town')
                        ->label('Town Name')
                        ->prefixIcon(Heroicon::MapPin)
                        ->prefixIconColor('primary')
                        ->helperText('Enter the town where the organization is located')
                        ->required(),
                    Toggle::make('is_visible')
                        ->label('Visible To Users')
                        ->onIcon(Heroicon::ShieldCheck)
                        ->onColor('success')
                        ->offIcon(Heroicon::ShieldExclamation)
                        ->offColor('danger')
                        ->default(fn (?Organization $record) => $record === null ? true : $record->is_visible)
                        ->helperText('If not active, the organization will not be visible to users')
                        ->required()
                ])->columnSpanFull(),
                Section::make()->schema([
                    MarkdownEditor::make('address')
                        ->label('Organization Address')
                        ->placeholder('Enter organization postal or physical address')
                        ->default(null)
                        ->columnSpanFull(),
                ])->columnSpanFull(),
            ])->columns(2)->columnSpan(['lg' => fn (?Organization $record) => $record === null ? 3 : 2]),
            Section::make()->schema([
                TextEntry::make('created_at')->state(fn (Organization $record): ?string => $record->created_at?->diffForHumans()),
                TextEntry::make('updated_at')->label('Last modified at')->state(fn (Organization $record): ?string => $record->updated_at?->diffForHumans()),
            ])->columnSpan(['lg' => 1])->hidden(fn (?Organization $record) => $record === null),
        ])->columns(3);
    }
}
