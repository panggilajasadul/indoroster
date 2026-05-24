<?php

namespace App\Filament\Resources\ShippingLabelResource\Pages;

use App\Filament\Resources\ShippingLabelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingLabel extends EditRecord
{
    protected static string $resource = ShippingLabelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
