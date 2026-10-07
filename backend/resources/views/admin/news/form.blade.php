@extends('layouts.app')
@section('content')<section class="pattern"><div class="mx-auto max-w-3xl px-5 py-12"><h1 class="text-4xl font-black text-solo-blue">{{ $news->exists ? 'Edit Berita' : 'Tambah Berita' }}</h1>@if($errors->any())<div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-700"><p class="font-black">Berita belum tersimpan, mohon perbaiki:</p><ul class="mt-2 list-disc pl-5 text-sm font-bold">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif<form class="mt-8 grid gap-5 rounded-3xl bg-white p-8 shadow-sm" action="{{ $news->exists ? route('admin.news.update', $news) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data">@csrf @if($news->exists) @method('PUT') @endif<label class="grid gap-2 font-bold">Judul<input name="title" value="{{ old('title', $news->title) }}" class="news-form-control rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-solo-blue focus:ring-4 focus:ring-solo-blue/10" required></label><p class="-mt-3 text-xs text-slate-400">URL berita (slug) dibuat otomatis dari judul: <span class="font-bold text-slate-500">{{ \Illuminate\Support\Str::slug(old('title', $news->title)) ?: 'contoh-judul-berita' }}</span></p><label class="grid gap-2 font-bold">Ringkasan<textarea name="excerpt" rows="3" class="news-form-control rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-solo-blue focus:ring-4 focus:ring-solo-blue/10" required>{{ old('excerpt', $news->excerpt) }}</textarea></label><label class="grid gap-2 font-bold">Isi berita</label><div class="quill-shell"><div id="news-editor" class="hidden"></div></div><textarea name="body" id="news-body-input" rows="12" class="news-form-control rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-800 shadow-sm outline-none transition focus:border-solo-blue focus:ring-4 focus:ring-solo-blue/10" aria-label="Isi berita" required>{{ old('body', $news->body) }}</textarea><label class="grid gap-2 font-bold">Gambar<input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="rounded-xl border border-slate-300 p-3"></label><label class="grid gap-2 font-bold">Tanggal publikasi<input name="published_at" type="datetime-local" value="{{ old('published_at', $news->published_at?->format('Y-m-d\TH:i')) }}" class="news-form-control rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-solo-blue focus:ring-4 focus:ring-solo-blue/10"></label><p class="-mt-3 text-xs text-slate-400">Waktu dalam WIB. Berita baru tayang setelah waktu ini tercapai. Sekarang: <span class="font-bold text-slate-500" data-clock-now>memuat…</span></p><label class="flex items-center gap-2 font-bold"><input name="is_published" type="checkbox" value="1" @checked(old('is_published', $news->is_published))> Publikasikan berita</label><button class="rounded-full bg-solo-blue px-6 py-3 font-extrabold text-white">Simpan berita</button></form></div></section>@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
.quill-shell { border: 1px solid #cbd5e1; border-radius: .75rem; overflow: hidden; background: #fff; box-shadow: 0 1px 2px rgba(15,23,42,.06); transition: border-color .2s ease, box-shadow .2s ease; }
.quill-shell:focus-within { border-color: #075a91; box-shadow: 0 0 0 4px rgba(7,90,145,.1); }
.ql-toolbar.ql-snow { border: 0; border-bottom: 1px solid #e2e8f0; background: #f8fafc; flex-wrap: wrap; }
.ql-container.ql-snow { border: 0; font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif; font-size: 1rem; }
.ql-editor { min-height: 320px; padding: 1.25rem; line-height: 1.75; color: #334155; }
.ql-editor h1 { font-size: 2rem; font-weight: 900; color: #075a91; margin: 1rem 0 .5rem; line-height: 1.25; }
.ql-editor h2 { font-size: 1.5rem; font-weight: 800; color: #0d3555; margin: .9rem 0 .45rem; line-height: 1.3; }
.ql-editor h3 { font-size: 1.2rem; font-weight: 800; color: #1e40af; margin: .8rem 0 .4rem; line-height: 1.35; }
.ql-editor p { margin: 0 0 .7rem; }
.ql-editor a { color: #075a91; text-decoration: underline; }
.ql-editor blockquote { border-left: 4px solid #3caf4a; padding-left: 1rem; color: #475569; font-style: italic; margin: .8rem 0; }
.ql-snow .ql-picker { font-weight: 700; }
.ql-snow .ql-stroke { stroke: #075a91; }
.ql-snow .ql-fill { fill: #075a91; }
.ql-snow .ql-picker-label { color: #075a91; }
.ql-snow button:hover .ql-stroke, .ql-snow .ql-picker-label:hover { stroke: #3caf4a; }
.ql-snow button:hover .ql-fill { fill: #3caf4a; }
.ql-snow button.ql-active .ql-stroke { stroke: #3caf4a; }
.ql-snow button.ql-active .ql-fill { fill: #3caf4a; }
.ql-snow .ql-picker-options { background: #fff; border: 1px solid #e2e8f0; border-radius: .5rem; box-shadow: 0 10px 24px rgba(15,23,42,.12); }
.ql-tooltip { z-index: 40; }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
(function () {
    const target = document.getElementById('news-body-input');
    const mount = document.getElementById('news-editor');
    if (!target || !mount) return;
    if (typeof Quill === 'undefined') return; // CDN gagal: biarkan textarea biasa
    mount.classList.remove('hidden');
    target.classList.add('hidden');
    target.required = false;
    const quill = new Quill(mount, {
        theme: 'snow',
        placeholder: 'Tulis isi berita di sini… gunakan toolbar untuk judul H1/H2/H3, warna font, bold, dan lainnya.',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['blockquote', 'link'],
                [{ align: [] }],
                ['clean']
            ]
        }
    });
    const initial = target.value || '';
    if (initial.trim()) {
        if (/^</.test(initial.trim())) quill.root.innerHTML = initial;
        else quill.setText(initial);
    }
    const sync = () => { target.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML; };
    quill.on('text-change', sync);
    sync();
    target.form?.addEventListener('submit', (event) => {
        sync();
        if (!target.value.trim()) {
            event.preventDefault();
            alert('Isi berita tidak boleh kosong.');
            quill.focus();
        }
    });
    const preview = document.querySelector('p.-mt-3 span');
    const title = document.querySelector('input[name="title"]');
    title?.addEventListener('input', () => { if (preview) preview.textContent = (window.slugify ? window.slugify(title.value) : title.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '')) || 'contoh-judul-berita'; });
})();
</script>
@endpush
