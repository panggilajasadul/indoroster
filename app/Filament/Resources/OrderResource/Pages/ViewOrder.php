<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print_order')
                ->label('Cetak Surat Jalan')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->modalHeading('Preview Surat Jalan')
                ->modalWidth('5xl')
                ->modalContent(fn ($record) => view('print.order-preview', ['order' => $record]))
                ->modalSubmitAction(false)
                ->extraModalFooterActions([
                    Actions\Action::make('open_order_in_new_tab')
                        ->label('Konfirmasi Cetak & Kirim')
                        ->icon('heroicon-o-truck')
                        ->color('primary')
                        ->url(fn ($record) => route('print.order', ['order' => $record, 'ship' => 1]))
                        ->openUrlInNewTab()
                        ->button()
                        ->visible(fn ($record) => $record->status === 'processing'),
                ]),

            Actions\Action::make('print_invoice')
                ->label('Cetak Invoice')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->visible(fn ($record) => $record->invoice !== null)
                ->modalHeading('Preview Invoice')
                ->modalWidth('5xl')
                ->modalContent(fn ($record) => view('print.invoice-preview', ['invoice' => $record->invoice]))
                ->modalSubmitAction(false)
                ->extraModalFooterActions([
                    Actions\Action::make('open_invoice_in_new_tab')
                        ->label('Buka Full Page / Cetak')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn ($record) => $record->invoice ? route('print.invoice', $record->invoice) : '#')
                        ->openUrlInNewTab()
                        ->button()
                        ->visible(fn ($record) => $record->status === 'processing'),
                ]),

            Actions\Action::make('print_label')
                ->label('Cetak Resi / Label')
                ->icon('heroicon-o-printer')
                ->color('warning')
                ->url(fn ($record) => route('print.shipping-label', $record->shippingLabel))
                ->openUrlInNewTab()
                ->visible(fn ($record) => $record->shippingLabel !== null && $record->status === 'processing'),
        ];
    }
}
