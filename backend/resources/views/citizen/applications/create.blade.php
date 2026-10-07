@extends('layouts.app')
@section('content')
<section class="hero-gradient text-white"><div class="mx-auto max-w-7xl px-5 py-14"><p class="font-bold uppercase tracking-widest text-emerald-200">Pengajuan DMLS</p><h1 class="mt-3 text-4xl font-black">{{ $service->name }}</h1><p class="mt-3 text-blue-100">Lengkapi data pemohon dan unggah seluruh persyaratan.</p></div></section>
<section class="pattern"><div class="mx-auto max-w-3xl px-5 py-12"><form action="{{ route('citizen.applications.store', $service) }}" method="POST" enctype="multipart/form-data" class="rounded-3xl bg-white p-8 shadow-sm">@csrf<h2 class="text-2xl font-black text-solo-blue">Data pemohon</h2><div class="mt-7 grid gap-5"><label class="grid gap-2 text-sm font-bold">Nama lengkap<input name="applicant_name" value="{{ old('applicant_name', auth()->user()->name) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-solo-blue focus:outline-none focus:ring-2 focus:ring-blue-100" required></label><label class="grid gap-2 text-sm font-bold">NIK<input value="{{ auth()->user()->citizenProfile?->nik }}" class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-600" readonly></label><label class="grid gap-2 text-sm font-bold">Nomor telepon<input name="phone" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-solo-blue focus:outline-none focus:ring-2 focus:ring-blue-100" required></label><label class="grid gap-2 text-sm font-bold">Alamat domisili<textarea name="address" rows="3" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-solo-blue focus:outline-none focus:ring-2 focus:ring-blue-100" required></textarea></label><label class="grid gap-2 text-sm font-bold">Catatan tambahan <span class="font-normal text-slate-500">(opsional)</span><textarea name="note" rows="3" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-solo-blue focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea></label></div><div class="mt-8 border-t border-slate-200 pt-7"><h2 class="text-2xl font-black text-solo-blue">Upload persyaratan</h2><p class="mt-2 text-sm leading-6 text-slate-500">Unggah satu file untuk setiap persyaratan. Format PDF, JPG, JPEG, atau PNG maksimal 5 MB.</p><div class="mt-6 grid gap-5">@foreach($service->requirements ?? [] as $index => $requirement)<label class="file-upload-field grid gap-2 text-sm font-bold text-slate-700"><span>{{ $index + 1 }}. {{ $requirement }}</span><div class="relative"><input name="requirements[]" type="file" accept="application/pdf,image/jpeg,image/png" class="application-file-input w-full rounded-xl border border-slate-300 bg-white p-3 text-slate-800 shadow-sm file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:font-bold file:text-solo-blue focus:border-solo-blue focus:outline-none focus:ring-2 focus:ring-blue-100" required><span class="file-status absolute right-4 top-1/2 hidden -translate-y-1/2 text-sm font-black"></span></div></label>@endforeach</div></div><button id="submit-application" type="submit" class="mt-8 rounded-full bg-solo-blue px-6 py-3 font-extrabold text-white shadow-md transition duration-150 hover:bg-solo-deep hover:shadow-lg active:scale-95 active:shadow-inner disabled:cursor-wait disabled:opacity-70 focus:outline-none focus:ring-4 focus:ring-blue-200">Kirim permohonan</button></form></div></section>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.application-file-input').forEach((input) => {
    input.addEventListener('change', () => {
        const status = input.closest('.file-upload-field')?.querySelector('.file-status');
        if (!status) return;
        status.classList.remove('hidden', 'text-emerald-600', 'text-red-600');
        if (!input.files.length) { status.classList.add('hidden'); return; }
        const file = input.files[0];
        const valid = ['application/pdf', 'image/jpeg', 'image/png'].includes(file.type) && file.size <= 5 * 1024 * 1024;
        status.textContent = valid ? '✓ File siap' : '✕ File tidak valid';
        status.classList.add(valid ? 'text-emerald-600' : 'text-red-600');
    });
});

document.querySelector('form[enctype="multipart/form-data"]')?.addEventListener('submit', (event) => {
    const button = document.querySelector('#submit-application');
    if (!button) return;
    button.disabled = true;
    button.innerHTML = '<span class="inline-flex items-center gap-2"><span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>Mengirim...</span>';
});
</script>
@endpush
