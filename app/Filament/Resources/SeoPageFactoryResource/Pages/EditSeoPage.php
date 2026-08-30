<?php

namespace App\Filament\Resources\SeoPageFactoryResource\Pages;

use App\Filament\Resources\SeoPageFactoryResource;
use App\Services\SeoQualityScorer;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeoPage extends EditRecord
{
    protected static string $resource = SeoPageFactoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Recalculate quality score setelah save
        $scorer = new SeoQualityScorer;
        $scorer->scoreAndSave($this->record);
    }
}
