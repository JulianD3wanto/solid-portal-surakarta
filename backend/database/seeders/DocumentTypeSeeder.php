<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['slug' => 'surat-keterangan-tidak-mampu', 'name' => 'Surat Keterangan Tidak Mampu', 'description' => 'Ajukan surat keterangan tidak mampu secara digital untuk kebutuhan administrasi pendidikan, kesehatan, dan sosial.', 'requirements' => ['Kartu Keluarga', 'KTP pemohon', 'Surat pengantar RT/RW'], 'processing_time' => '2–3 hari kerja', 'icon' => 'users'],
            ['slug' => 'surat-nikah', 'name' => 'Surat Nikah', 'description' => 'Siapkan dokumen administrasi pernikahan dengan alur pengajuan yang mudah dan transparan.', 'requirements' => ['KTP calon pengantin', 'Kartu Keluarga', 'Surat pengantar kelurahan'], 'processing_time' => '3–5 hari kerja', 'icon' => 'heart'],
            ['slug' => 'surat-pengantar-skck', 'name' => 'Surat Pengantar SKCK', 'description' => 'Dapatkan surat pengantar untuk pembuatan SKCK tanpa perlu mengulang pengisian data.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga', 'Pas foto'], 'processing_time' => '1–2 hari kerja', 'icon' => 'briefcase'],
            ['slug' => 'surat-beda-nama', 'name' => 'Beda Nama', 'description' => 'Surat keterangan beda nama untuk kebutuhan administrasi warga.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga', 'Dokumen pendukung'], 'processing_time' => '2–3 hari kerja', 'icon' => 'identity'],
            ['slug' => 'surat-belum-bekerja', 'name' => 'Belum Bekerja', 'description' => 'Penerbitan surat keterangan belum bekerja.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga'], 'processing_time' => '2–3 hari kerja', 'icon' => 'briefcase'],
            ['slug' => 'surat-belum-memiliki-rumah', 'name' => 'Belum Memiliki Rumah', 'description' => 'Penerbitan surat keterangan belum memiliki rumah.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga', 'Surat pernyataan'], 'processing_time' => '2–3 hari kerja', 'icon' => 'home'],
            ['slug' => 'surat-belum-pernah-menikah', 'name' => 'Belum Pernah Menikah', 'description' => 'Penerbitan surat keterangan belum menikah atau belum pernah menikah.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga'], 'processing_time' => '2–3 hari kerja', 'icon' => 'heart'],
            ['slug' => 'surat-domisili-keluar', 'name' => 'Domisili Keluar', 'description' => 'Surat pengantar keterangan domisili keluar.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga', 'Surat pengantar RT/RW'], 'processing_time' => '2–3 hari kerja', 'icon' => 'exit'],
            ['slug' => 'surat-domisili-masuk', 'name' => 'Domisili Masuk', 'description' => 'Surat keterangan domisili masuk.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga', 'Bukti tempat tinggal'], 'processing_time' => '2–3 hari kerja', 'icon' => 'enter'],
            ['slug' => 'surat-domisili-usaha', 'name' => 'Domisili Usaha', 'description' => 'Penerbitan surat keterangan domisili usaha.', 'requirements' => ['KTP pemohon', 'Bukti usaha', 'Bukti tempat usaha'], 'processing_time' => '3–5 hari kerja', 'icon' => 'building'],
            ['slug' => 'ijin-kegiatan-keramaian', 'name' => 'Ijin Kegiatan/Keramaian', 'description' => 'Penerbitan surat rekomendasi izin kegiatan atau keramaian.', 'requirements' => ['KTP pemohon', 'Proposal kegiatan', 'Surat pengantar'], 'processing_time' => '3–5 hari kerja', 'icon' => 'megaphone'],
            ['slug' => 'ijin-penutupan-jalan', 'name' => 'Ijin Penutupan Jalan', 'description' => 'Surat keterangan pengajuan izin penutupan jalan lingkungan.', 'requirements' => ['KTP pemohon', 'Proposal kegiatan', 'Denah lokasi'], 'processing_time' => '3–5 hari kerja', 'icon' => 'stop'],
            ['slug' => 'surat-bepergian', 'name' => 'Keterangan Bepergian', 'description' => 'Surat keterangan bepergian untuk keperluan melangsungkan pernikahan.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga', 'Surat pengantar'], 'processing_time' => '2–3 hari kerja', 'icon' => 'plane'],
            ['slug' => 'surat-janda-duda', 'name' => 'Keterangan Janda/Duda', 'description' => 'Penerbitan surat keterangan janda atau duda.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga', 'Akta cerai atau kematian'], 'processing_time' => '2–3 hari kerja', 'icon' => 'person'],
            ['slug' => 'surat-kehilangan', 'name' => 'Keterangan Kehilangan', 'description' => 'Penerbitan surat keterangan kehilangan.', 'requirements' => ['KTP pemohon', 'Keterangan kehilangan'], 'processing_time' => '1–2 hari kerja', 'icon' => 'alert'],
            ['slug' => 'surat-keterangan-kematian', 'name' => 'Keterangan Kematian', 'description' => 'Penerbitan surat keterangan kematian.', 'requirements' => ['KTP almarhum', 'Kartu Keluarga', 'Surat keterangan kematian'], 'processing_time' => '2–3 hari kerja', 'icon' => 'file'],
            ['slug' => 'surat-keterangan-penghasilan', 'name' => 'Keterangan Penghasilan', 'description' => 'Penerbitan surat keterangan penghasilan.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga', 'Surat pernyataan penghasilan'], 'processing_time' => '2–3 hari kerja', 'icon' => 'money'],
            ['slug' => 'surat-keterangan-usaha', 'name' => 'Keterangan Usaha', 'description' => 'Penerbitan surat keterangan usaha.', 'requirements' => ['KTP pemohon', 'Bukti usaha', 'Foto usaha'], 'processing_time' => '3–5 hari kerja', 'icon' => 'store'],
            ['slug' => 'surat-wali-nikah', 'name' => 'Keterangan Wali Nikah', 'description' => 'Penerbitan surat keterangan wali nikah.', 'requirements' => ['KTP wali', 'Kartu Keluarga', 'Dokumen calon pengantin'], 'processing_time' => '3–5 hari kerja', 'icon' => 'couple'],
            ['slug' => 'nomor-induk-kesenian', 'name' => 'Nomor Induk Kesenian', 'description' => 'Surat pengantar nomor induk kesenian.', 'requirements' => ['KTP pemohon', 'Profil kesenian', 'Surat pengantar'], 'processing_time' => '3–5 hari kerja', 'icon' => 'music'],
            ['slug' => 'pengantar-skck', 'name' => 'Pengantar SKCK', 'description' => 'Penerbitan surat pengantar pembuatan surat keterangan catatan kepolisian.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga', 'Pas foto'], 'processing_time' => '1–2 hari kerja', 'icon' => 'shield'],
            ['slug' => 'pindah-agama-kepercayaan', 'name' => 'Pindah Agama/Kepercayaan', 'description' => 'Surat pengantar pindah agama dan kepercayaan.', 'requirements' => ['KTP pemohon', 'Kartu Keluarga', 'Surat pernyataan'], 'processing_time' => '3–5 hari kerja', 'icon' => 'globe'],
            ['slug' => 'rekomendasi-pembelian-bbm', 'name' => 'Rekomendasi Pembelian BBM', 'description' => 'Surat keterangan rekomendasi pembelian jenis BBM.', 'requirements' => ['KTP pemohon', 'Dokumen kendaraan', 'Surat pengantar'], 'processing_time' => '2–3 hari kerja', 'icon' => 'fuel'],
            ['slug' => 'surat-pernikahan-n1', 'name' => 'Surat Pernikahan N1', 'description' => 'Penerbitan surat pernikahan N1.', 'requirements' => ['KTP calon pengantin', 'Kartu Keluarga', 'Dokumen pernikahan'], 'processing_time' => '3–5 hari kerja', 'icon' => 'couple'],
        ];

        foreach ($services as $service) {
            DocumentType::updateOrCreate(['slug' => $service['slug']], $service);
        }
    }
}
