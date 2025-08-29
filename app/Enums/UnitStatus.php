<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum UnitStatus: string implements HasLabel, HasColor, HasIcon
{
    case Vacant = 'vacant';
    case Occupied = 'occupied';
    case Reserved = 'reserved';
    case UnderMaintenance = 'under_maintenance';
    case Sold = 'sold';
    case Leased = 'leased';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Vacant => 'Vacant',
            self::Occupied => 'Occupied',
            self::Reserved => 'Reserved',
            self::UnderMaintenance => 'Under Maintenance',
            self::Sold => 'Sold',
            self::Leased => 'Leased',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Vacant => 'warning',
            self::Occupied, self::Leased => 'success',
            self::Reserved => 'info',
            self::UnderMaintenance => 'danger',
            self::Sold => 'primary',
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Vacant => Heroicon::OutlinedExclamationTriangle,
            self::Occupied, self::Leased => Heroicon::OutlinedCheckCircle,
            self::Reserved => Heroicon::OutlinedShieldExclamation,
            self::UnderMaintenance => Heroicon::OutlinedWrenchScrewdriver,
            self::Sold => Heroicon::OutlinedCurrencyDollar,
        };
    }
}
