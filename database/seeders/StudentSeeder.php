<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ti = Department::where('name', 'Teknik Informatika')->first();
        $si = Department::where('name', 'Sistem Informasi')->first();
        $mn = Department::where('name', 'Manajemen')->first();
        $pbi = Department::where('name', 'Pendidikan Bahasa Inggris')->first();

        Student::insert([
            [
                'nim' => '20210001',
                'nama' => 'Andi Pratama',
                'department_id' => $ti?->id,
                'judul_skripsi' => 'Sistem Rekomendasi Buku Perpustakaan',
                'tahun_lulus' => 2025,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '20210002',
                'nama' => 'Siti Rahma',
                'department_id' => $si?->id,
                'judul_skripsi' => 'Analisis Data Peminjaman Buku',
                'tahun_lulus' => 2025,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '20210003',
                'nama' => 'Budi Santoso',
                'department_id' => $mn?->id,
                'judul_skripsi' => 'Manajemen Layanan Perpustakaan',
                'tahun_lulus' => 2024,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '20210004',
                'nama' => 'Dewi Lestari',
                'department_id' => $pbi?->id,
                'judul_skripsi' => 'Literasi Digital Mahasiswa',
                'tahun_lulus' => 2024,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '20210005',
                'nama' => 'Rizky Maulana',
                'department_id' => $mn?->id,
                'judul_skripsi' => 'Audit Koleksi Perpustakaan',
                'tahun_lulus' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
