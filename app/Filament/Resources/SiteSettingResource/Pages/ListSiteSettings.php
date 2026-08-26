<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
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
            'all' => Tab::make('Semua'),
            'general' => Tab::make('Umum')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'general')),
            'contact' => Tab::make('Kontak')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'contact')),
            'payment' => Tab::make('Pembayaran')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'payment')),
            'shipping' => Tab::make('Pengiriman')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'shipping')),
            'seo' => Tab::make('SEO')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'seo')),
        ];
    }
}
