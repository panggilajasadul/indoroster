<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingRateResource\Pages;
use App\Models\ShippingRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class ShippingRateResource extends Resource
{
    protected static ?string $model = ShippingRate::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Tarif Pengiriman';

    protected static ?string $pluralModelLabel = 'Tarif Pengiriman';

    protected static ?string $modelLabel = 'Tarif Pengiriman';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Wilayah & Tarif')
                    ->schema([
                        Forms\Components\Select::make('province_code')
                            ->label('Provinsi')
                            ->options(Province::pluck('name', 'code'))
                            ->searchable()
                            ->dehydrated(false)
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('city_code', null))
                            ->live()
                            ->formatStateUsing(function ($record) {
                                if ($record && $record->city) {
                                    return $record->city->province_code;
                                }

                                return null;
                            }),
                        Forms\Components\Select::make('city_code')
                            ->label('Kota/Kabupaten')
                            ->required()
                            ->searchable()
                            ->options(function (Forms\Get $get) {
                                $provinceCode = $get('province_code');
                                if (! $provinceCode) {
                                    return [];
                                }

                                return City::where('province_code', $provinceCode)->pluck('name', 'code');
                            })
                            ->disabled(fn (Forms\Get $get) => ! $get('province_code')),
                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Ongkos Kirim')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(180000.00),
                        Forms\Components\TextInput::make('min_order_qty')
                            ->label('Minimal Order (Qty)')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->required()
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('city.province.name')
                    ->label('Provinsi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->label('Kota/Kabupaten')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_cost')
                    ->label('Ongkos Kirim')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_order_qty')
                    ->label('Min. Order (Qty)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('city.province.name')
            ->groups([
                Tables\Grouping\Group::make('city.province.name')
                    ->label('Provinsi')
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('province')
                    ->label('Filter Provinsi')
                    ->options(Province::pluck('name', 'code'))
                    ->query(function (Builder $query, array $data) {
                        if (! $data['value']) {
                            return $query;
                        }

                        return $query->whereHas('city', function (Builder $query) use ($data) {
                            $query->where('province_code', $data['value']);
                        });
                    }),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('bulk_update_province')
                    ->label('Atur per Provinsi')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('province_code')
                            ->label('Pilih Provinsi')
                            ->options(Province::pluck('name', 'code'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Ongkos Kirim Baru')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\TextInput::make('min_order_qty')
                            ->label('Minimal Order (Qty)')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ])
                    ->action(function (array $data) {
                        $cityCodes = City::where('province_code', $data['province_code'])->pluck('code');

                        $count = ShippingRate::whereIn('city_code', $cityCodes)->update([
                            'shipping_cost' => $data['shipping_cost'],
                            'min_order_qty' => $data['min_order_qty'],
                        ]);

                        Notification::make()
                            ->title('Update Berhasil')
                            ->body("Berhasil memperbarui {$count} kota di provinsi tersebut.")
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Atur Ongkir & Min Order Massal')
                    ->modalDescription('Semua kota di provinsi yang dipilih akan diperbarui datanya secara otomatis.')
                    ->modalSubmitActionLabel('Update Semua Kota'),

                Tables\Actions\Action::make('bulk_update_cities')
                    ->label('Atur per Kota/Daerah')
                    ->icon('heroicon-o-building-office-2')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('city_codes')
                            ->label('Pilih Kota/Kabupaten')
                            ->multiple()
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => City::where('name', 'like', "%{$search}%")
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn ($city) => [$city->code => $city->name.' ('.$city->province?->name.')'])
                                ->toArray()
                            )
                            ->getOptionLabelsUsing(fn (array $values): array => City::whereIn('code', $values)
                                ->get()
                                ->mapWithKeys(fn ($city) => [$city->code => $city->name.' ('.$city->province?->name.')'])
                                ->toArray()
                            )
                            ->required(),
                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Ongkos Kirim Baru')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\TextInput::make('min_order_qty')
                            ->label('Minimal Order (Qty)')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ])
                    ->action(function (array $data) {
                        $count = 0;
                        foreach ($data['city_codes'] as $cityCode) {
                            ShippingRate::updateOrCreate(
                                ['city_code' => $cityCode],
                                [
                                    'shipping_cost' => $data['shipping_cost'],
                                    'min_order_qty' => $data['min_order_qty'],
                                ]
                            );
                            $count++;
                        }

                        Notification::make()
                            ->title('Update Berhasil')
                            ->body("Berhasil memperbarui/membuat tarif untuk {$count} kota/daerah.")
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Atur Ongkir & Min Order per Kota/Daerah')
                    ->modalDescription('Pilih satu atau beberapa kota/daerah yang ingin diperbarui tarif pengirimannya secara massal.')
                    ->modalSubmitActionLabel('Update Kota Terpilih'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_update_rates')
                        ->label('Update Ongkir & Min Order')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning')
                        ->form([
                            Forms\Components\TextInput::make('shipping_cost')
                                ->label('Ongkos Kirim Baru')
                                ->numeric()
                                ->prefix('Rp')
                                ->required(),
                            Forms\Components\TextInput::make('min_order_qty')
                                ->label('Minimal Order (Qty)')
                                ->numeric()
                                ->required()
                                ->default(0),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function (ShippingRate $record) use ($data) {
                                $record->update([
                                    'shipping_cost' => $data['shipping_cost'],
                                    'min_order_qty' => $data['min_order_qty'],
                                ]);
                            });

                            Notification::make()
                                ->title('Update Massal Berhasil')
                                ->body('Berhasil memperbarui '.$records->count().' daerah/kota yang dipilih.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingRates::route('/'),
            'create' => Pages\CreateShippingRate::route('/create'),
            'edit' => Pages\EditShippingRate::route('/{record}/edit'),
        ];
    }
}
