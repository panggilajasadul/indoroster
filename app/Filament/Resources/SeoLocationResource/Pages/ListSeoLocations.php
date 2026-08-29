<?php

namespace App\Filament\Resources\SeoLocationResource\Pages;

use App\Filament\Resources\SeoLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeoLocations extends ListRecords
{
    protected static string $resource = SeoLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
