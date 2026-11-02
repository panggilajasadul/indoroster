@php
    $order = $getRecord();
    $documents = \App\Models\ManualDocument::where('extra_data->order_id', $order->id)
        ->orWhere('document_number', 'like', "%{$order->order_number}%")
        ->orderByDesc('id')
        ->get();
@endphp

<div class="space-y-3">
    @if($documents->isEmpty())
        <div class="text-center py-6 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl bg-slate-50/50 dark:bg-slate-900/30">
            <div class="text-3xl mb-1">📄</div>
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum Ada Dokumen Tambahan</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Gunakan tombol <strong>"📄 Terbitkan Dokumen Tambahan"</strong> di bagian atas untuk membuat SPH, BAST, Faktur, atau Sertifikat Lab.
            </p>
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
            <table class="w-full text-left text-xs divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-4 py-3">No. Dokumen</th>
                        <th class="px-4 py-3">Tipe Dokumen</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Penandatangan</th>
                        <th class="px-4 py-3 text-right">Nilai / Total</th>
                        <th class="px-4 py-3 text-center">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @foreach($documents as $doc)
                        @php
                            $printUrl = route('print.manual-document', $doc->id);
                            $printBlankUrl = route('print.manual-document', $doc->id) . '?blank_sign=1';
                            $editUrl = \App\Filament\Resources\ManualDocumentResource::getUrl('edit', ['record' => $doc]);
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 font-mono font-bold text-slate-800 dark:text-slate-100">
                                {{ $doc->document_number }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                    {{ $doc->getTypeLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-400">
                                {{ $doc->document_date ? \Carbon\Carbon::parse($doc->document_date)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-800 dark:text-slate-200">{{ $doc->issued_by ?: '-' }}</div>
                                <div class="text-[10px] text-slate-500">{{ $doc->extra_data['signer_role'] ?? 'Penanggung Jawab' }}</div>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-800 dark:text-slate-200">
                                Rp {{ number_format($doc->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ $printUrl }}" target="_blank"
                                       title="Cetak PDF Digital Lengkap Cap & TTD"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-300 transition-colors border border-emerald-200 dark:border-emerald-800">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>PDF (TTD)</span>
                                    </a>
                                    <a href="{{ $printBlankUrl }}" target="_blank"
                                       title="Cetak Fisik Kertas: Tanpa TTD & Cap (Siap Tanda Tangan Pulpen & Cap Basah Manual)"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-50 text-slate-700 hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-300 transition-colors border border-slate-300 dark:border-slate-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Cetak Fisik</span>
                                    </a>
                                    <a href="{{ $editUrl }}" target="_blank"
                                       title="Edit Dokumen Ini"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 dark:bg-amber-950/50 dark:text-amber-300 transition-colors border border-amber-200 dark:border-amber-800">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

