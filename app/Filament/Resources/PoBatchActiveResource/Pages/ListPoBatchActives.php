<?php

namespace App\Filament\Resources\PoBatchActiveResource\Pages;

use App\Filament\Resources\PoBatchActiveResource;
use Filament\Resources\Pages\ListRecords;

class ListPoBatchActives extends ListRecords
{
    protected static string $resource = PoBatchActiveResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
