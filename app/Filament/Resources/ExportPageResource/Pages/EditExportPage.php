<?php

namespace App\Filament\Resources\ExportPageResource\Pages;

use App\Filament\Resources\ExportPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExportPage extends EditRecord
{
    protected static string $resource = ExportPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_page')
                ->label('🔗 Lihat Halaman Publik')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn ($record) => url('/export/'.$record->country_slug))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
