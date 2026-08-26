@php
    use Illuminate\Support\Str;
    $order = isset($record) ? $record : $getRecord();
    $batches = $order->batches()->orderBy('batch_number')->get();
    $totalQty = $order->total_ordered_quantity;
    $shippedQty = $order->batches()->whereIn('status', ['shipped', 'delivered'])->sum('quantity');
    $progressPct = $totalQty > 0 ? round(($shippedQty / $totalQty) * 100) : 0;
    $shippedCount = $batches->whereIn('status', ['shipped', 'delivered'])->count();
    $totalCount = $batches->count();
@endphp

<div style="padding: 0 0 8px 0;">
    {{-- ===== PROGRESS OVERVIEW ===== --}}
    <div style="background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 12px; padding: 20px; margin-bottom: 16px; border: 1px solid #334155;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div>
                <div style="color: #f97316; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;">Progres Pengiriman Proyek</div>
                <div style="color: #ffffff; font-size: 1.2rem; font-weight: 700; margin-top: 2px;">
                    {{ number_format($shippedQty, 0, ',', '.') }} / {{ number_format($totalQty, 0, ',', '.') }} pcs
                </div>
            </div>
            <div style="text-align: right;">
                <div style="color: #f97316; font-size: 2rem; font-weight: 800; line-height: 1;">{{ $progressPct }}%</div>
                <div style="color: #94a3b8; font-size: 0.75rem;">{{ $shippedCount }}/{{ $totalCount }} batch terkirim</div>
            </div>
        </div>
        {{-- Progress Bar --}}
        <div style="background: #334155; border-radius: 999px; height: 10px; overflow: hidden;">
            <div style="background: linear-gradient(90deg, #f97316, #ea580c); height: 100%; border-radius: 999px; width: {{ $progressPct }}%; transition: width 0.5s ease;"></div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 8px;">
            <span style="color: #94a3b8; font-size: 0.72rem;">Sisa: {{ number_format($totalQty - $shippedQty, 0, ',', '.') }} pcs ({{ $totalCount - $shippedCount }} batch)</span>
            <span style="color: #94a3b8; font-size: 0.72rem;">No. Pesanan: {{ $order->order_number }}</span>
        </div>
    </div>

    {{-- ===== KARTU PER BATCH ===== --}}
    <div style="space-y: 12px;">
        @foreach($batches as $batch)
        @php
            $isShipped = in_array($batch->status, ['shipped', 'delivered']);
            $cardBorder = match($batch->status) {
                'shipped'   => '2px solid #ea580c',
                'delivered' => '2px solid #16a34a',
                'ready_to_ship' => '2px solid #2563eb',
                'producing' => '2px solid #d97706',
                default     => '1px solid #334155',
            };
            $cardBg = match($batch->status) {
                'shipped'   => 'linear-gradient(135deg, #1c1008, #0f172a)',
                'delivered' => 'linear-gradient(135deg, #071c0f, #0f172a)',
                'ready_to_ship' => 'linear-gradient(135deg, #08102a, #0f172a)',
                'producing' => 'linear-gradient(135deg, #1c1508, #0f172a)',
                default     => '#1e293b',
            };
            $badgeColor = $batch->status_hex_color;
        @endphp

        <div style="background: {{ $cardBg }}; border: {{ $cardBorder }}; border-radius: 12px; padding: 16px; margin-bottom: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px;">
                <div>
                    <div style="color: #f8fafc; font-size: 1rem; font-weight: 700;">{{ $batch->batch_name }}</div>
                    <div style="color: #94a3b8; font-size: 0.82rem; margin-top: 2px;">
                        <strong style="color: #e2e8f0;">{{ number_format($batch->quantity, 0, ',', '.') }} pcs</strong>
                        @if($batch->production_start_date)
                        &nbsp;·&nbsp; 🔨 Mulai: {{ $batch->production_start_date->format('d M Y') }}
                        @endif
                        &nbsp;·&nbsp;
                        @if($isShipped && $batch->actual_dispatch_date)
                            🚚 Dikirim: {{ $batch->actual_dispatch_date->format('d M Y') }}
                        @else
                            📅 Est. Kirim: {{ $batch->estimated_dispatch_date?->format('d M Y') ?? '-' }}
                        @endif
                    </div>
                </div>
                <div>
                    <span style="background: {{ $badgeColor }}22; color: {{ $badgeColor }}; border: 1px solid {{ $badgeColor }}55; font-size: 0.75rem; font-weight: 600; padding: 3px 10px; border-radius: 999px; white-space: nowrap;">
                        {{ $batch->status_label }}
                    </span>
                </div>
            </div>

            {{-- Info supir jika sudah dikirim --}}
            @if($isShipped && ($batch->courier_name || $batch->tracking_number))
            <div style="background: #0f172a; border-radius: 8px; padding: 10px 12px; margin-top: 10px; font-size: 0.78rem; color: #94a3b8; display: flex; flex-wrap: wrap; gap: 12px;">
                @if($batch->courier_name)
                <span>👤 Supir: <strong style="color: #e2e8f0;">{{ $batch->courier_name }}</strong></span>
                @endif
                @if($batch->tracking_number)
                <span>🚛 Plat: <strong style="color: #f97316; font-family: monospace;">{{ $batch->tracking_number }}</strong></span>
                @endif
                @if($batch->courier_phone)
                <span>📱 HP: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', str_replace('+', '', $batch->courier_phone)) }}" target="_blank" style="color: #22c55e;">{{ $batch->courier_phone }}</a></span>
                @endif
            </div>
            @endif

            @if($batch->notes)
            <div style="margin-top: 8px; color: #64748b; font-size: 0.75rem; font-style: italic;">💬 {{ $batch->notes }}</div>
            @endif

            {{-- Foto Bukti Kirim jika ada --}}
            @if($batch->delivery_photo_path)
            <div style="margin-top: 10px; padding: 4px; background: #0f172a; border-radius: 8px; display: inline-block;">
                <div style="color: #94a3b8; font-size: 0.75rem; font-weight: 600; margin-bottom: 5px; padding-left: 4px;">📸 Bukti Pengiriman (Bongkar Muat):</div>
                <a href="{{ asset('storage/' . $batch->delivery_photo_path) }}" target="_blank" style="display: block;">
                    <img src="{{ asset('storage/' . $batch->delivery_photo_path) }}" style="max-height: 120px; border-radius: 6px; border: 1px solid #475569; display: block; object-fit: cover;" alt="Bukti Kirim">
                </a>
            </div>
            @endif

            {{-- Aksi per-batch --}}
            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px;">
                {{-- Cetak SJ (hanya yang sudah dikirim) --}}
                @if($isShipped)
                <a href="{{ route('print.order', ['order' => $order->id, 'batch_id' => $batch->id]) }}" target="_blank"
                   style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; background: transparent; border: 1.5px solid #64748b; border-radius: 8px; color: #94a3b8; font-size: 0.75rem; font-weight: 600; text-decoration: none; cursor: pointer;">
                    🖨️ Cetak Ulang Surat Jalan
                </a>
                @endif

                {{-- Aksi transisi status: dihandle via Filament Action di ViewOrder --}}
                @if($batch->status === 'pending_production')
                <button wire:click="mountAction('batch_start_production_{{ $batch->id }}')"
                        style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; background: #78350f; border: 1.5px solid #d97706; border-radius: 8px; color: #fbbf24; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                    🔨 Mulai Produksi
                </button>
                @elseif($batch->status === 'producing')
                <button wire:click="mountAction('batch_mark_ready_{{ $batch->id }}')"
                        style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; background: #1e3a5f; border: 1.5px solid #2563eb; border-radius: 8px; color: #60a5fa; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                    📦 Tandai Siap Dikirim
                </button>
                @elseif($batch->status === 'ready_to_ship')
                <button wire:click="mountAction('batch_dispatch_{{ $batch->id }}')"
                        style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; background: linear-gradient(135deg, #c2410c, #9a3412); border: none; border-radius: 8px; color: #fff; font-size: 0.75rem; font-weight: 700; cursor: pointer; box-shadow: 0 2px 8px rgba(234,88,12,0.4);">
                    🚚 Berangkatkan Truk
                </button>
                @elseif($batch->status === 'shipped')
                <button wire:click="mountAction('batch_delivered_{{ $batch->id }}')"
                        style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; background: #14532d; border: 1.5px solid #16a34a; border-radius: 8px; color: #4ade80; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                    ✅ Tandai Diterima di Lokasi
                </button>
                @elseif($batch->status === 'delivered')
                <span style="color: #16a34a; font-size: 0.75rem; font-weight: 600;">✅ Selesai — Diterima {{ $batch->actual_delivered_date?->format('d M Y') ?? '' }}</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
