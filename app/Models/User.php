<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserStatus;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Notifications\Notification;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => UserStatus::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return ($this->status === UserStatus::Active) && $this->with('roles');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->gravatar_url;
    }

    public function getGravatarUrlAttribute(): string
    {
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?s=200&d=retro";
    }

    protected static function booted(): void
    {
        static::created(function (self $user) {
            Notification::make()
                ->title('New User Created')
                ->body("User {$user->name} has been created")
                ->icon($user->gravatar_url ?? Heroicon::User)
                ->actions([
                    Action::make('View')->url(UserResource::getUrl('edit', ['record' => $user])),
                ])->sendToDatabase(User::query()->where('is_admin', true)->get());
        });

        static::updated(function (self $user) {
            Notification::make()
                ->title('User Updated')
                ->body("User {$user->name} has been updated")
                ->icon($user->gravatar_url ?? Heroicon::User)
                ->actions([
                    Action::make('View')->url(UserResource::getUrl('edit', ['record' => $user])),
                ])->sendToDatabase(User::query()->where('is_admin', true)->get());
        });
    }
}
