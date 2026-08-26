<?php

namespace App\Filament\Resources\CourierResource\Pages;

use App\Filament\Resources\CourierResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourier extends CreateRecord
{
    protected static string $resource = CourierResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['email'])) {
            $data['email'] = $data['phone'].'@kurir.indoroster.local';
        }

        return $data;
    }
}
