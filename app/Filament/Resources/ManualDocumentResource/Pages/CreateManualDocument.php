<?php

namespace App\Filament\Resources\ManualDocumentResource\Pages;

use App\Filament\Resources\ManualDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateManualDocument extends CreateRecord
{
    protected static string $resource = ManualDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print_instant')
                ->label('Cetak Langsung (Tanpa Save)')
                ->icon('heroicon-o-printer')
                ->color('warning')
                ->action(function (array $data) {
                    $mutatedData = $this->mutateFormDataBeforeCreate($data);

                    $mutatedData['items'] = $data['items'] ?? [];
                    $mutatedData['subtotal'] = $data['subtotal'] ?? 0;
                    $mutatedData['discount'] = $data['discount'] ?? 0;
                    $mutatedData['has_tax'] = $data['has_tax'] ?? false;
                    $mutatedData['tax_amount'] = $data['tax_amount'] ?? 0;
                    $mutatedData['grand_total'] = $data['grand_total'] ?? 0;
                    $mutatedData['document_number'] = 'DRAFT';
                    $mutatedData['type'] = $data['type'] ?? 'invoice';
                    $mutatedData['client_name'] = $data['client_name'] ?? '';
                    $mutatedData['client_address'] = $data['client_address'] ?? '';
                    $mutatedData['client_phone'] = $data['client_phone'] ?? '';
                    $mutatedData['client_email'] = $data['client_email'] ?? '';
                    $mutatedData['document_date'] = $data['document_date'] ?? now();
                    $mutatedData['status'] = 'draft';

                    $sessionKey = 'unsaved_doc_preview_'.auth()->id();
                    session()->put($sessionKey, $mutatedData);

                    $url = route('print.manual-document', ['document' => 0, 'preview_session' => 1]).'?t='.time();

                    $this->dispatch('open-new-tab', url: $url);
                }),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $overrides = [
            'orientation' => $data['orientation'] ?? 'portrait',
            'paper_size' => $data['paper_size'] ?? 'a4',
            'margins' => $data['margins'] ?? ['top' => 15, 'bottom' => 15, 'left' => 15, 'right' => 15],
            'logo_x' => isset($data['logo_x']) ? (int) $data['logo_x'] : null,
            'logo_y' => isset($data['logo_y']) ? (int) $data['logo_y'] : null,
            'logo_width' => isset($data['logo_width']) ? (int) $data['logo_width'] : null,
            'logo_height' => isset($data['logo_height']) ? (int) $data['logo_height'] : null,
            'signature_x' => isset($data['signature_x']) ? (int) $data['signature_x'] : null,
            'signature_y' => isset($data['signature_y']) ? (int) $data['signature_y'] : null,
            'signature_width' => isset($data['signature_width']) ? (int) $data['signature_width'] : null,
            'signature_height' => isset($data['signature_height']) ? (int) $data['signature_height'] : null,
            'stamp_x' => isset($data['stamp_x']) ? (int) $data['stamp_x'] : null,
            'stamp_y' => isset($data['stamp_y']) ? (int) $data['stamp_y'] : null,
            'stamp_width' => isset($data['stamp_width']) ? (int) $data['stamp_width'] : null,
            'stamp_height' => isset($data['stamp_height']) ? (int) $data['stamp_height'] : null,
            'stamp_opacity' => isset($data['stamp_opacity']) ? (float) $data['stamp_opacity'] : 0.8,
            'stamp_rotation' => isset($data['stamp_rotation']) ? (int) $data['stamp_rotation'] : 0,
            'elements' => $data['elements'] ?? null,
        ];

        $data['extra_data'] = array_merge($data['extra_data'] ?? [], [
            'payment_instructions' => $data['custom_payment_instructions'] ?? null,
            'payment_instructions_title' => $data['custom_payment_instructions_title'] ?? null,
            'delivery_notes' => $data['custom_delivery_notes'] ?? null,
            'delivery_notes_title' => $data['custom_delivery_notes_title'] ?? null,
            'receipt_notes' => $data['custom_receipt_notes'] ?? null,
            'receipt_notes_title' => $data['custom_receipt_notes_title'] ?? null,
            'terms_and_conditions' => $data['custom_terms_and_conditions'] ?? null,
            'terms_title' => $data['custom_terms_title'] ?? null,
            'order_notes' => $data['custom_order_notes'] ?? null,
            'order_notes_title' => $data['custom_order_notes_title'] ?? null,
            'notes' => $data['notes'] ?? null,
            'layout_overrides' => $overrides,
            'watermark' => $data['custom_watermark'] ?? 'none',
            'custom_sections' => $data['custom_sections'] ?? null,
        ]);

        // Remove virtual fields
        unset(
            $data['custom_payment_instructions'],
            $data['custom_payment_instructions_title'],
            $data['custom_delivery_notes'],
            $data['custom_delivery_notes_title'],
            $data['custom_receipt_notes'],
            $data['custom_receipt_notes_title'],
            $data['custom_terms_and_conditions'],
            $data['custom_terms_title'],
            $data['custom_order_notes'],
            $data['custom_order_notes_title'],
            $data['notes'],
            $data['custom_watermark'],
            $data['custom_sections'],
            $data['orientation'],
            $data['paper_size'],
            $data['margins'],
            $data['logo_x'],
            $data['logo_y'],
            $data['logo_width'],
            $data['logo_height'],
            $data['signature_x'],
            $data['signature_y'],
            $data['signature_width'],
            $data['signature_height'],
            $data['stamp_x'],
            $data['stamp_y'],
            $data['stamp_width'],
            $data['stamp_height'],
            $data['stamp_opacity'],
            $data['stamp_rotation'],
            $data['elements'],
            $data['logo_path'],
            $data['stamp_path'],
            $data['template_signature_path'],
            $data['company_name'],
            $data['company_address'],
            $data['company_phone'],
            $data['company_email'],
            $data['signer_name'],
            $data['signer_position']
        );

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
