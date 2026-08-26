<?php

namespace App\Filament\Resources\CourierResource\Pages;

use App\Filament\Resources\CourierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourier extends EditRecord
{
    protected static string $resource = CourierResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['email'])) {
            $data['email'] = $data['phone'].'@kurir.indoroster.local';
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
