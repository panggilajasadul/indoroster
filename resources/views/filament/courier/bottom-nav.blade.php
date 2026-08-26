<div class="courier-bottom-nav">
    <a href="/courier" class="courier-nav-item {{ request()->is('courier') ? 'active' : '' }}">
        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
        </svg>
        <span>Beranda</span>
    </a>
    <a href="/courier/orders" class="courier-nav-item {{ request()->is('courier/orders*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.321-5.128a2.25 2.25 0 0 0-2.236-2.112H15.75m1.5 8.25h-1.5M6.75 6.75h.75m-.75 3h.75M6.75 13.5h.75m3-6.75H12m-2.25 3H12m-2.25 3H12m3 3H15m0-3H15m0-3H15m0-3H15m0 12h-9"></path>
        </svg>
        <span>Tugas</span>
    </a>
    <a href="/courier/riwayat" class="courier-nav-item {{ request()->is('courier/riwayat*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Riwayat</span>
    </a>
    <form action="/courier/logout" method="POST" style="margin:0; padding:0; height:100%; display:flex; flex:1;">
        @csrf
        <button type="submit" class="courier-nav-item" style="color: #ef4444; width: 100%; height: 100%; background: none; border: none; padding: 0; cursor: pointer;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span>Logout</span>
        </button>
    </form>
</div>
