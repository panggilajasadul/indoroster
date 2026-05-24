<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Helpers\SimulationHelper;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ProductSimulation extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $navigationLabel = 'Simulasi Terjual & Produk';
    protected static ?string $title = 'Simulasi Penjualan & Produk Baru';
    protected static ?int $navigationSort = 11;

    protected static string $view = 'filament.pages.product-simulation';

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->latest())
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('primary_image')
                    ->label('Foto')
                    ->square(),
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->getStateUsing(fn (Product $record): string => $record->formatted_price_range)
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('total_sold')
                    ->label('Total Terjual')
                    ->numeric()
                    ->badge()
                    ->color(fn (int $state): string => $state < 5000 ? 'warning' : 'success')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Diunggah Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('low_sales')
                    ->label('Terjual < 5.000')
                    ->query(fn ($query) => $query->where('total_sold', '<', 5000)),
                \Filament\Tables\Filters\Filter::make('created_at')
                    ->label('Produk Baru (30 Hari Terakhir)')
                    ->query(fn ($query) => $query->where('created_at', '>=', now()->subDays(30))),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('suntik_terjual')
                    ->label('Suntik Terjual')
                    ->icon('heroicon-m-plus-circle')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\Radio::make('mode')
                            ->label('Metode Suntik')
                            ->options([
                                'set' => 'Setel total baru',
                                'add' => 'Tambahkan ke total lama',
                            ])
                            ->default('set')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Terjual')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(100),
                    ])
                    ->action(function (Product $record, array $data): void {
                        $amount = (int)$data['amount'];
                        if ($data['mode'] === 'set') {
                            $record->total_sold = $amount;
                        } else {
                            $record->total_sold = ($record->total_sold ?? 0) + $amount;
                        }
                        $record->save();

                        Notification::make()
                            ->title('Suntik Terjual Berhasil')
                            ->body("Produk {$record->name} sekarang memiliki {$record->total_sold} terjual.")
                            ->success()
                            ->send();
                    }),
                \Filament\Tables\Actions\Action::make('simulasi_ulasan')
                    ->label('Ulasan Baru')
                    ->icon('heroicon-m-sparkles')
                    ->color('warning')
                    ->form([
                        \Filament\Forms\Components\Select::make('rating')
                            ->label('Rating Bintang')
                            ->options([
                                0 => 'Acak (Random)',
                                1 => '1 Bintang',
                                2 => '2 Bintang',
                                3 => '3 Bintang',
                                4 => '4 Bintang',
                                5 => '5 Bintang',
                            ])
                            ->default(0)
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah Ulasan')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->required(),
                    ])
                    ->action(function (Product $record, array $data): void {
                        $rating = $data['rating'] == 0 ? null : $data['rating'];
                        $quantity = (int)$data['quantity'];
                        $created = SimulationHelper::generateProductReviewsForProduct($record->id, $rating, $quantity);

                        Notification::make()
                            ->title('Ulasan Berhasil Ditambahkan')
                            ->body("Berhasil membuat {$created} ulasan simulasi untuk produk {$record->name}.")
                            ->success()
                            ->send();
                    })
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkAction::make('suntik_terjual_massal')
                    ->label('Suntik Terjual Massal')
                    ->icon('heroicon-m-plus-circle')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\Radio::make('mode')
                            ->label('Metode Suntik')
                            ->options([
                                'set' => 'Setel total baru',
                                'add' => 'Tambahkan ke total lama',
                            ])
                            ->default('add')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Terjual')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(500),
                    ])
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                        $amount = (int)$data['amount'];
                        $mode = $data['mode'];

                        foreach ($records as $record) {
                            if ($mode === 'set') {
                                $record->total_sold = $amount;
                            } else {
                                $record->total_sold = ($record->total_sold ?? 0) + $amount;
                            }
                            $record->save();
                        }

                        Notification::make()
                            ->title('Suntik Terjual Massal Berhasil')
                            ->body("Berhasil memperbarui total terjual untuk " . $records->count() . " produk.")
                            ->success()
                            ->send();
                    })
            ]);
    }
}
