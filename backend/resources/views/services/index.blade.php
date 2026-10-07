@extends('layouts.app')
@section('content')
<section class="hero-gradient text-white"><div class="mx-auto max-w-7xl px-5 py-16"><p class="font-bold uppercase tracking-widest text-emerald-200">Katalog layanan</p><h1 class="mt-3 text-4xl font-black sm:text-5xl">Pilih layanan administrasi Anda</h1><p class="mt-4 max-w-2xl text-blue-50">Semua persyaratan dan alur pengajuan tersedia secara transparan dalam satu tempat.</p></div></section>
<section class="pattern"><div class="mx-auto max-w-7xl px-5 py-14">
    <div class="mb-10 rounded-3xl border border-slate-200/80 bg-white p-4 shadow-[0_10px_30px_-18px_rgba(7,90,145,.45)] sm:p-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="relative w-full lg:max-w-xl">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute left-5 top-1/2 h-5 w-5 -translate-y-1/2 text-solo-blue"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                <input type="search" data-service-search placeholder="Cari layanan… (contoh: SKTM, domisili, nikah)" class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-4 pl-14 pr-16 text-base font-semibold text-slate-800 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-solo-blue focus:bg-white focus:ring-4 focus:ring-solo-blue/10" aria-label="Cari layanan administrasi">
                <span class="pointer-events-none absolute right-4 top-1/2 hidden -translate-y-1/2 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-black tracking-wide text-slate-400 sm:block">CTRL K</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="rounded-2xl bg-solo-blue/8 px-4 py-2.5">
                    <p class="text-sm font-black text-solo-blue"><span data-service-count>{{ $services->count() }}</span> <span class="font-semibold text-slate-600">layanan tersedia</span></p>
                </div>
                <button type="button" data-service-clear class="hidden rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:border-solo-blue hover:bg-solo-blue hover:text-white">Bersihkan</button>
            </div>
        </div>
    </div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-service-grid>@foreach($services as $service)<article class="service-card group flex flex-col rounded-3xl bg-white p-6 ring-1 ring-slate-200/70" data-service-name="{{ strtolower($service->name) }}" data-service-desc="{{ strtolower($service->description) }}"><div class="flex items-start justify-between gap-3"><div class="service-card-icon flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl text-2xl" style="{{ ['users'=>'background:#dcfce7;color:#059669','heart'=>'background:#ffe4e6;color:#e11d48','briefcase'=>'background:#dbeafe;color:#2563eb','map'=>'background:#fef3c7;color:#d97706','identity'=>'background:#dbeafe;color:#2563eb','home'=>'background:#ffe4e6;color:#e11d48','exit'=>'background:#fef3c7;color:#d97706','enter'=>'background:#ffe4e6;color:#e11d48','building'=>'background:#dcfce7;color:#059669','megaphone'=>'background:#dcfce7;color:#059669','stop'=>'background:#dbeafe;color:#2563eb','plane'=>'background:#ffe4e6;color:#e11d48','person'=>'background:#dcfce7;color:#059669','alert'=>'background:#dbeafe;color:#2563eb','file'=>'background:#fef3c7;color:#d97706','money'=>'background:#dcfce7;color:#059669','store'=>'background:#fef3c7;color:#d97706','couple'=>'background:#ffe4e6;color:#e11d48','music'=>'background:#dcfce7;color:#059669','shield'=>'background:#fef3c7;color:#d97706','globe'=>'background:#ffe4e6;color:#e11d48','fuel'=>'background:#dbeafe;color:#2563eb'][$service->icon] ?? 'background:#f1f5f9;color:#475569' }}">{{ ['users'=>'♟','heart'=>'♡','briefcase'=>'▣','map'=>'⌖','identity'=>'▤','home'=>'⌂','exit'=>'⇥','enter'=>'⇤','building'=>'▥','megaphone'=>'⚑','stop'=>'⬡','plane'=>'✈','person'=>'♙','alert'=>'!','file'=>'▧','money'=>'▤','store'=>'⌂','couple'=>'♟','music'=>'♪','shield'=>'♢','globe'=>'◎','fuel'=>'▱'][$service->icon] ?? '▤' }}</div><span class="shrink-0 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-black text-solo-blue ring-1 ring-blue-100">{{ $service->processing_time }}</span></div><h2 class="service-card-title mt-5 text-xl font-black leading-snug text-slate-800">{{ $service->name }}</h2><p class="service-card-desc mt-2.5 line-clamp-2 min-h-[3rem] text-sm leading-6 text-slate-500">{{ $service->description }}</p><div class="mt-4 mb-6 border-t border-dashed border-slate-200 pt-4"><h3 class="text-[11px] font-black uppercase tracking-[.14em] text-slate-400">Persyaratan utama</h3><ul class="mt-3 grid gap-2 text-sm text-slate-600">@foreach($service->requirements ?? [] as $requirement)<li class="flex items-start gap-2.5"><span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-solo-green/15 text-[10px] font-black text-solo-green">✓</span><span>{{ $requirement }}</span></li>@endforeach</ul></div><a class="service-card-link focus-ring mt-auto inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-solo-blue px-5 py-3.5 text-sm font-extrabold text-white shadow-[0_8px_20px_-10px_rgba(7,90,145,.9)] transition group-hover:bg-solo-deep" href="{{ route('services.show', $service) }}">Lihat detail layanan <span aria-hidden="true" class="transition-transform group-hover:translate-x-1">→</span></a></article>@endforeach
    </div>
    <div data-service-empty class="hidden rounded-3xl border-2 border-dashed border-slate-300 bg-white p-14 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-2xl">🔍</div>
        <p class="mt-4 text-lg font-black text-slate-600">Layanan tidak ditemukan</p>
        <p class="mt-1 text-sm text-slate-500">Coba kata kunci lain, atau <button type="button" data-service-clear class="font-black text-solo-blue underline underline-offset-2">tampilkan semua layanan</button>.</p>
    </div>
</div></section>
@push('scripts')
<script>
(function () {
    const input = document.querySelector('[data-service-search]');
    const grid = document.querySelector('[data-service-grid]');
    const empty = document.querySelector('[data-service-empty]');
    const count = document.querySelector('[data-service-count]');
    const clear = document.querySelectorAll('[data-service-clear]');
    if (!input || !grid) return;
    const cards = [...grid.querySelectorAll('[data-service-name]')];
    const filter = () => {
        const q = input.value.trim().toLowerCase();
        let visible = 0;
        cards.forEach((card) => {
            const match = !q || card.dataset.serviceName.includes(q) || card.dataset.serviceDesc.includes(q);
            card.classList.toggle('hidden', !match);
            if (match) visible++;
        });
        if (count) count.textContent = visible;
        empty?.classList.toggle('hidden', visible > 0);
        grid.classList.toggle('hidden', visible === 0);
        clear.forEach((btn) => btn.classList.toggle('hidden', !q));
    };
    input.addEventListener('input', filter);
    clear.forEach((btn) => btn.addEventListener('click', () => { input.value = ''; filter(); input.focus(); }));
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); input.focus(); input.select(); }
    });
})();
</script>
@endpush
@endsection
