<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SmtpSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'SMTP Email';

    protected static ?string $title = 'Pengaturan SMTP Email';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.smtp-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::where('group', 'mail')->pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Konfigurasi Server SMTP')
                    ->description('Atur konfigurasi email untuk pengiriman invoice dan notifikasi sistem.')
                    ->schema([
                        TextInput::make('mail_host')
                            ->label('SMTP Host')
                            ->placeholder('smtp.gmail.com')
                            ->required(),
                        TextInput::make('mail_port')
                            ->label('SMTP Port')
                            ->placeholder('587')
                            ->numeric()
                            ->required(),
                        TextInput::make('mail_encryption')
                            ->label('Enkripsi')
                            ->placeholder('tls / ssl')
                            ->required(),
                        TextInput::make('mail_username')
                            ->label('Username / Email SMTP')
                            ->email()
                            ->required(),
                        TextInput::make('mail_password')
                            ->label('Password SMTP / App Password')
                            ->password()
                            ->revealable()
                            ->required(),
                    ])->columns(2),

                Section::make('Identitas Pengirim')
                    ->schema([
                        TextInput::make('mail_from_address')
                            ->label('Email Pengirim (From Address)')
                            ->email()
                            ->required(),
                        TextInput::make('mail_from_name')
                            ->label('Nama Pengirim (From Name)')
                            ->required(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->color('primary')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'mail']
            );
        }

        Notification::make()
            ->title('Pengaturan Berhasil Disimpan')
            ->success()
            ->send();
    }
}
