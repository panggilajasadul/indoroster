<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class VisitorCounterSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Statistik Pengunjung';

    protected static ?string $title = 'Pengaturan Statistik Pengunjung';

    protected static ?int $navigationSort = 90;

    protected static string $view = 'filament.pages.visitor-counter-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'visitor_base_today_hits' => (int) SiteSetting::getValue('visitor_base_today_hits', 32),
            'visitor_base_today_visitors' => (int) SiteSetting::getValue('visitor_base_today_visitors', 23),
            'visitor_base_total_hits' => (int) SiteSetting::getValue('visitor_base_total_hits', 11282),
            'visitor_base_total_visitors' => (int) SiteSetting::getValue('visitor_base_total_visitors', 8172),
            'visitor_base_online' => (int) SiteSetting::getValue('visitor_base_online', 1),
            'visitor_country_my' => (int) SiteSetting::getValue('visitor_country_my', 245),
            'visitor_country_sg' => (int) SiteSetting::getValue('visitor_country_sg', 182),
            'visitor_country_au' => (int) SiteSetting::getValue('visitor_country_au', 128),
            'visitor_country_us' => (int) SiteSetting::getValue('visitor_country_us', 94),
            'visitor_country_jp' => (int) SiteSetting::getValue('visitor_country_jp', 43),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('📊 Angka Dasar Statistik (Base Count)')
                    ->description('Angka ini menjadi baseline yang akan terus bertambah secara otomatis setiap kali ada pengunjung baru membuka website.')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('visitor_base_today_hits')
                                ->label('Kunjungan Hari Ini (Base Hits)')
                                ->numeric()
                                ->required()
                                ->helperText('Tayangan halaman dasar hari ini.'),
                            Forms\Components\TextInput::make('visitor_base_today_visitors')
                                ->label('Pengunjung Hari Ini (Base Unique Visitors)')
                                ->numeric()
                                ->required()
                                ->helperText('Pengunjung unik dasar hari ini.'),
                        ]),
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('visitor_base_total_hits')
                                ->label('Total Kunjungan (All-Time Hits)')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('visitor_base_total_visitors')
                                ->label('Total Pengunjung (All-Time Visitors)')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('visitor_base_online')
                                ->label('Pengunjung Online Minimal')
                                ->numeric()
                                ->default(1)
                                ->required(),
                        ]),
                    ]),

                Forms\Components\Section::make('🌍 Pengunjung Asal Negara Internasional (Base Offset)')
                    ->description('Tentukan baseline jumlah pengunjung dari negara-negara tujuan ekspor & regional.')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('visitor_country_my')
                                ->label('🇲🇾 Malaysia')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('visitor_country_sg')
                                ->label('🇸🇬 Singapura')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('visitor_country_au')
                                ->label('🇦🇺 Australia')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('visitor_country_us')
                                ->label('🇺🇸 Amerika Serikat')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('visitor_country_jp')
                                ->label('🇯🇵 Jepang')
                                ->numeric()
                                ->required(),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($state as $key => $val) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => (string) $val, 'group' => 'visitor_counter']
            );
        }

        Cache::forget('site_visitor_stats');

        Notification::make()
            ->title('Statistik Pengunjung Berhasil Disimpan')
            ->success()
            ->send();
    }
}
