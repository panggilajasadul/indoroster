<?php

namespace App\Filament\Courier\Resources;

use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RiwayatResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Riwayat Pengiriman';
    protected static ?string $pluralModelLabel = 'Riwayat Pengiriman';
    protected static ?string $modelLabel = 'Pengiriman Selesai';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'riwayat';

    public static function getEloquentQuery(): Builder
    {
        // Hanya tampilkan pesanan yang SUDAH SELESAI diantarkan
        return parent::getEloquentQuery()
            ->where('courier_id', auth()->id())
            ->where('status', 'completed');
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ViewColumn::make('id')
                    ->view('filament.courier.riwayat-card')
            ])
            ->contentGrid([
                'default' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->defaultSort('completed_at', 'desc')
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Courier\Resources\RiwayatResource\Pages\ListRiwayat::route('/'),
        ];
    }
}
