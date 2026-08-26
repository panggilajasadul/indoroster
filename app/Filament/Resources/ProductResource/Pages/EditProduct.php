<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generate_seo')
                ->label('Generate SEO')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->action(function () {
                    $record = $this->getRecord();
                    $pythonPath = 'python';
                    $scriptPath = base_path('seo-engine/main.py');

                    // Set URL Laravel dinamis agar Python Engine menembak host yang benar
                    putenv('LARAVEL_API_URL='.url('/'));

                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        $cmd = "start /B {$pythonPath} \"{$scriptPath}\" analyze-product {$record->id} --save > \"".storage_path('logs/seo-engine.log').'" 2>&1';
                        pclose(popen($cmd, 'r'));
                    } else {
                        $cmd = "{$pythonPath} \"{$scriptPath}\" analyze-product {$record->id} --save > \"".storage_path('logs/seo-engine.log').'" 2>&1 &';
                        shell_exec($cmd);
                    }

                    Notification::make()
                        ->title('SEO Generation Dipicu')
                        ->body('Proses analisis SEO sedang berjalan di latar belakang. Silakan muat ulang halaman ini dalam beberapa detik.')
                        ->success()
                        ->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! isset($data['price'])) {
            $data['price'] = 0;
        }
        if (! isset($data['stock'])) {
            $data['stock'] = 0;
        }

        return $data;
    }
}
