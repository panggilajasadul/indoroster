@php
    $record = isset($record) ? $record : $getRecord();
    if (!$record) return;
    
    $status = $record->status;
    $type = $record->fulfillment_type;
    $prodStatus = $record->production_status ?? 'pending';
    
    $isPaid = in_array($status, ['paid', 'processing', 'shipped', 'completed']);
    
    // Step 2: Produksi / Persiapan
    $isStep2Active = ($status === 'processing');
    $isStep2Completed = in_array($prodStatus, ['ready_to_ship', 'shipped', 'delivered']) || in_array($status, ['shipped', 'completed']);
    
    // Step 3: Pengiriman
    $isStep3Active = in_array($prodStatus, ['shipped', 'delivered']) || in_array($status, ['shipped', 'completed']);
    $isStep3Completed = ($prodStatus === 'delivered') || ($status === 'completed');
    
    // Step 4: Selesai
    $isCompleted = ($status === 'completed');
@endphp

<div class="p-5" wire:poll.5s style="background: #0f172a; border-radius: 12px; border: 1px solid #1e293b; color: #f8fafc; font-family: inherit;">
    <div style="display: flex; flex-direction: column; gap: 20px;">
        
        <!-- Step 1: Lunas -->
        <div style="display: flex; gap: 15px; align-items: flex-start;">
            <div style="display: flex; flex-direction: column; align-items: center;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: #22c55e; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; color: #fff; font-weight: bold; border: 2px solid #22c55e;">
                    ✓
                </div>
                <div style="width: 2px; height: 40px; background: {{ $isStep2Active || $isStep2Completed ? '#22c55e' : '#334155' }};"></div>
            </div>
            <div>
                <h4 style="margin: 0; font-size: 0.95rem; font-weight: bold; color: #f8fafc;">💳 Pembayaran Diterima (Lunas)</h4>
                <p style="margin: 4px 0 0 0; font-size: 0.78rem; color: #94a3b8;">
                    Pesanan lunas dibayar pada {{ $record->paid_at ? $record->paid_at->format('d M Y H:i') : ($record->created_at ? $record->created_at->format('d M Y H:i') : '-') }}
                </p>
            </div>
        </div>

        <!-- Step 2: Proses Produksi / Persiapan -->
        <div style="display: flex; gap: 15px; align-items: flex-start;">
            @php
                $step2Bg = '#1e293b';
                $step2Color = '#64748b';
                $step2Text = '🔨';
                $step2Border = '#334155';
                
                if ($isStep2Completed) {
                    $step2Bg = '#22c55e';
                    $step2Color = '#fff';
                    $step2Text = '✓';
                    $step2Border = '#22c55e';
                } elseif ($isStep2Active) {
                    $step2Bg = '#eab308';
                    $step2Color = '#0f172a';
                    $step2Border = '#eab308';
                }
            @endphp
            <div style="display: flex; flex-direction: column; align-items: center;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: {{ $step2Bg }}; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; color: {{ $step2Color }}; font-weight: bold; border: 2px solid {{ $step2Border }};">
                    {{ $step2Text }}
                </div>
                <div style="width: 2px; height: 40px; background: {{ $isStep3Active || $isStep3Completed ? '#22c55e' : '#334155' }};"></div>
            </div>
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <h4 style="margin: 0; font-size: 0.95rem; font-weight: bold; color: {{ ($isStep2Active || $isStep2Completed) ? '#f8fafc' : '#64748b' }};">
                            @if($type === 'po_single')
                                {{ $isStep2Completed ? '✓ Produksi Pre-Order (PO) Selesai' : ($prodStatus === 'producing' ? '🔨 Sedang Diproduksi di Pabrik (PO)' : '🔨 Menunggu Produksi (PO)') }}
                            @else
                                📦 Penyiapan Barang di Gudang (Ready Stock)
                            @endif
                        </h4>
                        <p style="margin: 4px 0 0 0; font-size: 0.78rem; color: #94a3b8; line-height: 1.5;">
                            @if($type === 'po_single')
                                {{ $prodStatus === 'producing' || $isStep2Completed ? 'Mulai Produksi (Aktif):' : 'Estimasi Mulai Produksi:' }} 
                                <strong style="color: #ffffff;">{{ $record->production_start_date ? $record->production_start_date->format('d M Y') : '-' }}</strong> <br>
                                Estimasi Siap Kirim: <strong style="color: #ffffff;">{{ $record->ready_shipping_date ? $record->ready_shipping_date->format('d M Y') : '-' }}</strong> <br>
                                Status Produksi: <strong style="color: {{ $prodStatus === 'producing' ? '#eab308' : ($isStep2Completed ? '#22c55e' : '#94a3b8') }};">{{ $prodStatus === 'producing' ? 'Sedang Diproduksi' : ($isStep2Completed ? 'Selesai & Siap Kirim' : 'Menunggu Produksi') }}</strong>
                            @else
                                Estimasi Siap Kirim: <strong style="color: #ffffff;">{{ $record->ready_shipping_date ? $record->ready_shipping_date->format('d M Y') : '-' }}</strong>
                            @endif
                        </p>
                    </div>

                    {{-- Tombol Aksi Step 2 --}}
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        @if($type === 'po_single')
                            @if($prodStatus === 'pending' || !$prodStatus)
                            <button type="button" wire:click.prevent="mountAction('single_start_production')"
                                    style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; background: #d97706; border: 1.5px solid #f59e0b; border-radius: 8px; color: #ffffff; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                🔨 Mulai Produksi Sekarang
                            </button>
                            @elseif($prodStatus === 'producing')
                            <button type="button" wire:click.prevent="mountAction('single_ready_to_ship')"
                                    style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; background: #0284c7; border: 1.5px solid #38bdf8; border-radius: 8px; color: #ffffff; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                📦 Tandai Selesai & Siap Kirim
                            </button>
                            @endif
                        @else
                            @if(!$isStep2Completed && $status !== 'shipped' && $status !== 'completed')
                            <button type="button" wire:click.prevent="mountAction('single_ready_to_ship')"
                                    style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; background: #0284c7; border: 1.5px solid #38bdf8; border-radius: 8px; color: #ffffff; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                📦 Siapkan & Tandai Siap Kirim
                            </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Pengiriman -->
        <div style="display: flex; gap: 15px; align-items: flex-start;">
            @php
                $step3Bg = '#1e293b';
                $step3Color = '#64748b';
                $step3Border = '#334155';
                
                if ($isStep3Completed) {
                    $step3Bg = '#22c55e';
                    $step3Color = '#fff';
                    $step3Border = '#22c55e';
                } elseif ($isStep3Active) {
                    $step3Bg = '#3b82f6';
                    $step3Color = '#fff';
                    $step3Border = '#3b82f6';
                }
            @endphp
            <div style="display: flex; flex-direction: column; align-items: center;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: {{ $step3Bg }}; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; color: {{ $step3Color }}; font-weight: bold; border: 2px solid {{ $step3Border }};">
                    🚚
                </div>
                <div style="width: 2px; height: 40px; background: {{ $isCompleted ? '#22c55e' : '#334155' }};"></div>
            </div>
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <h4 style="margin: 0; font-size: 0.95rem; font-weight: bold; color: {{ $isStep3Active ? '#f8fafc' : '#64748b' }};">🚚 Pengiriman Armada</h4>
                        @if($isStep3Active)
                        <p style="margin: 4px 0 0 0; font-size: 0.78rem; color: #94a3b8; line-height: 1.5;">
                            Supir/Ekspedisi: <strong>{{ $record->courier ?? '-' }}</strong> <br>
                            No. Plat Truk/Resi: <strong style="color: #f97316; font-family: monospace;">{{ $record->tracking_number ?? '-' }}</strong> <br>
                            Waktu Berangkat: {{ $record->shipped_at ? $record->shipped_at->format('d M Y H:i') : '-' }}
                        </p>
                        @else
                        <p style="margin: 4px 0 0 0; font-size: 0.78rem; color: #64748b;">
                            Armada belum diberangkatkan.
                        </p>
                        @endif
                    </div>

                    {{-- Tombol Aksi Step 3 --}}
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        @if(($prodStatus === 'ready_to_ship' || $type === 'ready_stock') && $status !== 'shipped' && $status !== 'completed')
                        <button type="button" wire:click.prevent="mountAction('single_dispatch')"
                                style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; background: #dc2626; border: 1.5px solid #ef4444; border-radius: 8px; color: #ffffff; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                            🚚 Berangkatkan Truk
                        </button>
                        @elseif($status === 'shipped')
                        <a href="{{ route('print.order', $record) }}" target="_blank"
                           style="display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; background: #0f172a; border: 1px solid #475569; border-radius: 6px; color: #cbd5e1; font-size: 0.72rem; font-weight: 600; text-decoration: none;">
                            🖨️ Cetak Surat Jalan
                        </a>
                        <button type="button" wire:click.prevent="mountAction('single_delivered')"
                                style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; background: #059669; border: 1.5px solid #10b981; border-radius: 8px; color: #ffffff; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                            ✅ Konfirmasi Tiba & Selesai
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Selesai -->
        <div style="display: flex; gap: 15px; align-items: flex-start;">
            <div style="display: flex; flex-direction: column; align-items: center;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: {{ $isCompleted ? '#22c55e' : '#1e293b' }}; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; color: {{ $isCompleted ? '#fff' : '#64748b' }}; font-weight: bold; border: 2px solid {{ $isCompleted ? '#22c55e' : '#334155' }};">
                    🏁
                </div>
            </div>
            <div>
                <h4 style="margin: 0; font-size: 0.95rem; font-weight: bold; color: {{ $isCompleted ? '#f8fafc' : '#64748b' }};">🏁 Selesai Diterima di Lokasi</h4>
                @if($isCompleted)
                <p style="margin: 4px 0 0 0; font-size: 0.78rem; color: #94a3b8; line-height: 1.5;">
                    Tiba & Bongkar di Lokasi pada {{ $record->completed_at ? $record->completed_at->format('d M Y H:i') : '-' }}
                </p>
                
                {{-- Foto Bukti Penerimaan --}}
                @if($record->delivery_photo_path)
                <div style="margin-top: 15px; background: #0f172a; border-radius: 10px; padding: 10px; border: 1px solid #334155; display: inline-block;">
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: bold; margin-bottom: 8px;">📸 Foto Bukti Pengiriman (Bongkar Muat):</div>
                    <a href="{{ asset('storage/' . $record->delivery_photo_path) }}" target="_blank" style="display: block;">
                        <img src="{{ asset('storage/' . $record->delivery_photo_path) }}" style="max-height: 180px; border-radius: 6px; border: 1px solid #475569; display: block; object-fit: cover;" alt="Bukti Penerimaan">
                    </a>
                </div>
                @endif
                
                @else
                <p style="margin: 4px 0 0 0; font-size: 0.78rem; color: #64748b;">
                    Menunggu pengiriman tiba di lokasi pembeli.
                </p>
                @endif
            </div>
        </div>

    </div>
</div>
