<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum UserStatus: string implements HasLabel, HasColor, HasIcon
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
    case Deleted = 'deleted';
    case Blocked = 'blocked';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Suspended => 'Suspended',
            self::Deleted => 'Deleted',
            self::Blocked => 'Blocked',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Inactive => 'warning',
            self::Suspended => 'primary',
            self::Deleted => 'secondary',
            self::Blocked => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Active => 'heroicon-s-check-circle',     // ✅ clearly means active/ok
            self::Inactive => 'heroicon-s-pause-circle',   // ⏸️ paused/inactive
            self::Suspended => 'heroicon-s-exclamation-circle', // ⚠️ warning/suspended
            self::Deleted => 'heroicon-s-trash',           // 🗑️ deleted
            self::Blocked => 'heroicon-c-x-circle',             // 🚫 blocked
        };
    }
}
