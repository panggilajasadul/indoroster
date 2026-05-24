<?php

namespace App\Filament\Resources\ShippingLabelResource\Pages;

use App\Filament\Resources\ShippingLabelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippingLabels extends ListRecords
{
    protected static string $resource = ShippingLabelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
