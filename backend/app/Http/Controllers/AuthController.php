<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\CitizenProfile;
use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('pages.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->validated(), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (in_array($request->user()->role, ['admin', 'kelurahan_officer'], true)) {
            return redirect()->intended(route('admin.applications.index'));
        }

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister(): View
    {
        return view('pages.register', ['kecamatans' => Kecamatan::with('kelurahans')->orderBy('name')->get()]);
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
            'role' => 'citizen',
        ]);
        CitizenProfile::create(['user_id' => $user->id, 'nik' => $request->string('nik')->toString(), 'kelurahan_id' => $request->integer('kelurahan_id')]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang di Portal Surakarta.');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home');
    }
}
