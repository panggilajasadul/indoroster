<?php

namespace App\Filament\Resources\ApplicationPageResource\Pages;

use App\Filament\Resources\ApplicationPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplicationPage extends EditRecord
{
    protected static string $resource = ApplicationPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
