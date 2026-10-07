<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal layanan digital Pemerintah Kota Surakarta.">
    <title>{{ $title ?? 'Portal Surakarta' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-solid-transparent.png') }}">
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50">
    @if(!request()->routeIs('login','register'))
    <div class="bg-solo-blue text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-5 py-3 text-sm font-bold sm:text-base">
            <div class="flex flex-wrap gap-5"><span>☎ (0271) 2931667</span><span>✉ email@surakarta.go.id</span></div>
            <div class="flex items-center gap-4"><span class="inline-flex items-center gap-1.5"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><span data-current-date>Memuat tanggal...</span></span><span class="inline-flex items-center gap-1.5"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg><span data-current-time>Memuat waktu...</span> WIB</span></div>
        </div>
    </div>
    <header data-site-header class="site-header-no-border pattern sticky top-0 z-40 bg-white/95 backdrop-blur" style="border:0!important;box-shadow:none!important;background-color:#ffffff;background-image:linear-gradient(rgba(255,255,255,.55),rgba(255,255,255,.55)),url('{{ asset('motif-batik-surakarta.png') }}');background-size:cover;background-position:center;">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-8 px-5 py-1.5 lg:py-2">
            <a href="{{ route('home') }}" class="focus-ring flex shrink-0 items-center" aria-label="SOLID, beranda">
                <img src="{{ asset('logo-solid-transparent.png') }}" alt="SOLID - Surakarta Online Letter Information & Digitalization" class="h-28 w-auto max-w-[520px] object-contain sm:h-32" style="max-height:140px;max-width:520px;">
            </a>
            <button data-menu-toggle aria-expanded="false" class="focus-ring rounded-lg border border-slate-300 px-3 py-2 text-solo-blue md:hidden">Menu</button>
            <nav class="hidden items-center gap-8 text-xl font-extrabold text-slate-800 md:flex" aria-label="Navigasi utama">
                <a class="nav-item focus-ring {{ request()->routeIs('home') ? 'nav-active' : '' }}" href="{{ route('home') }}">Home</a>
                <div class="group relative"><button class="nav-item focus-ring inline-flex items-center gap-2 {{ request()->routeIs('profile','vision') ? 'nav-active' : '' }}" type="button">Profil <span class="text-sm transition-transform group-hover:rotate-180">⌄</span></button><div class="invisible absolute left-1/2 top-full z-50 mt-3 w-56 -translate-x-1/2 rounded-xl bg-white p-2 text-base font-semibold text-slate-700 opacity-0 shadow-xl ring-1 ring-slate-200 transition-all group-hover:visible group-hover:opacity-100"><a class="block rounded-lg px-4 py-3 hover:bg-slate-100 hover:text-solo-green" href="{{ route('profile') }}">Tentang Solo</a><a class="block rounded-lg px-4 py-3 hover:bg-slate-100 hover:text-solo-green" href="{{ route('vision') }}">Visi Misi</a></div></div>
                <a class="nav-item focus-ring {{ request()->routeIs('ppid') ? 'nav-active' : '' }}" href="{{ route('ppid') }}">PPID</a>
                <a class="nav-item focus-ring {{ request()->routeIs('contact') ? 'nav-active' : '' }}" href="{{ route('contact') }}">Kontak</a>
                <a class="nav-item focus-ring {{ request()->routeIs('services.*') ? 'nav-active' : '' }}" href="{{ route('services.index') }}">Layanan</a>
                @auth @if(in_array(auth()->user()->role, ['admin', 'kelurahan_officer'], true))<a class="nav-item {{ request()->routeIs('admin.applications.*') ? 'nav-active' : '' }}" href="{{ route('admin.applications.index') }}">Permohonan</a><a class="nav-item {{ request()->routeIs('admin.news.*') ? 'nav-active' : '' }}" href="{{ route('admin.news.index') }}">Berita</a><div class="relative"><button type="button" data-notif-toggle aria-expanded="false" class="notif-bell relative flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-solo-blue transition hover:border-solo-blue hover:bg-solo-blue hover:text-white" aria-label="Notifikasi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg><span data-notif-badge class="notif-badge hidden"></span></button><div data-notif-panel data-notif-endpoint="{{ route('admin.notifications.index') }}" data-notif-readall-url="{{ route('admin.notifications.read-all') }}" data-notif-read-base="{{ url('admin/notifikasi') }}" class="invisible absolute right-0 top-full z-50 mt-3 w-[23rem] max-w-[92vw] origin-top-right scale-95 overflow-hidden rounded-2xl bg-white opacity-0 shadow-2xl ring-1 ring-slate-200 transition-all" style="max-height:70vh"><div class="flex items-center justify-between gap-3 border-b border-slate-100 bg-gradient-to-r from-solo-blue to-solo-deep px-4 py-3.5 text-white"><p class="text-sm font-black uppercase tracking-wider">Notifikasi</p><button type="button" data-notif-readall class="rounded-full bg-white/15 px-3 py-1 text-xs font-bold transition hover:bg-white/30">Tandai semua dibaca</button></div><div data-notif-list class="notif-scroll max-h-[55vh] overflow-y-auto"><div data-notif-loading class="p-6 text-center text-sm text-slate-400">Memuat notifikasi…</div></div><a href="{{ route('admin.applications.index') }}" class="block border-t border-slate-100 bg-slate-50 px-4 py-3 text-center text-xs font-black text-solo-blue transition hover:bg-solo-blue hover:text-white">Lihat semua permohonan →</a></div></div><div class="group relative"><button class="nav-item inline-flex items-center gap-2" type="button"><span class="flex h-8 w-8 items-center justify-center rounded-full bg-solo-blue text-sm font-black text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span> {{ auth()->user()->role === 'kelurahan_officer' && auth()->user()->citizenProfile?->kelurahan ? 'Admin '.auth()->user()->citizenProfile->kelurahan->name : 'Admin' }} <span class="text-sm transition-transform group-hover:rotate-180">⌄</span></button><div class="invisible absolute right-0 top-full z-50 mt-3 w-64 rounded-xl bg-white p-2 text-base font-semibold text-slate-700 opacity-0 shadow-xl ring-1 ring-slate-200 transition-all group-hover:visible group-hover:opacity-100"><div class="border-b border-slate-100 px-4 py-3"><p class="truncate font-black text-solo-blue">{{ auth()->user()->name }}</p><p class="truncate text-sm text-slate-500">{{ auth()->user()->email }}</p></div><a class="mt-1 block rounded-lg px-4 py-3 hover:bg-slate-100 hover:text-solo-green {{ request()->routeIs('admin.profile.*') ? 'bg-slate-100 text-solo-green' : '' }}" href="{{ route('admin.profile.edit') }}">✎ Edit profil admin</a><form action="{{ route('logout') }}" method="POST">@csrf<button class="w-full rounded-lg px-4 py-3 text-left hover:bg-slate-100 hover:text-red-600">↪ Keluar</button></form></div></div> @else<a class="nav-item focus-ring {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}" href="{{ route('dashboard') }}">Permohonan</a><div class="relative"><button type="button" data-notif-toggle aria-expanded="false" class="notif-bell relative flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-solo-blue transition hover:border-solo-blue hover:bg-solo-blue hover:text-white" aria-label="Notifikasi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg><span data-notif-badge class="notif-badge hidden"></span></button><div data-notif-panel data-notif-endpoint="{{ route('citizen.notifications.index') }}" data-notif-readall-url="{{ route('citizen.notifications.read-all') }}" data-notif-read-base="{{ url('notifikasi') }}" class="invisible absolute right-0 top-full z-50 mt-3 w-[23rem] max-w-[92vw] origin-top-right scale-95 overflow-hidden rounded-2xl bg-white opacity-0 shadow-2xl ring-1 ring-slate-200 transition-all" style="max-height:70vh"><div class="flex items-center justify-between gap-3 border-b border-slate-100 bg-gradient-to-r from-solo-blue to-solo-deep px-4 py-3.5 text-white"><p class="text-sm font-black uppercase tracking-wider">Notifikasi</p><button type="button" data-notif-readall class="rounded-full bg-white/15 px-3 py-1 text-xs font-bold transition hover:bg-white/30">Tandai semua dibaca</button></div><div data-notif-list class="notif-scroll max-h-[55vh] overflow-y-auto"><div class="p-6 text-center text-sm text-slate-400">Memuat notifikasi…</div></div><a href="{{ route('dashboard') }}" class="block border-t border-slate-100 bg-slate-50 px-4 py-3 text-center text-xs font-black text-solo-blue transition hover:bg-solo-blue hover:text-white">Lihat dashboard →</a></div></div><div class="group relative"><button class="nav-item inline-flex items-center gap-2 {{ request()->routeIs('profile.edit') ? 'nav-active' : '' }}" type="button"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-solo-green text-sm font-black text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span> <span class="max-w-[9rem] truncate">Profil Saya</span> <span class="text-sm transition-transform group-hover:rotate-180">⌄</span></button><div class="invisible absolute right-0 top-full z-50 mt-3 w-64 rounded-xl bg-white p-2 text-base font-semibold text-slate-700 opacity-0 shadow-xl ring-1 ring-slate-200 transition-all group-hover:visible group-hover:opacity-100"><div class="border-b border-slate-100 px-4 py-3"><p class="truncate font-black text-solo-blue">{{ auth()->user()->name }}</p><p class="truncate text-sm text-slate-500">{{ auth()->user()->email }}</p></div><a class="mt-1 block rounded-lg px-4 py-3 hover:bg-slate-100 hover:text-solo-green {{ request()->routeIs('profile.edit') ? 'bg-slate-100 text-solo-green' : '' }}" href="{{ route('profile.edit') }}">✎ Edit profil</a><a class="block rounded-lg px-4 py-3 hover:bg-slate-100 hover:text-solo-green" href="{{ route('dashboard') }}">▦ Dashboard</a><form action="{{ route('logout') }}" method="POST">@csrf<button class="w-full rounded-lg px-4 py-3 text-left hover:bg-slate-100 hover:text-red-600">↪ Keluar</button></form></div></div> @endif @else<a class="rounded-full bg-solo-blue px-6 py-3 text-white" href="{{ url('/masuk') }}">Masuk</a>@endauth
            </nav>
        </div>
        <nav data-mobile-menu class="hidden border-t border-slate-200 bg-white px-5 py-4 md:hidden" aria-label="Navigasi mobile">
            <div class="grid gap-3 text-sm font-bold"><a href="{{ route('home') }}">Home</a><a href="{{ route('profile') }}">Profil</a><a href="{{ route('vision') }}">Visi Misi</a><a href="{{ route('ppid') }}">PPID</a><a href="{{ route('contact') }}">Kontak</a><a href="{{ route('services.index') }}">Layanan</a>@auth @if(in_array(auth()->user()->role, ['admin', 'kelurahan_officer'], true))<a href="{{ route('admin.applications.index') }}">Permohonan</a><a href="{{ route('admin.news.index') }}">Berita</a><a href="{{ route('admin.profile.edit') }}">Profil admin</a>@else<a href="{{ route('dashboard') }}">Permohonan</a><a href="{{ route('profile.edit') }}">Profil Saya</a>@endif<form action="{{ route('logout') }}" method="POST">@csrf<button class="text-left">Keluar</button></form>@else<a href="{{ route('register') }}">Daftar</a><a href="{{ url('/masuk') }}">Masuk</a>@endauth</div>
        </nav>
    </header>
    @endif
    <main id="main-content">@yield('content')</main>
    @if(!request()->routeIs('login','register'))
    <div class="floating-actions" aria-label="Aksi cepat">
        <a class="floating-wa" href="https://wa.me/6285855070067?text=Halo%20SOLID%2C%20saya%20ingin%20bertanya%20tentang%20layanan%20surat%20digital." target="_blank" rel="noopener" aria-label="Chat customer service via WhatsApp">
            <span class="floating-wa-icon" aria-hidden="true">
                <svg viewBox="0 0 32 32" fill="currentColor" width="26" height="26"><path d="M16.04 3C9.42 3 4.05 8.37 4.05 14.99c0 2.11.55 4.17 1.6 6L4 29l8.2-1.6a12 12 0 0 0 3.84.63c6.62 0 11.99-5.37 11.99-11.99C28.03 8.37 22.66 3 16.04 3zm0 21.8c-1.2 0-2.38-.32-3.4-.93l-.25-.15-4.87.95.96-4.75-.16-.25a9.74 9.74 0 0 1-1.5-5.2c0-5.39 4.39-9.77 9.78-9.77 5.39 0 9.77 4.38 9.77 9.77 0 5.39-4.38 9.78-9.77 9.78zm5.37-7.32c-.29-.15-1.73-.85-2-.95-.27-.1-.46-.15-.66.15-.19.29-.76.95-.93 1.14-.17.2-.35.22-.64.07-.29-.15-1.23-.45-2.34-1.44-.86-.77-1.45-1.72-1.62-2.01-.17-.29-.02-.45.13-.6.13-.13.29-.35.44-.52.14-.17.19-.29.29-.49.1-.19.05-.37-.02-.52-.08-.15-.66-1.59-.9-2.18-.24-.57-.48-.49-.66-.5h-.57c-.19 0-.5.07-.77.37-.27.29-1.02.99-1.02 2.42s1.05 2.81 1.19 3c.15.19 2.07 3.16 5.01 4.43.7.3 1.25.48 1.68.62.7.22 1.35.19 1.86.12.57-.09 1.73-.71 1.97-1.39.24-.68.24-1.27.17-1.39-.07-.12-.26-.19-.55-.34z"/></svg>
            </span>
            <span class="floating-wa-tip">Tanya jawab via WhatsApp</span>
        </a>
        <button type="button" class="floating-top" data-scroll-top aria-label="Kembali ke atas">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
        </button>
    </div>
    @endif

    <button type="button" class="a11y-trigger" data-a11y-toggle aria-expanded="false" aria-controls="a11y-panel" aria-label="Buka menu aksesibilitas (Ctrl+U)">
        <svg viewBox="0 0 24 24" fill="currentColor" width="28" height="28" aria-hidden="true"><path d="M12 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm7 6.5c0 .55-.45 1-1 1s-1-.45-1-1V7a1 1 0 0 0-1-1h-3.6l-.7 2.6L11.5 12H8.7c-.4 0-.75.24-.9.6L6 18l-1.7.7a.996.996 0 0 0-.48 1.3c.2.45.71.68 1.16.48L6.5 19.4l1.4-4.4h1.6l-1.3 4.9c-.14.52.17 1.06.7 1.2.52.14 1.06-.17 1.2-.7L13.2 12h1.3l1.5 4.9c.14.52.68.83 1.2.7.53-.14.84-.68.7-1.2L17.4 11h.1c1.6 0 2.9-1.3 2.9-2.9V7c0-.55-.45-1-1-1s-1 .45-1 1v1.5z"/></svg>
    </button>

    <div id="a11y-panel" class="a11y-panel" data-a11y-panel hidden role="dialog" aria-label="Menu aksesibilitas">
        <div class="a11y-head">
            <p>Menu Aksesibilitas <span>(CTRL+U)</span></p>
            <button type="button" class="a11y-close" data-a11y-toggle aria-label="Tutup menu aksesibilitas">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" width="20" height="20" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
        <div class="a11y-body">
            <div class="a11y-grid">
                <button type="button" class="a11y-item" data-a11y="contrast" aria-pressed="false"><span class="a11y-ico">◐</span>Kontras +</button>
                <button type="button" class="a11y-item" data-a11y="links" aria-pressed="false"><span class="a11y-ico">🔗</span>Tandai Link</button>
                <button type="button" class="a11y-item" data-a11y="bigtext" aria-pressed="false"><span class="a11y-ico">T<sup>T</sup></span>Teks Lebih Besar</button>
                <button type="button" class="a11y-item" data-a11y="spacing" aria-pressed="false"><span class="a11y-ico">↔</span>Jarak Teks</button>
                <button type="button" class="a11y-item" data-a11y="motion" aria-pressed="false"><span class="a11y-ico">⏸</span>Jeda Animasi</button>
                <button type="button" class="a11y-item" data-a11y="images" aria-pressed="false"><span class="a11y-ico">🖼</span>Sembunyikan Gambar</button>
                <button type="button" class="a11y-item" data-a11y="dyslexia" aria-pressed="false"><span class="a11y-ico">Df</span>Font Disleksia</button>
                <button type="button" class="a11y-item" data-a11y="cursor" aria-pressed="false"><span class="a11y-ico">➤</span>Kursor Besar</button>
            </div>
            <button type="button" class="a11y-reset" data-a11y-reset>Atur Ulang</button>
        </div>
    </div>
    <div class="a11y-live" aria-live="polite" data-a11y-live></div>

    @push('scripts')
    <script>
    (function () {
        const token = document.querySelector('meta[name="csrf-token"]')?.content
            || document.querySelector('input[name="_token"]')?.value || '';

        const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

        /* each widget: root = element holding [data-notif-toggle]; config read from its panel */
        document.querySelectorAll('[data-notif-toggle]').forEach((toggle) => {
            const root = toggle.parentElement;
            const panel = root?.querySelector('[data-notif-panel]');
            const badge = root?.querySelector('[data-notif-badge]');
            const list = root?.querySelector('[data-notif-list]');
            const readAll = root?.querySelector('[data-notif-readall]');
            if (!toggle || !panel) return;

            const endpoint = panel.dataset.notifEndpoint;
            const readAllUrl = panel.dataset.notifReadallUrl;
            const readBase = panel.dataset.notifReadBase;

            const setBadge = (n) => {
                if (!badge) return;
                const current = parseInt(badge.dataset.count || '0', 10);
                const next = Number.isFinite(n) ? n : 0;
                badge.dataset.count = String(next);
                if (next > 0) {
                    badge.textContent = next > 99 ? '99+' : String(next);
                    badge.classList.remove('hidden');
                    toggle.classList.add('has-unread');
                    if (next !== current) {
                        badge.classList.remove('is-bump');
                        void badge.offsetWidth; /* restart animation */
                        badge.classList.add('is-bump');
                    }
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('is-bump');
                    toggle.classList.remove('has-unread');
                }
            };

            const render = (items) => {
                if (!list) return;
                if (!items.length) {
                    list.innerHTML = '<div class="p-8 text-center"><div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-xl">🔔</div><p class="mt-3 text-sm font-bold text-slate-500">Belum ada notifikasi</p><p class="mt-1 text-xs text-slate-400">Pembaruan status permohonan Anda akan muncul di sini.</p></div>';
                    return;
                }
                list.innerHTML = items.map((n) => `
                    <a href="${esc(n.link || '#')}" data-notif-id="${n.id}" class="notif-item flex items-start gap-3 border-b border-slate-100 px-4 py-3.5 transition hover:bg-blue-50/70 ${n.is_read ? 'bg-white' : 'is-unread bg-blue-50/50'}">
                        <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl ${n.is_read ? 'bg-slate-100 text-slate-400' : 'bg-gradient-to-br from-solo-blue to-solo-deep text-white shadow-md shadow-solo-blue/25'}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="flex items-baseline justify-between gap-3">
                                <span class="text-sm font-black leading-snug ${n.is_read ? 'text-slate-700' : 'text-solo-blue'}">${esc(n.title)}</span>
                                <span class="shrink-0 text-right text-[11px] font-bold text-slate-400">${esc(n.created_at)}</span>
                            </span>
                            <span class="mt-1 block text-[13px] leading-5 text-slate-600">${esc(n.message)}</span>
                            ${n.is_read ? '' : '<span class="notif-baru mt-2 inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-red-600">Baru</span>'}
                        </span>
                    </a>`).join('');
            };

            const isOpen = () => !panel.classList.contains('invisible');

            const refresh = async () => {
                try {
                    const res = await fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
                    if (!res.ok) return;
                    const data = await res.json();
                    setBadge(data.unread);
                    if (!isOpen()) return;
                    render(data.notifications);
                } catch (e) { /* network hiccup: keep last render */ }
            };

            const open = async () => {
                panel.classList.remove('invisible', 'opacity-0', 'scale-95');
                panel.classList.add('visible', 'opacity-100', 'scale-100');
                toggle.setAttribute('aria-expanded', 'true');
                if (list) list.innerHTML = '<div class="p-6 text-center text-sm text-slate-400">Memuat notifikasi…</div>';
                try {
                    const res = await fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
                    if (!res.ok) throw new Error();
                    const data = await res.json();
                    setBadge(data.unread);
                    render(data.notifications);
                } catch (e) {
                    if (list) list.innerHTML = '<div class="p-6 text-center text-sm text-red-500">Gagal memuat notifikasi. Coba lagi.</div>';
                }
            };

            const close = () => {
                panel.classList.add('invisible', 'opacity-0', 'scale-95');
                panel.classList.remove('visible', 'opacity-100', 'scale-100');
                toggle.setAttribute('aria-expanded', 'false');
            };

            toggle.addEventListener('click', (e) => { e.stopPropagation(); isOpen() ? close() : open(); });
            document.addEventListener('click', (e) => { if (isOpen() && !panel.contains(e.target) && !toggle.contains(e.target)) close(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && isOpen()) close(); });

            list?.addEventListener('click', async (e) => {
                const item = e.target.closest('[data-notif-id]');
                if (!item || item.dataset.reading === '1') return;
                if (!item.classList.contains('is-unread')) return; /* already read: just navigate */
                item.dataset.reading = '1';
                item.classList.remove('is-unread', 'bg-blue-50/50');
                item.classList.add('bg-white');
                item.querySelector('.notif-baru')?.remove();
                const next = Math.max(0, (parseInt(badge?.dataset.count || '0', 10) || 0) - 1);
                setBadge(next);
                /* keepalive lets the request finish even while the page navigates */
                try {
                    await fetch(`${readBase}/${item.dataset.notifId}/read`, {
                        method: 'POST',
                        keepalive: true,
                        headers: { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                    });
                } catch (err) { /* navigation already underway */ }
            });

            readAll?.addEventListener('click', async () => {
                try {
                    await fetch(readAllUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
                    setBadge(0);
                    if (list) render([]);
                    refresh();
                } catch (e) {}
            });

            refresh();
            setInterval(refresh, 45000);
        });
    })();
    </script>
    @endpush
    @stack('scripts')
    @if(!request()->routeIs('login','register'))
    <footer class="bg-solo-blue text-white">
        <div class="mx-auto max-w-7xl px-5 py-14">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                <div><div class="flex flex-wrap items-center gap-4"><div class="rounded-2xl bg-white p-5 shadow-lg"><img src="{{ asset('logo-solid-transparent.png') }}" alt="SOLID" class="h-20 w-full max-w-[240px] object-contain"></div><div class="rounded-2xl bg-white p-5 shadow-lg"><img src="{{ asset('logo-uns.png') }}" alt="Universitas Sebelas Maret" class="h-14 w-full max-w-[240px] object-contain"></div></div><p class="mt-5 max-w-xs text-sm leading-7 text-blue-100">Surakarta Online Letter Information &amp; Digitalization — sistem layanan persuratan digital Pemerintah Kota Surakarta.</p></div>
                <div><h2 class="mb-4 text-xl font-black">Lokasi</h2><div class="overflow-hidden rounded-xl bg-white"><iframe class="h-48 w-full border-0" src="https://maps.google.com/maps?q=Balai%20Kota%20Surakarta&amp;z=15&amp;ie=UTF8&amp;output=embed" title="Peta lokasi Balai Kota Surakarta" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div><p class="mt-3 text-sm leading-7 text-blue-100">Jl. Jend. Sudirman No. 2, Kota Surakarta, Jawa Tengah 57133</p></div>
                <div><h2 class="mb-4 text-xl font-black">Link Terkait</h2><div class="grid gap-3 text-sm text-blue-100"><a class="transition hover:text-white" href="{{ route('ppid') }}">› PPID Kota Surakarta</a><a class="transition hover:text-white" href="{{ route('services.index') }}">› Layanan Digital DMLS</a><a class="transition hover:text-white" href="{{ route('news.index') }}">› Berita Kota Surakarta</a><a class="transition hover:text-white" href="{{ route('contact') }}">› Kontak Pemerintah</a></div></div>
                <div><h2 class="mb-4 text-xl font-black">Pengunjung</h2><dl class="grid gap-2 text-sm text-blue-100"><div class="flex justify-between gap-4"><dt>Hari Ini</dt><dd class="font-black text-white">1596</dd></div><div class="flex justify-between gap-4"><dt>Kemarin</dt><dd class="font-black text-white">1910</dd></div><div class="flex justify-between gap-4"><dt>Bulan Ini</dt><dd class="font-black text-white">34801</dd></div><div class="flex justify-between gap-4"><dt>Total</dt><dd class="font-black text-white">427771</dd></div></dl></div>
            </div>
        </div>
        <div class="bg-solo-deep px-5 py-5 text-center text-sm text-blue-100">© {{ date('Y') }} — Universitas Sebelas Maret &amp; Pemerintah Kota Surakarta.</div>
    </footer>
    @endif
</body>
</html>
