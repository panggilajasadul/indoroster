<?php

namespace App\Filament\Resources\SeoLocationResource\Pages;

use App\Filament\Resources\SeoLocationResource;
use App\Models\SeoLocation;
use Filament\Resources\Pages\CreateRecord;

class CreateSeoLocation extends CreateRecord
{
    protected static string $resource = SeoLocationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Hitung skor otomatis
        $tempModel = new SeoLocation($data);
        $data['seo_score'] = $tempModel->calculateSeoScore();

        return $data;
    }
}
