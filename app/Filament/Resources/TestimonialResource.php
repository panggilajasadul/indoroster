<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $navigationLabel = 'Testimoni';
    protected static ?string $modelLabel = 'Testimoni';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('customer_name')->label('Nama Pelanggan')->required(),
                    Forms\Components\TextInput::make('customer_role')->label('Peran')->placeholder('Contractor, Homeowner, Developer'),
                    Forms\Components\TextInput::make('location')->label('Lokasi')->placeholder('Jakarta'),
                    Forms\Components\Select::make('rating')->label('Rating')->options([5=>'⭐⭐⭐⭐⭐',4=>'⭐⭐⭐⭐',3=>'⭐⭐⭐',2=>'⭐⭐',1=>'⭐'])->default(5)->required(),
                    Forms\Components\Textarea::make('content')->label('Isi Testimoni')->required()->rows(4)->columnSpanFull(),
                    Forms\Components\TextInput::make('photo_url')->label('Link Foto Pelanggan')->url()->placeholder('https://...'),
                    Forms\Components\TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_url')->label('Foto')->circular(),
                Tables\Columns\TextColumn::make('customer_name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('customer_role')->label('Peran'),
                Tables\Columns\TextColumn::make('location')->label('Lokasi'),
                Tables\Columns\TextColumn::make('rating')->label('Rating')->formatStateUsing(fn(int $state) => str_repeat('⭐', $state)),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
