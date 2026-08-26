<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class DocumentSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Dokumen';

    protected static ?string $title = 'Pengaturan Umum Pembuat Dokumen';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.document-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::where('group', 'document_settings')->pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profil & Informasi Perusahaan')
                    ->description('Data profil perusahaan default untuk kop surat jika tidak memilih template.')
                    ->schema([
                        TextInput::make('doc_company_name')
                            ->label('Nama Perusahaan')
                            ->placeholder('Contoh: INDOROSTER INDONESIA')
                            ->required(),
                        Textarea::make('doc_company_address')
                            ->label('Alamat Lengkap Perusahaan')
                            ->placeholder('Alamat pabrik / kantor...')
                            ->rows(3)
                            ->required(),
                        TextInput::make('doc_company_phone')
                            ->label('No. Telepon / WhatsApp')
                            ->placeholder('Contoh: 0813-8970-9847')
                            ->required(),
                        TextInput::make('doc_company_email')
                            ->label('Email Perusahaan')
                            ->email()
                            ->placeholder('Contoh: info@indoroster.com')
                            ->required(),
                    ])->columns(2),

                Section::make('Aset Branding & Penandatangan')
                    ->description('Logo, stempel, dan tanda tangan default yang akan digunakan di berbagai dokumen.')
                    ->schema([
                        TextInput::make('doc_signer_name')
                            ->label('Nama Penanggung Jawab')
                            ->placeholder('Contoh: Abdul Hamid')
                            ->required(),
                        TextInput::make('doc_signer_position')
                            ->label('Jabatan / Posisi')
                            ->placeholder('Contoh: Direktur Utama')
                            ->required(),
                        FileUpload::make('doc_logo_path')
                            ->label('Logo Default Perusahaan')
                            ->disk('public')
                            ->directory('document-assets/logos')
                            ->extraInputAttributes(['accept' => 'image/*'])
                            ->fetchFileInformation(false),
                        FileUpload::make('doc_signature_path')
                            ->label('Tanda Tangan Default')
                            ->disk('public')
                            ->directory('document-assets/signatures')
                            ->extraInputAttributes(['accept' => 'image/*'])
                            ->fetchFileInformation(false),
                        FileUpload::make('doc_stamp_path')
                            ->label('Stempel Default Perusahaan')
                            ->disk('public')
                            ->directory('document-assets/stamps')
                            ->extraInputAttributes(['accept' => 'image/*'])
                            ->fetchFileInformation(false),
                    ])->columns(2),

                Section::make('Tata Letak Default Halaman')
                    ->description('Pengaturan default ukuran kertas dan orientasi cetakan.')
                    ->schema([
                        Select::make('doc_paper_size')
                            ->label('Ukuran Kertas')
                            ->options([
                                'a4' => 'A4 (210 x 297 mm)',
                                'letter' => 'Letter (216 x 279 mm)',
                            ])
                            ->default('a4')
                            ->required(),
                        Select::make('doc_orientation')
                            ->label('Orientasi Halaman')
                            ->options([
                                'portrait' => 'Tegak (Portrait)',
                                'landscape' => 'Mendatar (Landscape)',
                            ])
                            ->default('portrait')
                            ->required(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->color('primary')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = reset($value);
            }

            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'document_settings',
                    'type' => 'text',
                    'description' => 'Document settings property: '.$key,
                ]
            );
        }

        Notification::make()
            ->title('Pengaturan Dokumen Berhasil Disimpan')
            ->success()
            ->send();
    }
}
