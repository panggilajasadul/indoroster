<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiteSettings extends ListRecords
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => \Filament\Resources\Components\Tab::make('Semua'),
            'general' => \Filament\Resources\Components\Tab::make('Umum')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'general')),
            'contact' => \Filament\Resources\Components\Tab::make('Kontak')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'contact')),
            'payment' => \Filament\Resources\Components\Tab::make('Pembayaran')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'payment')),
            'shipping' => \Filament\Resources\Components\Tab::make('Pengiriman')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'shipping')),
            'seo' => \Filament\Resources\Components\Tab::make('SEO')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'seo')),
        ];
    }
}
