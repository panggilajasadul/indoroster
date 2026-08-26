<?php

namespace App\Filament\Resources\ManualDocumentResource\Pages;

use App\Filament\Resources\ManualDocumentResource;
use App\Models\SiteSetting;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManualDocument extends EditRecord
{
    protected static string $resource = ManualDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Cetak Dokumen')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn () => route('print.manual-document', $this->record).'?t='.time())
                ->openUrlInNewTab(),
            Actions\Action::make('print_instant')
                ->label('Cetak Langsung (Tanpa Save)')
                ->icon('heroicon-o-printer')
                ->color('warning')
                ->action(function (array $data) {
                    $mutatedData = $this->mutateFormDataBeforeSave($data);

                    $mutatedData['items'] = $data['items'] ?? [];
                    $mutatedData['subtotal'] = $data['subtotal'] ?? 0;
                    $mutatedData['discount'] = $data['discount'] ?? 0;
                    $mutatedData['has_tax'] = $data['has_tax'] ?? false;
                    $mutatedData['tax_amount'] = $data['tax_amount'] ?? 0;
                    $mutatedData['grand_total'] = $data['grand_total'] ?? 0;
                    $mutatedData['document_number'] = $data['document_number'] ?? 'DRAFT';
                    $mutatedData['type'] = $data['type'] ?? 'invoice';
                    $mutatedData['client_name'] = $data['client_name'] ?? '';
                    $mutatedData['client_address'] = $data['client_address'] ?? '';
                    $mutatedData['client_phone'] = $data['client_phone'] ?? '';
                    $mutatedData['client_email'] = $data['client_email'] ?? '';
                    $mutatedData['document_date'] = $data['document_date'] ?? now();
                    $mutatedData['status'] = 'draft';

                    $sessionKey = 'unsaved_doc_preview_'.auth()->id();
                    session()->put($sessionKey, $mutatedData);

                    $url = route('print.manual-document', ['document' => $this->record->id, 'preview_session' => 1]).'?t='.time();

                    $this->dispatch('open-new-tab', url: $url);
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $overrides = $data['extra_data']['layout_overrides'] ?? [];
        $template = $this->record->documentTemplate;

        // Load visual editor and coordinate values
        $data['orientation'] = $overrides['orientation'] ?? $template?->orientation ?? 'portrait';
        $data['paper_size'] = $overrides['paper_size'] ?? $template?->paper_size ?? 'a4';
        $data['margins'] = $overrides['margins'] ?? $template?->margins ?? ['top' => 15, 'bottom' => 15, 'left' => 15, 'right' => 15];

        $data['logo_x'] = $overrides['logo_x'] ?? $template?->logo_x ?? 15;
        $data['logo_y'] = $overrides['logo_y'] ?? $template?->logo_y ?? 15;
        $data['logo_width'] = $overrides['logo_width'] ?? $template?->logo_width ?? 50;
        $data['logo_height'] = $overrides['logo_height'] ?? $template?->logo_height ?? 25;

        $data['signature_x'] = $overrides['signature_x'] ?? $template?->signature_x ?? 140;
        $data['signature_y'] = $overrides['signature_y'] ?? $template?->signature_y ?? 220;
        $data['signature_width'] = $overrides['signature_width'] ?? $template?->signature_width ?? 40;
        $data['signature_height'] = $overrides['signature_height'] ?? $template?->signature_height ?? 20;

        $data['stamp_x'] = $overrides['stamp_x'] ?? $template?->stamp_x ?? 130;
        $data['stamp_y'] = $overrides['stamp_y'] ?? $template?->stamp_y ?? 215;
        $data['stamp_width'] = $overrides['stamp_width'] ?? $template?->stamp_width ?? 35;
        $data['stamp_height'] = $overrides['stamp_height'] ?? $template?->stamp_height ?? 35;
        $data['stamp_opacity'] = $overrides['stamp_opacity'] ?? $template?->stamp_opacity ?? 0.8;
        $data['stamp_rotation'] = $overrides['stamp_rotation'] ?? $template?->stamp_rotation ?? 0;

        $data['elements'] = $overrides['elements'] ?? $template?->elements ?? null;

        // Entangled template properties
        $data['logo_path'] = $template?->logo_path ?? SiteSetting::getValue('doc_logo_path');
        $data['signature_path'] = $this->record->signature_path ?: SiteSetting::getValue('doc_signature_path');
        $data['template_signature_path'] = $template?->signature_path ?? SiteSetting::getValue('doc_signature_path');
        $data['stamp_path'] = $template?->stamp_path ?? SiteSetting::getValue('doc_stamp_path');
        $data['company_name'] = $template?->company_name ?? SiteSetting::getValue('doc_company_name') ?? SiteSetting::getValue('factory_name') ?? 'Indoroster';
        $data['company_address'] = $template?->company_address ?? SiteSetting::getValue('doc_company_address') ?? SiteSetting::getValue('factory_address');
        $data['company_phone'] = $template?->company_phone ?? SiteSetting::getValue('doc_company_phone') ?? SiteSetting::getValue('whatsapp_number');
        $data['company_email'] = $template?->company_email ?? SiteSetting::getValue('doc_company_email') ?? SiteSetting::getValue('contact_email');
        $data['signer_name'] = $template?->signer_name ?? SiteSetting::getValue('doc_signer_name') ?? $this->record->issued_by;
        $data['signer_position'] = $template?->signer_position ?? SiteSetting::getValue('doc_signer_position') ?? 'Authorized Signatory';

        $data['custom_watermark'] = $data['extra_data']['watermark'] ?? 'none';
        $data['custom_sections'] = $data['extra_data']['custom_sections'] ?? [];

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $existingExtraData = $this->record->extra_data ?? [];

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

        $data['extra_data'] = array_merge($existingExtraData, [
            'payment_instructions' => $data['custom_payment_instructions'] ?? $existingExtraData['payment_instructions'] ?? null,
            'payment_instructions_title' => $data['custom_payment_instructions_title'] ?? $existingExtraData['payment_instructions_title'] ?? null,
            'delivery_notes' => $data['custom_delivery_notes'] ?? $existingExtraData['delivery_notes'] ?? null,
            'delivery_notes_title' => $data['custom_delivery_notes_title'] ?? $existingExtraData['delivery_notes_title'] ?? null,
            'receipt_notes' => $data['custom_receipt_notes'] ?? $existingExtraData['receipt_notes'] ?? null,
            'receipt_notes_title' => $data['custom_receipt_notes_title'] ?? $existingExtraData['receipt_notes_title'] ?? null,
            'terms_and_conditions' => $data['custom_terms_and_conditions'] ?? $existingExtraData['terms_and_conditions'] ?? null,
            'terms_title' => $data['custom_terms_title'] ?? $existingExtraData['terms_title'] ?? null,
            'order_notes' => $data['custom_order_notes'] ?? $existingExtraData['order_notes'] ?? null,
            'order_notes_title' => $data['custom_order_notes_title'] ?? $existingExtraData['order_notes_title'] ?? null,
            'notes' => $data['notes'] ?? $existingExtraData['notes'] ?? null,
            'layout_overrides' => $overrides,
            'watermark' => $data['custom_watermark'] ?? $existingExtraData['watermark'] ?? 'none',
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
