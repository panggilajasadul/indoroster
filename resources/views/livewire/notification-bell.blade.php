<div class="relative inline-block text-left" 
     x-data="{ open: @entangle('isOpen'), notifTimer: null }"
     @mouseenter="clearTimeout(notifTimer); open = true; $wire.loadNotifications()"
     @mouseleave="notifTimer = setTimeout(() => { open = false }, 300)">
    <!-- Bell Button -->
    <button @click="open = !open; if(open) { $wire.loadNotifications() }" type="button" class="relative p-2 text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white focus:outline-none transition-colors cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <!-- Unread Badge -->
        @if($unreadCount > 0)
        <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full shadow-xs">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
         class="origin-top-right absolute right-0 mt-2 w-[calc(100vw-2rem)] sm:w-96 rounded-2xl shadow-2xl dark:shadow-[0_20px_60px_rgba(0,0,0,0.7)] bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200/90 dark:border-slate-800 focus:outline-none z-50 overflow-hidden"
         style="display: none;">
         
        <!-- Header -->
        <div class="px-5 py-3.5 flex justify-between items-center bg-slate-50 dark:bg-slate-950/70 border-b border-slate-200/80 dark:border-slate-800">
            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider font-display">Notifikasi</h3>
            @if($unreadCount > 0)
            <button wire:click="markAllAsRead" class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:text-terra-700 dark:hover:text-terra-300 transition-colors cursor-pointer">Tandai semua dibaca</button>
            @endif
        </div>

        <!-- Notification Items List -->
        <div class="max-h-[70vh] overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/80">
            @forelse($notifications as $notification)
                @php
                    $isUnread = $notification->unread();
                    $type = $notification->data['type'] ?? 'default';
                @endphp
                <div wire:click="markAsRead('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}')" 
                     class="block px-5 py-4 cursor-pointer transition-all duration-150 relative {{ $isUnread ? 'bg-amber-50/30 dark:bg-terra-500/[0.08] hover:bg-amber-50/60 dark:hover:bg-terra-500/[0.14] border-l-3 border-terra-500' : 'bg-transparent hover:bg-slate-50/80 dark:hover:bg-slate-800/50 border-l-3 border-transparent' }}">
                    <div class="flex justify-between items-start gap-3.5">
                        <div class="flex-shrink-0 mt-0.5">
                            @if($type === 'comment_replied')
                                <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-200/60 dark:border-blue-800/50 shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                </div>
                            @elseif($type === 'order_status')
                                <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-terra-600 dark:text-terra-400 flex items-center justify-center border border-amber-200/60 dark:border-terra-500/30 shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold leading-tight {{ $isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-200' }}">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>
                            
                            @if($type === 'comment_replied' && isset($notification->data['parent_body']))
                                <div class="mt-1.5 p-2 bg-slate-100/80 dark:bg-slate-800/80 rounded-lg text-[11px] text-slate-600 dark:text-slate-400 italic border-l-2 border-slate-300 dark:border-slate-600">
                                    "{{ \Illuminate\Support\Str::limit($notification->data['parent_body'], 60) }}"
                                </div>
                            @endif
                            
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed break-words">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            
                            <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if($isUnread)
                            <div class="flex-shrink-0 mt-1">
                                <span class="inline-block w-2 h-2 bg-terra-500 rounded-full shadow-[0_0_8px_rgba(234,88,12,0.8)]"></span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 flex flex-col items-center text-center">
                    <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-3 border border-slate-200/60 dark:border-slate-700/60">
                        <svg class="w-7 h-7 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-900 dark:text-white">Belum Ada Notifikasi</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Pemberitahuan transaksi akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
        
        @if(count($notifications) > 0)
        <!-- Footer -->
        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-950/70 border-t border-slate-200/80 dark:border-slate-800 text-center">
            <a href="{{ route('member.notifications') }}" wire:navigate class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:text-terra-500 dark:hover:text-terra-300 transition-colors inline-flex items-center gap-1 font-display tracking-wider">
                Lihat Semua Notifikasi →
            </a>
        </div>
        @endif
    </div>
</div>
