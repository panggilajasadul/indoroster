<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Invoice';

    protected static ?string $modelLabel = 'Invoice';

    protected static ?string $pluralModelLabel = 'Invoice';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Invoice')
                ->schema([
                    Forms\Components\Select::make('order_id')
                        ->label('Pesanan')
                        ->relationship('order', 'order_number')
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('invoice_number')
                        ->label('No. Invoice')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->default(fn () => Invoice::generateInvoiceNumber()),
                    Forms\Components\DatePicker::make('invoice_date')
                        ->label('Tanggal Invoice')
                        ->required()
                        ->default(now()),
                    Forms\Components\DatePicker::make('due_date')
                        ->label('Jatuh Tempo'),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'sent' => 'Terkirim',
                            'paid' => 'Lunas',
                            'overdue' => 'Jatuh Tempo',
                            'cancelled' => 'Dibatalkan',
                        ])
                        ->default('draft')
                        ->required(),
                ])->columns(2),
            Forms\Components\Section::make('Rincian Biaya')
                ->schema([
                    Forms\Components\TextInput::make('subtotal')
                        ->label('Subtotal')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                    Forms\Components\TextInput::make('shipping_cost')
                        ->label('Ongkir')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0),
                    Forms\Components\TextInput::make('discount_amount')
                        ->label('Diskon')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0),
                    Forms\Components\TextInput::make('tax_amount')
                        ->label('Pajak/PPN')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0),
                    Forms\Components\TextInput::make('grand_total')
                        ->label('Grand Total')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                ])->columns(3),
            Forms\Components\Section::make('Catatan')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan Invoice')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('No. Pesanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'sent' => 'info',
                        'draft' => 'gray',
                        'overdue' => 'danger',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('print')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Invoice $record) => route('print.invoice', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
