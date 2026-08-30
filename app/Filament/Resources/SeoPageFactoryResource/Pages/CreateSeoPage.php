<?php

namespace App\Filament\Resources\SeoPageFactoryResource\Pages;

use App\Filament\Resources\SeoPageFactoryResource;
use App\Services\SeoQualityScorer;
use Filament\Resources\Pages\CreateRecord;

class CreateSeoPage extends CreateRecord
{
    protected static string $resource = SeoPageFactoryResource::class;

    protected function afterCreate(): void
    {
        // Hitung quality score otomatis setelah create
        $scorer = new SeoQualityScorer;
        $scorer->scoreAndSave($this->record);
    }
}
