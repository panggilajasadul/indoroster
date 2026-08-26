<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class OrderSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Mode Transaksi & Order';

    protected static ?string $title = 'Pengaturan Mode Transaksi & Order';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.order-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::where('group', 'order')->pluck('value', 'key')->toArray();

        $defaults = [
            'order_mode' => 'midtrans',
            'order_wa_number' => '081389709847',
            'order_wa_template_product' => "Halo Admin IndoRoster, saya ingin memesan:\n• Produk: {product_name}\n• Varian: {variant}\n• Harga Satuan: {unit_price}\n• Jumlah: {qty} pcs\n• Estimasi Total: {total_price}\n• Link: {product_url}\n\nMohon info ketersediaan stok & perkiraan ongkos kirim ke lokasi saya. Terima kasih.",
            'order_wa_template_cart' => "Halo Admin IndoRoster, saya ingin memesan daftar produk berikut:\n\n{items_list}\n\n• Total Jumlah: {total_qty} pcs\n• Subtotal: {subtotal}\n\nMohon info ketersediaan stok dan perkiraan ongkos kirim ke lokasi saya. Terima kasih.",
        ];

        $this->form->fill(array_merge($defaults, $settings));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Metode Transaksi Aktif')
                    ->description('Tentukan bagaimana pelanggan melakukan pemesanan di seluruh halaman website IndoRoster.')
                    ->schema([
                        Radio::make('order_mode')
                            ->label('Pilihan Mode Transaksi')
                            ->options([
                                'midtrans' => 'Mode Otomatis (Midtrans Payment Gateway) — Keranjang & Checkout online aktif, pembayaran otomatis (Virtual Account, QRIS, dll), invoice otomatis terbit.',
                                'whatsapp' => 'Mode Manual (Pemesanan WhatsApp) — Tombol pembelian otomatis mengarahkan pelanggan ke WhatsApp Admin untuk pemesanan & negosiasi langsung.',
                            ])
                            ->default('midtrans')
                            ->required(),
                    ]),

                Section::make('Konfigurasi Pemesanan WhatsApp')
                    ->description('Pengaturan kontak dan format pesan otomatis saat Mode WhatsApp aktif.')
                    ->schema([
                        TextInput::make('order_wa_number')
                            ->label('Nomor WhatsApp Admin / CS')
                            ->placeholder('081389709847')
                            ->helperText('Nomor yang akan menerima chat pesanan dari pelanggan.')
                            ->required(),

                        Textarea::make('order_wa_template_product')
                            ->label('Format Pesan WhatsApp (Halaman Produk)')
                            ->rows(6)
                            ->helperText('Tag yang tersedia: {product_name}, {variant}, {unit_price}, {qty}, {total_price}, {product_url}')
                            ->required(),

                        Textarea::make('order_wa_template_cart')
                            ->label('Format Pesan WhatsApp (Keranjang Belanja)')
                            ->rows(6)
                            ->helperText('Tag yang tersedia: {items_list}, {total_qty}, {subtotal}')
                            ->required(),
                    ]),
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
                ['value' => $value, 'group' => 'order']
            );
        }

        Notification::make()
            ->title('Pengaturan Mode Transaksi Berhasil Disimpan')
            ->success()
            ->send();
    }
}
