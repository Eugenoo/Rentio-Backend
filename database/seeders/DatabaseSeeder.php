<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 x god
        User::factory()->god()->create([
            'email' => 'god@example.com',
        ]);
        // 2 x admin
        User::factory()->admin()->count(2)->create();

        // rest - random 7 users
        User::factory()->count(7)->create();
    }
}
