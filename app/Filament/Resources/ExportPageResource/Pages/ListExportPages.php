<?php

namespace App\Filament\Resources\ExportPageResource\Pages;

use App\Filament\Resources\ExportPageResource;
use App\Services\ExportCountryService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListExportPages extends ListRecords
{
    protected static string $resource = ExportPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Halaman Ekspor'),
            Actions\Action::make('sync_defaults')
                ->label('⚡ Sinkronkan 110 Negara Bawaan')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Sinkronkan 110 Destinasi Ekspor')
                ->modalDescription('Tindakan ini akan mengimpor seluruh 110 negara default beserta konfigurasi blok Page Builder dinamis ke dalam database sehingga dapat langsung Anda edit.')
                ->action(function () {
                    $count = ExportCountryService::syncAllDefaultsToDatabase();
                    Notification::make()
                        ->title('Sinkronisasi Berhasil!')
                        ->body("Sebanyak {$count} halaman destinasi ekspor telah berhasil disinkronkan ke database.")
                        ->success()
                        ->send();
                }),
        ];
    }
}
