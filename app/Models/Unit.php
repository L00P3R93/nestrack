<?php

namespace App\Models;

use App\Enums\UnitStatus;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Unit extends Model
{
    /** @use HasFactory<\Database\Factories\UnitFactory> */
    use HasFactory;

    protected $casts = [
        'status' => UnitStatus::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (self $unit) {
            if(empty($unit->unit_code)) {
                $unit->unit_code = 'unit_'.Str::lower(Str::random(6));
            }
        });

        static::created(function (self $unit) {
            Notification::make()
                ->title('New Property Unit')
                ->body("Unit {$unit->unit_no} has been created")
                ->icon(Heroicon::OutlinedBuildingStorefront)
                ->actions([
                    Action::make('View')->url(UnitResource::getUrl('edit', ['record' => $unit])),
                ])->sendToDatabase(User::query()->where('is_admin', true)->get());
        });

        static::updated(function (self $unit) {
            Notification::make()
                ->title('Unit Updated')
                ->body("Unit {$unit->unit_no} has been updated")
                ->icon(Heroicon::OutlinedBuildingStorefront)
                ->actions([
                    Action::make('View')->url(UnitResource::getUrl('edit', ['record' => $unit])),
                ])->sendToDatabase(User::query()->where('is_admin', true)->get());
        });
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
