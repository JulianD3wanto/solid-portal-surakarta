<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([DocumentTypeSeeder::class, NewsSeeder::class, RegionSeeder::class]);

        User::updateOrCreate(
            ['email' => 'warga@example.com'],
            ['name' => 'Warga Demo', 'password' => 'password', 'role' => 'citizen', 'email_verified_at' => now()],
        );

        User::updateOrCreate(
            ['email' => 'petugas@example.com'],
            ['name' => 'Petugas Kelurahan', 'password' => 'password', 'role' => 'kelurahan_officer', 'email_verified_at' => now()],
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrator DMLS', 'password' => 'password', 'role' => 'admin', 'email_verified_at' => now()],
        );
    }
}
