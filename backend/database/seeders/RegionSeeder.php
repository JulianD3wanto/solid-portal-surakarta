<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            'Banjarsari' => ['Banyువanyar','Banjarsari','Gilingan','Joglo','Kadipiro','Keprabon','Kestalan','Ketelan','Manahan','Mangkubumen','Nusukan','Punggawan','Setabelan','Sumber','Timuran'],
            'Jebres' => ['Gandekan','Jagalan','Jebres','Kepatihan Kulon','Kepatihan Wetan','Mojosongo','Pucang Sawit','Purwodiningratan','Sewu','Sudiroprajan','Tegalharjo'],
            'Laweyan' => ['Bumi','Jajar','Karangasem','Kerten','Laweyan','Pajang','Panularan','Penumping','Purwosari','Sondakan','Sriwedari'],
            'Pasar Kliwon' => ['Baluwarti','Gajahan','Joyosuran','Kampung Baru','Kauman','Kedung Lumbu','Mojo','Pasar Kliwon','Sangkrah','Semanggi'],
            'Serengan' => ['Danukusuman','Jayengan','Joyotakan','Kemlayan','Kratonan','Serengan','Tipes'],
        ];

        foreach ($regions as $kecamatanName => $kelurahanNames) {
            $kecamatan = Kecamatan::updateOrCreate(['name' => $kecamatanName]);
            foreach ($kelurahanNames as $name) {
                $name = str_replace('Banyువanyar', 'Banyuanyar', $name);
                $kelurahan = $kecamatan->kelurahans()->updateOrCreate(['name' => $name]);
                $slug = Str::slug($name);
                $user = User::updateOrCreate(
                    ['email' => 'admin.'.$slug.'@surakarta.go.id'],
                    ['name' => 'Admin Kelurahan '.$name, 'password' => 'password', 'role' => 'kelurahan_officer', 'email_verified_at' => now()],
                );
                $user->citizenProfile()->updateOrCreate(['user_id' => $user->id], ['kelurahan_id' => $kelurahan->id]);
            }
        }
    }
}
