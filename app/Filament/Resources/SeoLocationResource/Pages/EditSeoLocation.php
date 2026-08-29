<?php

namespace App\Filament\Resources\SeoLocationResource\Pages;

use App\Filament\Resources\SeoLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeoLocation extends EditRecord
{
    protected static string $resource = SeoLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->record->fill($data);
        $data['seo_score'] = $this->record->calculateSeoScore();

        return $data;
    }
}
