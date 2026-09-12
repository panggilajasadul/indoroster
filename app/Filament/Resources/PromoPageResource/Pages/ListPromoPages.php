<?php

namespace App\Filament\Resources\PromoPageResource\Pages;

use App\Filament\Resources\PromoPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPromoPages extends ListRecords
{
    protected static string $resource = PromoPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
