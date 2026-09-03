<?php

namespace App\Filament\Resources\ApplicationPageResource\Pages;

use App\Filament\Resources\ApplicationPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplicationPages extends ListRecords
{
    protected static string $resource = ApplicationPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
