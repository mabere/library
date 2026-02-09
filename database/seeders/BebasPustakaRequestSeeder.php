<?php

namespace Database\Seeders;

use App\Models\BebasPustakaRequest;
use App\Models\Letter;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class BebasPustakaRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $stafRole = Role::where('name', 'staf')->first();
        $kepalaRole = Role::where('name', 'kepala')->first();

        $mahasiswa = $mahasiswaRole
            ? User::whereHas('roles', fn ($q) => $q->where('roles.id', $mahasiswaRole->id))->first()
            : null;
        $staf = $stafRole
            ? User::whereHas('roles', fn ($q) => $q->where('roles.id', $stafRole->id))->first()
            : null;
        $kepala = $kepalaRole
            ? User::whereHas('roles', fn ($q) => $q->where('roles.id', $kepalaRole->id))->first()
            : null;

        if (!$mahasiswa || !$staf || !$kepala) {
            return;
        }

        $student1 = Student::where('nim', '20210001')->first();
        $student2 = Student::where('nim', '20210002')->first();
        $student3 = Student::where('nim', '20210003')->first();
        $letterBebas = Letter::where('letter_type', 'bebas_pustaka')->where('status', 'aktif')->first();

        BebasPustakaRequest::create([
            'user_id' => $mahasiswa->id,
            'student_id' => $student1?->id,
            'nim' => $student1?->nim ?? '20219999',
            'nama' => $student1?->nama ?? 'Mahasiswa Dummy',
            'prodi' => $student1?->department?->name ?? 'Teknik Informatika',
            'status' => 'diajukan',
            'submitted_at' => now()->subDays(1),
        ]);

        BebasPustakaRequest::create([
            'user_id' => $mahasiswa->id,
            'student_id' => $student2?->id,
            'nim' => $student2?->nim ?? '20219998',
            'nama' => $student2?->nama ?? 'Mahasiswa Dummy 2',
            'prodi' => $student2?->department?->name ?? 'Sistem Informasi',
            'status' => 'diverifikasi_staf',
            'submitted_at' => now()->subDays(4),
            'has_fine' => false,
            'verified_by' => $staf->id,
            'verified_at' => now()->subDays(3),
        ]);

        BebasPustakaRequest::create([
            'user_id' => $mahasiswa->id,
            'student_id' => $student3?->id,
            'nim' => $student3?->nim ?? '20219997',
            'nama' => $student3?->nama ?? 'Mahasiswa Dummy 3',
            'prodi' => $student3?->department?->name ?? 'Manajemen',
            'status' => 'ditolak_staf',
            'submitted_at' => now()->subDays(6),
            'has_fine' => true,
            'fine_note' => 'Denda keterlambatan',
            'rejection_note' => 'Masih ada tanggungan denda.',
            'verified_by' => $staf->id,
            'verified_at' => now()->subDays(5),
        ]);

        if ($letterBebas) {
            BebasPustakaRequest::create([
                'user_id' => $mahasiswa->id,
                'student_id' => $student1?->id,
                'letter_id' => $letterBebas->id,
                'nim' => $student1?->nim ?? '20219996',
                'nama' => $student1?->nama ?? 'Mahasiswa Dummy 4',
                'prodi' => $student1?->department?->name ?? 'Teknik Informatika',
                'status' => 'disetujui_kepala',
                'submitted_at' => now()->subDays(9),
                'has_fine' => false,
                'verified_by' => $staf->id,
                'verified_at' => now()->subDays(8),
                'approved_by' => $kepala->id,
                'approved_at' => now()->subDays(7),
            ]);
        }
    }
}
