<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?string $pluralModelLabel = 'Pembayaran';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Pembayaran')
                ->schema([
                    Forms\Components\Select::make('order_id')
                        ->label('No. Pesanan')
                        ->relationship('order', 'order_number')
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('transaction_id')
                        ->label('Transaction ID (Midtrans)'),
                    Forms\Components\TextInput::make('payment_type')
                        ->label('Metode Bayar'),
                    Forms\Components\TextInput::make('bank')
                        ->label('Bank'),
                    Forms\Components\TextInput::make('va_number')
                        ->label('No. Virtual Account'),
                    Forms\Components\TextInput::make('gross_amount')
                        ->label('Jumlah Bayar')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => '⏳ Pending',
                            'settlement' => '✅ Settlement (Sukses)',
                            'capture' => '✅ Capture',
                            'deny' => '❌ Ditolak',
                            'cancel' => '❌ Dibatalkan',
                            'expire' => '⚪ Kedaluwarsa',
                            'refund' => '🔄 Refund',
                        ])
                        ->required(),
                    Forms\Components\DateTimePicker::make('paid_at')
                        ->label('Waktu Bayar'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn ($state, Payment $record) => $record->payment_type_label),
                Tables\Columns\TextColumn::make('bank')
                    ->label('Bank')
                    ->formatStateUsing(fn ($state) => strtoupper($state))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('va_number')
                    ->label('VA/Bill Key')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('gross_amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'settlement', 'capture' => 'success',
                        'pending' => 'warning',
                        'deny', 'cancel' => 'danger',
                        'expire' => 'gray',
                        'refund' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Waktu Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'settlement' => 'Settlement',
                        'deny' => 'Ditolak',
                        'expire' => 'Kedaluwarsa',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
