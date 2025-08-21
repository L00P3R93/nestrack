<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserStatus;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('name')
                    ->label('Full name')
                    ->prefixIcon(Heroicon::User)
                    ->prefixIconColor('primary')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
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
                    ->tel()
                    ->telRegex('/^(?:\+254|254|0)(7\d{8}|1\d{8})$/')
                    ->prefixIcon(Heroicon::Phone)
                    ->prefixIconColor('primary')
                    ->validationMessages([
                        'required' => 'Phone number is required.',
                        'regex' => 'Invalid phone number.'
                    ])
                    ->required(),
                Select::make('status')
                    ->label('User Status')
                    ->options(UserStatus::class)
                    ->default('active')
                    ->prefixIcon(Heroicon::ShieldExclamation)
                    ->prefixIconColor('primary')
                    ->native(false)
                    ->required(),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Roles')
                    ->preload()
                    ->multiple()
                    ->native(false)
                    //->disabled(fn (?User $record) => $record !== null)
                    ->createOptionAction(fn (Action $action) => $action->visible(auth()->user()->can('manage roles')))
                    ->createOptionForm(function () {
                        return RoleForm::configure(Schema::wrap())->getComponents();
                    })
                    ->searchable()
                    ->required(),
                Toggle::make('is_admin')
                    ->label('Mark as admin')
                    ->onIcon(Heroicon::ShieldCheck)
                    ->onColor('success')
                    ->offIcon(Heroicon::ShieldExclamation)
                    ->offColor('danger')
                    ->helperText('This will give the user admin privileges.')
                    ->disabled(fn () => !auth()->user()->hasRole('Admin'))
                    ->default(false)
                    ->required(),

                Section::make('Password')->schema([
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required(fn (string $context) => $context === 'create')
                        ->dehydrated(fn ($state) => filled($state)) // only send if filled
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null) // hash if filled
                        ->rules([
                            'confirmed',
                            'regex:/^(?=.*[A-Z])(?=.*[\W_]).{8,}$/',
                        ])
                        ->validationMessages([
                            'regex' => 'Password must be at least 8 characters long, contain at least one uppercase letter, and one special symbol.',
                            'confirmed' => 'Password confirmation does not match.',
                            'required' => 'Password is required.',
                        ]),
                    TextInput::make('password_confirmation')
                        ->label('Confirm Password')
                        ->password()
                        ->dehydrated(false) // don't send to DB
                        ->required(fn (string $context) => $context === 'create'),
                ])->columnSpanFull()->hidden(fn (?User $record) => $record !== null),
            ])->columns(2)->columnSpan(['lg' => fn (?User $record) => $record === null ? 3 : 2]),
            Section::make()->schema([
                TextEntry::make('created_at')->state(fn (User $record): ?string => $record->created_at?->diffForHumans()),
                TextEntry::make('updated_at')->label('Last modified at')->state(fn (User $record): ?string => $record->updated_at?->diffForHumans()),
            ])->columnSpan(['lg' => 1])->hidden(fn (?User $record) => $record === null),

        ])->columns(3);
    }
}
