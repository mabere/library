<?php

namespace Database\Seeders;

use App\Models\Letter;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::orderBy('id')->take(3)->get();

        if ($students->count() < 3) {
            return;
        }

        Letter::insert([
            [
                'student_id' => $students[0]->id,
                'letter_type' => 'bebas_pustaka',
                'letter_number' => 'BP-001/2026',
                'status' => 'aktif',
                'file_path' => null,
                'has_fine' => false,
                'fine_note' => null,
                'verified_by' => 'Staf Perpustakaan',
                'verified_at' => now()->subDays(10),
                'token' => (string) Str::uuid(),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'student_id' => $students[1]->id,
                'letter_type' => 'penyerahan_skripsi',
                'letter_number' => 'PS-002/2026',
                'status' => 'aktif',
                'file_path' => null,
                'has_fine' => false,
                'fine_note' => null,
                'verified_by' => 'Staf Perpustakaan',
                'verified_at' => now()->subDays(7),
                'token' => (string) Str::uuid(),
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'student_id' => $students[2]->id,
                'letter_type' => 'bebas_pustaka',
                'letter_number' => 'BP-003/2026',
                'status' => 'dibatalkan',
                'file_path' => null,
                'has_fine' => true,
                'fine_note' => 'Denda keterlambatan',
                'verified_by' => 'Staf Perpustakaan',
                'verified_at' => now()->subDays(3),
                'token' => (string) Str::uuid(),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ]);
    }
}
