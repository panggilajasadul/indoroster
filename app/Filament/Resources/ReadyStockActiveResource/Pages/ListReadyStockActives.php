<?php

namespace App\Filament\Resources\ReadyStockActiveResource\Pages;

use App\Filament\Resources\ReadyStockActiveResource;
use Filament\Resources\Pages\ListRecords;

class ListReadyStockActives extends ListRecords
{
    protected static string $resource = ReadyStockActiveResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
