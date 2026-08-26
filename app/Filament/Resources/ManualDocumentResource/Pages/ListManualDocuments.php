<?php

namespace App\Filament\Resources\ManualDocumentResource\Pages;

use App\Filament\Resources\ManualDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManualDocuments extends ListRecords
{
    protected static string $resource = ManualDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
