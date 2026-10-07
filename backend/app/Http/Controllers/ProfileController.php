<?php

namespace App\Http\Controllers;

use App\Models\CitizenProfile;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['profile' => $request->user()->citizenProfile, 'kecamatans' => Kecamatan::with('kelurahans')->orderBy('name')->get()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate(['name'=>['required','string','max:120'],'email'=>['required','email','max:255','unique:users,email,'.$user->id],'nik'=>['required','digits:16','unique:citizen_profiles,nik,'.$user->citizenProfile?->id],'phone'=>['nullable','string','max:30'],'address'=>['nullable','string','max:500'],'kelurahan_id'=>['nullable','exists:kelurahans,id'],'current_password'=>['nullable','required_with:password','current_password'],'password'=>['nullable','confirmed','min:8']]);
        if (!empty($data['password'])) $data['password'] = Hash::make($data['password']); else unset($data['password']); unset($data['current_password']);
        $user->update(['name'=>$data['name'],'email'=>$data['email']] + (isset($data['password']) ? ['password'=>$data['password']] : []));
        CitizenProfile::updateOrCreate(['user_id'=>$user->id], ['kelurahan_id'=>$data['kelurahan_id'] ?? null,'nik'=>$data['nik'],'phone'=>$data['phone'] ?? null,'address'=>$data['address'] ?? null]);
        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
