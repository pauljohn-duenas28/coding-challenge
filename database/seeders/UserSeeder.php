<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Paul John Duenas',
            'email' => 'pauljohn.duenas28@gmail.com',
            'password' => 'moderator123',
            'role' => 'moderator'
        ]);

        Employer::create([
            'name' => 'OneTeamAnywhere'
        ]);

        User::create([
            'name' => 'Test Employer',
            'email' => 'employer@gmail.com',
            'password' => 'employer123',
            'role' => 'employer',
            'employer_id' => 1
        ]);



        User::create([
            'name' => 'Test User',
            'email' => 'normaluser@gmail.com',
            'password' => 'normaluser123',
            'role' => 'normal'
        ]);
    }
}
