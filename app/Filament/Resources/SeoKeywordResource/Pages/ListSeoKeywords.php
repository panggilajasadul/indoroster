<?php

namespace App\Filament\Resources\SeoKeywordResource\Pages;

use App\Filament\Resources\SeoKeywordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeoKeywords extends ListRecords
{
    protected static string $resource = SeoKeywordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
