<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cards')->insert([
            ['created_at' => now()->subDays(5), 'updated_at' => now()->subDays(5)],
            ['created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2)],
        ]);
    }
}
