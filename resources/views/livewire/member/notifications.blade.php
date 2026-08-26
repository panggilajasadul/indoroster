<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex text-xs font-semibold text-slate-400 dark:text-slate-500 gap-1.5 mb-2 uppercase tracking-wider">
                    <a href="{{ route('home') }}" class="hover:text-slate-600 dark:hover:text-slate-300 transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-slate-600 dark:text-slate-300">Notifikasi</span>
                </nav>
                <h1 class="font-display text-fluid-h1 font-black text-slate-900 dark:text-white tracking-tight">Notifikasi Saya</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Dapatkan informasi status pesanan, balasan komentar, dan informasi penting lainnya di sini.</p>
            </div>

            <!-- Quick Actions -->
            @if($notifications->count() > 0)
                <div class="flex flex-wrap gap-2.5 shrink-0">
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <button wire:click="markAllAsRead" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 text-slate-700 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white rounded-xl text-xs font-bold transition shadow-2xs cursor-pointer">
                            <svg class="w-4 h-4 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Tandai Semua Dibaca
                        </button>
                    @endif
                    <button wire:click="deleteAllNotifications" onclick="confirm('Apakah Anda yakin ingin menghapus semua riwayat notifikasi?') || event.stopImmediatePropagation()" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/40 border border-rose-100 dark:border-rose-900/50 text-rose-700 dark:text-rose-300 rounded-xl text-xs font-bold transition shadow-2xs cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Bersihkan Semua
                    </button>
                </div>
            @endif
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-xl font-medium text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Notifications List -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-soft-xs overflow-hidden divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($notifications as $notification)
                @php
                    $isUnread = $notification->unread();
                    $type = $notification->data['type'] ?? 'default';
                @endphp
                <div class="flex items-start gap-4 p-5 transition duration-150 ease-in-out relative border-l-4 {{ $isUnread ? 'bg-amber-50/30 dark:bg-terra-500/[0.08] hover:bg-amber-50/60 dark:hover:bg-terra-500/[0.14] border-terra-500' : 'hover:bg-slate-50/80 dark:hover:bg-slate-800/50 border-transparent' }}">
                    
                    <!-- Icon Indicator -->
                    <div class="flex-shrink-0 mt-0.5">
                        @if($type === 'comment_replied')
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-200/60 dark:border-blue-800/50 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                            </div>
                        @elseif($type === 'order_status')
                            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-terra-600 dark:text-terra-400 flex items-center justify-center border border-amber-200/60 dark:border-terra-500/30 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content (Clickable) -->
                    <div class="flex-grow min-w-0 cursor-pointer" wire:click="markAsRead('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}')">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                {{ $notification->data['title'] ?? 'Notifikasi' }}
                            </h3>
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-terra-500 inline-block shadow-[0_0_8px_rgba(234,88,12,0.8)]"></span>
                            @endif
                        </div>

                        @if($type === 'comment_replied' && isset($notification->data['parent_body']))
                            <div class="mt-2 p-3 bg-slate-100/80 dark:bg-slate-800/80 border-l-2 border-slate-300 dark:border-slate-600 rounded-r-lg text-xs text-slate-600 dark:text-slate-400 italic max-w-2xl leading-relaxed">
                                "{{ \Illuminate\Support\Str::limit($notification->data['parent_body'], 120) }}"
                            </div>
                        @endif

                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1.5 leading-relaxed max-w-3xl">
                            {{ $notification->data['message'] ?? '' }}
                        </p>

                        <div class="flex items-center gap-4 mt-3">
                            <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                            @if(isset($notification->data['url']) && $notification->data['url'] !== '#')
                                <span class="text-[11px] font-bold text-terra-600 dark:text-terra-400 hover:underline">
                                    Lihat Detail →
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Delete button -->
                    <div class="flex-shrink-0">
                        <button wire:click="deleteNotification('{{ $notification->id }}')" title="Hapus Notifikasi" class="p-1.5 rounded-lg text-slate-400 dark:text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors focus:outline-none cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-20 flex flex-col items-center text-center px-6">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 border border-slate-200/60 dark:border-slate-700/60">
                        <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white">Belum Ada Notifikasi</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-sm">Semua pemberitahuan tentang pesanan dan interaksi komentar Anda akan terekam di halaman ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
