<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex text-xs font-semibold text-slate-400 gap-1.5 mb-2 uppercase tracking-wider">
                    <a href="{{ route('home') }}" class="hover:text-slate-600 transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-slate-600">Notifikasi</span>
                </nav>
                <h1 class="font-display text-fluid-h1 font-black text-slate-900 tracking-tight">Notifikasi Saya</h1>
                <p class="text-slate-500 mt-1">Dapatkan informasi status pesanan, balasan komentar, dan informasi penting lainnya di sini.</p>
            </div>

            <!-- Quick Actions -->
            @if($notifications->count() > 0)
                <div class="flex flex-wrap gap-2.5 shrink-0">
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <button wire:click="markAllAsRead" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 hover:text-slate-900 rounded-xl text-xs font-bold transition shadow-sm cursor-pointer">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Tandai Semua Dibaca
                        </button>
                    @endif
                    <button wire:click="deleteAllNotifications" onclick="confirm('Apakah Anda yakin ingin menghapus semua riwayat notifikasi?') || event.stopImmediatePropagation()" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-700 rounded-xl text-xs font-bold transition shadow-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Bersihkan Semua
                    </button>
                </div>
            @endif
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-medium text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Notifications List -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden divide-y divide-slate-100">
            @forelse($notifications as $notification)
                @php
                    $isUnread = $notification->unread();
                    $type = $notification->data['type'] ?? 'default';
                @endphp
                <div class="flex items-start gap-4 p-5 hover:bg-slate-50/50 transition duration-150 ease-in-out relative border-l-4 {{ $isUnread ? 'bg-blue-50/10 border-terra-500' : 'border-transparent' }}">
                    
                    <!-- Icon Indicator -->
                    <div class="flex-shrink-0 mt-0.5">
                        @if($type === 'comment_replied')
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100/50">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                            </div>
                        @elseif($type === 'order_status')
                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-100/50">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-100/50">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content (Clickable) -->
                    <div class="flex-grow min-w-0 cursor-pointer" wire:click="markAsRead('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}')">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                                {{ $notification->data['title'] ?? 'Notifikasi' }}
                            </h3>
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-terra-500 inline-block shadow-sm"></span>
                            @endif
                        </div>

                        @if($type === 'comment_replied' && isset($notification->data['parent_body']))
                            <div class="mt-2 p-3 bg-slate-50 border-l-3 border-slate-200 rounded-r-lg text-xs text-slate-500 italic max-w-2xl leading-relaxed">
                                "{{ \Illuminate\Support\Str::limit($notification->data['parent_body'], 120) }}"
                            </div>
                        @endif

                        <p class="text-sm text-slate-600 mt-2 leading-relaxed max-w-3xl">
                            {{ $notification->data['message'] ?? '' }}
                        </p>

                        <div class="flex items-center gap-4 mt-3">
                            <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                            @if(isset($notification->data['url']) && $notification->data['url'] !== '#')
                                <span class="text-[11px] font-bold text-terra-600 hover:underline">
                                    Lihat Detail →
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Delete button -->
                    <div class="flex-shrink-0">
                        <button wire:click="deleteNotification('{{ $notification->id }}')" title="Hapus Notifikasi" class="p-1.5 rounded-lg text-slate-300 hover:text-rose-600 hover:bg-rose-50 transition-colors focus:outline-none cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-20 flex flex-col items-center text-center px-6">
                    <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mb-5 border border-slate-100">
                        <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-800">Belum Ada Notifikasi</h3>
                    <p class="text-sm text-slate-500 mt-2 max-w-sm">Semua pemberitahuan tentang pesanan dan interaksi komentar Anda akan terekam di halaman ini.</p>
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
