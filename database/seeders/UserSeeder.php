<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::first();

        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $stafRole = Role::where('name', 'staf')->first();
        $kepalaRole = Role::where('name', 'kepala')->first();
        $kaprodiRole = Role::where('name', 'kaprodi')->first();

        $userMahasiswa = User::create([
            'name' => 'Mahasiswa Demo',
            'email' => 'mahasiswa@demo.test',
            'password' => Hash::make('password'),
            'student_id' => $student?->id,
        ]);

        if ($mahasiswaRole) {
            $userMahasiswa->roles()->sync([$mahasiswaRole->id]);
        }

        $userStaf = User::create([
            'name' => 'Staf Perpustakaan',
            'email' => 'staf@demo.test',
            'password' => Hash::make('password'),
        ]);

        if ($stafRole) {
            $userStaf->roles()->sync([$stafRole->id]);
        }

        $userKepala = User::create([
            'name' => 'Kepala Perpustakaan',
            'email' => 'kepala@demo.test',
            'password' => Hash::make('password'),
        ]);

        if ($kepalaRole) {
            $userKepala->roles()->sync([$kepalaRole->id]);
        }

        $userKaprodi = User::create([
            'name' => 'Kaprodi',
            'email' => 'kaprodi@demo.test',
            'password' => Hash::make('password'),
        ]);

        if ($kaprodiRole) {
            $userKaprodi->roles()->sync([$kaprodiRole->id]);
        }

        $ti = Department::where('name', 'Teknik Informatika')->first();
        if ($ti) {
            $userKaprodi->departments()->syncWithoutDetaching([
                $ti->id => ['role_in_department' => 'kaprodi'],
            ]);
        }

        $extraUsers = User::factory(6)->create();
        if ($mahasiswaRole) {
            foreach ($extraUsers as $extra) {
                $extra->roles()->sync([$mahasiswaRole->id]);
            }
        }
    }
}
