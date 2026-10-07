@extends('layouts.app')
@section('content')
<section class="hero-gradient text-white">
    <div class="mx-auto max-w-7xl px-5 py-14">
        <p class="font-bold uppercase tracking-widest text-emerald-200">Panel admin</p>
        <h1 class="mt-3 text-4xl font-black">Profil Saya</h1>
        <p class="mt-3 text-blue-100">Perbarui nama, email, dan password akun Anda.</p>
    </div>
</section>
<section class="pattern">
    <div class="mx-auto max-w-3xl px-5 py-12">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="grid gap-5 rounded-3xl bg-white p-8 shadow-sm">
            @csrf @method('PUT')
            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 p-4 font-bold text-emerald-700">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
                    <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-4 rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-solo-blue text-2xl font-black text-white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-lg font-black text-slate-800">{{ $user->name }}</p>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-solo-green">
                        {{ $user->role === 'kelurahan_officer'
                            ? ('Petugas '.($kelurahan ? 'Kelurahan '.$kelurahan->name : 'Kelurahan'))
                            : 'Administrator' }}
                    </p>
                </div>
            </div>

            <label class="grid gap-2 font-bold">Nama lengkap
                <input name="name" value="{{ old('name', $user->name) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-solo-blue focus:ring-4 focus:ring-solo-blue/10" required>
            </label>

            <label class="grid gap-2 font-bold">Email
                <input name="email" type="email" value="{{ old('email', $user->email) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-solo-blue focus:ring-4 focus:ring-solo-blue/10" required>
            </label>

            <div class="border-t pt-5">
                <h2 class="text-xl font-black text-solo-blue">Ganti password</h2>
                <p class="mt-1 text-sm text-slate-500">Kosongkan semua kolom jika tidak ingin mengubah password.</p>
                <div class="mt-4 grid gap-4">
                    <input name="current_password" type="password" placeholder="Password saat ini" autocomplete="current-password" class="rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-solo-blue focus:ring-4 focus:ring-solo-blue/10">
                    <input name="password" type="password" placeholder="Password baru (min. 8 karakter)" autocomplete="new-password" class="rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-solo-blue focus:ring-4 focus:ring-solo-blue/10">
                    <input name="password_confirmation" type="password" placeholder="Konfirmasi password baru" autocomplete="new-password" class="rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-solo-blue focus:ring-4 focus:ring-solo-blue/10">
                </div>
            </div>

            <button class="rounded-full bg-solo-blue px-6 py-3 font-extrabold text-white transition hover:bg-solo-deep">Simpan profil</button>
        </form>
    </div>
</section>
@endsection
