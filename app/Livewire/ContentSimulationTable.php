<?php

namespace App\Livewire;

use App\Models\Gallery;
use App\Helpers\SimulationHelper;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ContentSimulationTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Gallery::query()->withCount(['likes', 'comments'])
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Konten')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('category')
                    ->label('Tipe Konten')
                    ->formatStateUsing(fn (string $state) => $state === 'video-inspirasi' ? 'Video' : 'Foto')
                    ->badge()
                    ->color(fn (string $state) => $state === 'video-inspirasi' ? 'primary' : 'success')
                    ->sortable(),
                TextColumn::make('views_count')
                    ->label('Tayangan (Views)')
                    ->numeric()
                    ->badge()
                    ->color(fn (int $state): string => $state < 5000 ? 'warning' : 'success')
                    ->sortable(),
                TextColumn::make('likes_count')
                    ->label('Like')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('comments_count')
                    ->label('Komentar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Unggah')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Tipe Konten')
                    ->options([
                        'video' => 'Konten Video',
                        'photo' => 'Konten Foto',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'video') {
                            $query->where('category', 'video-inspirasi');
                        } elseif ($data['value'] === 'photo') {
                            $query->where('category', '!=', 'video-inspirasi');
                        }
                    }),
                Filter::make('low_views')
                    ->label('Tayangan < 5.000')
                    ->query(fn (Builder $query) => $query->where('views_count', '<', 5000)),
            ])
            ->actions([
                // Suntik Tayangan
                Action::make('suntik_tayangan')
                    ->label('Suntik Tayangan')
                    ->icon('heroicon-m-eye')
                    ->color('info')
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
                            ->label('Jumlah Tayangan')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(100),
                    ])
                    ->action(function (Gallery $record, array $data): void {
                        $amount = (int)$data['amount'];
                        if ($data['mode'] === 'set') {
                            $record->views_count = $amount;
                        } else {
                            $record->views_count = ($record->views_count ?? 0) + $amount;
                        }
                        $record->save();

                        // Refresh stats in parent page
                        $this->dispatch('refreshSimulationStats');

                        Notification::make()
                            ->title('Suntik Tayangan Berhasil')
                            ->body("Konten \"{$record->title}\" sekarang memiliki {$record->views_count} tayangan.")
                            ->success()
                            ->send();
                    }),

                // Suntik Like
                Action::make('suntik_like')
                    ->label('Suntik Like')
                    ->icon('heroicon-m-heart')
                    ->color('danger')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Like')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(50),
                    ])
                    ->action(function (Gallery $record, array $data): void {
                        $amount = (int)$data['amount'];
                        $created = SimulationHelper::generateLikesForMedia(Gallery::class, $record->id, $amount);

                        // Refresh stats in parent page
                        $this->dispatch('refreshSimulationStats');

                        Notification::make()
                            ->title('Suntik Like Berhasil')
                            ->body("Berhasil menambahkan {$created} like pada \"{$record->title}\".")
                            ->success()
                            ->send();
                    }),

                // Suntik Komentar
                Action::make('suntik_komentar')
                    ->label('Suntik Komentar')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('warning')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Komentar')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(10),
                    ])
                    ->action(function (Gallery $record, array $data): void {
                        $amount = (int)$data['amount'];
                        if ($record->category === 'video-inspirasi') {
                            $created = SimulationHelper::generateVideoCommentsForMedia(Gallery::class, $record->id, $amount);
                        } else {
                            $created = SimulationHelper::generatePhotoCommentsForMedia(Gallery::class, $record->id, $amount);
                        }

                        // Refresh stats in parent page
                        $this->dispatch('refreshSimulationStats');

                        Notification::make()
                            ->title('Suntik Komentar Berhasil')
                            ->body("Berhasil menambahkan {$created} komentar pada \"{$record->title}\".")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkAction::make('suntik_tayangan_massal')
                    ->label('Suntik Tayangan Massal')
                    ->icon('heroicon-m-eye')
                    ->color('info')
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
                            ->label('Jumlah Tayangan')
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
                                $record->views_count = $amount;
                            } else {
                                $record->views_count = ($record->views_count ?? 0) + $amount;
                            }
                            $record->save();
                        }

                        // Refresh stats in parent page
                        $this->dispatch('refreshSimulationStats');

                        Notification::make()
                            ->title('Suntik Tayangan Massal Berhasil')
                            ->body("Berhasil memperbarui tayangan untuk " . $records->count() . " konten.")
                            ->success()
                            ->send();
                    }),

                BulkAction::make('suntik_like_massal')
                    ->label('Suntik Like Massal')
                    ->icon('heroicon-m-heart')
                    ->color('danger')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Like per Konten')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(100),
                    ])
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                        $amount = (int)$data['amount'];
                        $totalCreated = 0;

                        foreach ($records as $record) {
                            $totalCreated += SimulationHelper::generateLikesForMedia(Gallery::class, $record->id, $amount);
                        }

                        // Refresh stats in parent page
                        $this->dispatch('refreshSimulationStats');

                        Notification::make()
                            ->title('Suntik Like Massal Berhasil')
                            ->body("Berhasil menambahkan total {$totalCreated} like ke " . $records->count() . " konten.")
                            ->success()
                            ->send();
                    }),

                BulkAction::make('suntik_komentar_massal')
                    ->label('Suntik Komentar Massal')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('warning')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Komentar per Konten')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(10),
                    ])
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                        $amount = (int)$data['amount'];
                        $totalCreated = 0;

                        foreach ($records as $record) {
                            if ($record->category === 'video-inspirasi') {
                                $totalCreated += SimulationHelper::generateVideoCommentsForMedia(Gallery::class, $record->id, $amount);
                            } else {
                                $totalCreated += SimulationHelper::generatePhotoCommentsForMedia(Gallery::class, $record->id, $amount);
                            }
                        }

                        // Refresh stats in parent page
                        $this->dispatch('refreshSimulationStats');

                        Notification::make()
                            ->title('Suntik Komentar Massal Berhasil')
                            ->body("Berhasil menambahkan total {$totalCreated} komentar ke " . $records->count() . " konten.")
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function render()
    {
        return view('livewire.content-simulation-table');
    }
}
