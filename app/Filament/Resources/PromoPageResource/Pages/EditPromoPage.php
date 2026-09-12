<?php

namespace App\Filament\Resources\PromoPageResource\Pages;

use App\Filament\Resources\PromoPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPromoPage extends EditRecord
{
    protected static string $resource = PromoPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
