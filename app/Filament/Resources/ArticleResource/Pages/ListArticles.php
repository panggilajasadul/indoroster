<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Models\ArticleCategory;
use App\Services\ArticleAiGeneratorService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        $presets = ArticleAiGeneratorService::getPresets();
        $presetOptions = [];
        foreach ($presets as $key => $preset) {
            $presetOptions[$key] = $preset['title'];
        }

        return [
            Actions\Action::make('ai_generator')
                ->label('✨ Buat Artikel dengan AI (8-Skill)')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->modalHeading('Generator Artikel AI IndoRoster (8-Skill Engine)')
                ->modalDescription('Hasilkan artikel blog berstandar manusia dengan narasi lapangan, data teknis, estimasi semen, dan Local SEO Plered Purwakarta.')
                ->modalSubmitActionLabel('Buat & Publikasikan Artikel')
                ->form([
                    Forms\Components\Radio::make('source_type')
                        ->label('Pilihan Metode Pembuatan')
                        ->options([
                            'preset' => 'Pilih dari 10 Ide Konten Master (Rekomendasi 8-Skill Lengkap)',
                            'custom' => 'Tulis Judul / Ide Topik Kustom Sendiri',
                        ])
                        ->default('preset')
                        ->live(),

                    Forms\Components\Select::make('preset_file')
                        ->label('Pilih Topik Master Artikel')
                        ->options($presetOptions)
                        ->default(array_key_first($presetOptions))
                        ->visible(fn (Forms\Get $get) => $get('source_type') === 'preset')
                        ->required(fn (Forms\Get $get) => $get('source_type') === 'preset')
                        ->helperText('Artikel ini telah diuji dengan 8 skill: perhitungan luas m², adukan semen, skenario lapangan, dan Local SEO.'),

                    Forms\Components\TextInput::make('custom_topic')
                        ->label('Judul atau Topik Artikel Kustom')
                        ->placeholder('Contoh: Tips Memilih Roster Beton Anti-Tampias untuk Fasad Barat')
                        ->visible(fn (Forms\Get $get) => $get('source_type') === 'custom')
                        ->required(fn (Forms\Get $get) => $get('source_type') === 'custom'),

                    Forms\Components\TextInput::make('focus_keyword')
                        ->label('Focus Keyword SEO')
                        ->placeholder('Contoh: roster beton anti tampias')
                        ->visible(fn (Forms\Get $get) => $get('source_type') === 'custom'),

                    Forms\Components\Select::make('category_id')
                        ->label('Kategori Artikel')
                        ->options(ArticleCategory::where('is_active', true)->pluck('name', 'id'))
                        ->visible(fn (Forms\Get $get) => $get('source_type') === 'custom')
                        ->searchable(),
                ])
                ->action(function (array $data) {
                    try {
                        if ($data['source_type'] === 'preset') {
                            $article = ArticleAiGeneratorService::generateFromPreset($data['preset_file']);
                        } else {
                            $article = ArticleAiGeneratorService::generateCustom(
                                $data['custom_topic'],
                                $data['focus_keyword'] ?? null,
                                $data['category_id'] ?? null
                            );
                        }

                        Notification::make()
                            ->title('Artikel AI Berhasil Dibuat!')
                            ->body("Artikel '{$article->title}' telah dibuat dan langsung tersimpan ke database.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Membuat Artikel')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\CreateAction::make(),
        ];
    }
}
