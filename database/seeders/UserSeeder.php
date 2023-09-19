<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Markieh Mark',
            'email' => 'markieh@member.com'
        ]);

        User::factory()->create([
            'name' => 'Davidoo',
            'email' => 'david@member.com'
        ]);

        User::factory()->create([
            'name' => 'Maximus',
            'email' => 'maxi@instructor.com',
            'role' => 'instructor'
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin'
        ]);

        User::factory()->count(10)->create();

        User::factory()->count(10)->create([
            'role' => 'instructor'
        ]);
    }
}