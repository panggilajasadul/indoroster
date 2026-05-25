<?php

namespace App\Filament\Courier\Resources\RiwayatResource\Pages;

use App\Filament\Courier\Resources\RiwayatResource;
use Filament\Resources\Pages\ListRecords;

class ListRiwayat extends ListRecords
{
    protected static string $resource = RiwayatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
