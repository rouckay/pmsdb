<?php

namespace App\Filament\Resources\BoxResource\Pages;

use App\Filament\Resources\BoxResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBox extends ViewRecord
{
    protected static string $resource = BoxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
