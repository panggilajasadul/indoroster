<?php

namespace App\Filament\Resources\SeoKeywordResource\Pages;

use App\Filament\Resources\SeoKeywordResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSeoKeyword extends CreateRecord
{
    protected static string $resource = SeoKeywordResource::class;

    protected function afterCreate(): void
    {
        $this->record->updatePriorityScore();
    }
}
