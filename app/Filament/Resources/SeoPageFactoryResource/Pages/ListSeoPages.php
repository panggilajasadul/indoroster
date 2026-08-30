<?php

namespace App\Filament\Resources\SeoPageFactoryResource\Pages;

use App\Filament\Resources\SeoPageFactoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeoPages extends ListRecords
{
    protected static string $resource = SeoPageFactoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
