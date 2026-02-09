<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Department::insert([
        //     ['name' => 'Teknik Sipil', 'code' => 'SPL', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Ilmu Hukum', 'code' => 'HKM', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Manajemen', 'code' => 'MNJ', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Pendidikan Bahasa Inggris', 'code' => 'PBI', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Administrasi Publik', 'code' => 'SFM', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Pertanian', 'code' => 'PTN', 'created_at' => now(), 'updated_at' => now()],
        // ]);
        // Role::insert([
        //     ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'kepala', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'kaprodi', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Pendstaff', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Pertanian', 'created_at' => now(), 'updated_at' => now()],
        // ]);
        $userStaf = User::create([
            'name' => 'Staf Perpustakaan',
            'email' => 'staf@demo.test',
            'password' => Hash::make('password'),
        ]);

    }
}
