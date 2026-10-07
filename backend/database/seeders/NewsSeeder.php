<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => 'Wali Kota Respati Dorong UMKM Solo Naik Kelas dan Perluas Pasar Digital', 'excerpt' => 'Pemerintah Kota Surakarta terus mendorong pelaku UMKM untuk meningkatkan kualitas produk dan memperluas pasar digital.', 'body' => 'Pemerintah Kota Surakarta memperkuat kolaborasi dan pendampingan bagi pelaku UMKM agar semakin siap bersaing di pasar digital.', 'published_at' => now()->subDays(1)],
            ['title' => 'Pemkot Surakarta Mulai Digitalisasi Pembayaran Parkir dengan QRIS', 'excerpt' => 'Digitalisasi pembayaran parkir menjadi langkah baru untuk meningkatkan kualitas pelayanan kepada masyarakat.', 'body' => 'Pembayaran parkir dengan QRIS memberikan pilihan transaksi yang praktis, cepat, dan transparan bagi warga.', 'published_at' => now()->subDays(3)],
            ['title' => 'Wawali Astrid Hadiri Dies Natalis ke-28 UNSA', 'excerpt' => 'Kegiatan Dies Natalis menjadi momentum memperkuat kolaborasi pendidikan dan pembangunan Kota Surakarta.', 'body' => 'Pemerintah Kota Surakarta terus membuka ruang kolaborasi dengan perguruan tinggi dan berbagai elemen masyarakat.', 'published_at' => now()->subDays(5)],
        ];

        foreach ($items as $item) {
            News::updateOrCreate(['slug' => Str::slug($item['title'])], $item + ['is_published' => true]);
        }
    }
}
