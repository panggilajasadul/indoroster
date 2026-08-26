<?php

namespace App\Filament\Resources\PoSingleActiveResource\Pages;

use App\Filament\Resources\PoSingleActiveResource;
use Filament\Resources\Pages\ListRecords;

class ListPoSingleActives extends ListRecords
{
    protected static string $resource = PoSingleActiveResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
