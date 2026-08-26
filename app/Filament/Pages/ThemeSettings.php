<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ThemeSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Tampilan & Tema';

    protected static ?string $title = 'Pengaturan Tampilan & Tema (Dark/Light)';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.theme-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::where('group', 'theme')->pluck('value', 'key')->toArray();

        $this->form->fill([
            'theme_default_mode' => $settings['theme_default_mode'] ?? 'light',
            'theme_allow_user_toggle' => filter_var($settings['theme_allow_user_toggle'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'theme_accent_color' => $settings['theme_accent_color'] ?? '#f75c20',
            'theme_navbar_style' => $settings['theme_navbar_style'] ?? 'glassmorphism',
            'theme_border_radius' => $settings['theme_border_radius'] ?? 'rounded-2xl',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Mode Tampilan (Dark & Light Mode)')
                    ->description('Tentukan mode warna bawaan website IndoRoster dan izin bagi pengunjung untuk mengganti tema.')
                    ->schema([
                        Radio::make('theme_default_mode')
                            ->label('Mode Default Website')
                            ->options([
                                'light' => '☀️ Light Mode (Terang Bersih & Elegan)',
                                'dark' => '🌙 Dark Mode (Obsidian Slate & Bronze Luxury)',
                                'system' => '💻 Auto / Mengikuti Pengaturan HP/Laptop Pengunjung',
                            ])
                            ->descriptions([
                                'light' => 'Tampilan bernuansa putih bersih, aksen abu-abu beton, dan kontras tajam.',
                                'dark' => 'Tampilan bernuansa batu obsidian hitam-slate mewah dengan aksen glowing warm terakota.',
                                'system' => 'Menyesuaikan tema terang/gelap sesuai OS browser perangkat pengunjung.',
                            ])
                            ->default('light')
                            ->required(),

                        Toggle::make('theme_allow_user_toggle')
                            ->label('Tampilkan Tombol Switcher (Matahari / Bulan) untuk Pengunjung')
                            ->helperText('Jika diaktifkan, pengunjung website dapat bebas mengganti antara Dark Mode & Light Mode melalui icon switcher di navbar & mobile drawer.')
                            ->default(true),
                    ])->columns(1),

                Section::make('Palet Warna Aksen Signature')
                    ->description('Warna primer aksen untuk tombol CTA, badge, border fokus, dan highlight arsitektural.')
                    ->schema([
                        ColorPicker::make('theme_accent_color')
                            ->label('Warna Aksen Kustom (Hex)')
                            ->default('#f75c20')
                            ->helperText('Default: #f75c20 (Terracotta Red/Orange khas pabrik bata & roster IndoRoster).'),

                        Select::make('theme_navbar_style')
                            ->label('Gaya Header & Navbar')
                            ->options([
                                'glassmorphism' => '✨ Glassmorphic Blur (Transparan Mewah)',
                                'solid' => '🏢 Solid Crisp (Tegas Klasik)',
                            ])
                            ->default('glassmorphism')
                            ->required(),

                        Select::make('theme_border_radius')
                            ->label('Kelengkungan Sudut Komponen (Border Radius)')
                            ->options([
                                'rounded-2xl' => 'Modern Luxury (Meliuk Lembut 16px)',
                                'rounded-xl' => 'Standard Balanced (12px)',
                                'rounded-lg' => 'Architectural Sharp (Minimalis Tegas 8px)',
                            ])
                            ->default('rounded-2xl')
                            ->required(),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan Tema')
                ->color('primary')
                ->icon('heroicon-o-check')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value, 'group' => 'theme']
            );
        }

        Notification::make()
            ->title('Pengaturan Tema Berhasil Disimpan')
            ->body('Perubahan tema dan mode warna langsung aktif di storefront website.')
            ->success()
            ->send();
    }
}
