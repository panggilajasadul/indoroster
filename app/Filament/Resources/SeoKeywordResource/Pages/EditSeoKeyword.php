<?php

namespace App\Filament\Resources\SeoKeywordResource\Pages;

use App\Filament\Resources\SeoKeywordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeoKeyword extends EditRecord
{
    protected static string $resource = SeoKeywordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->updatePriorityScore();
    }
}
