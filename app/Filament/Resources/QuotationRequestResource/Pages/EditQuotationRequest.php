<?php

namespace App\Filament\Resources\QuotationRequestResource\Pages;

use App\Filament\Resources\QuotationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuotationRequest extends EditRecord
{
    protected static string $resource = QuotationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
