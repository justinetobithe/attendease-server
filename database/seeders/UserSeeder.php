<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            ['student_number' => '20235235', 'first_name' => 'John Kane', 'last_name' => 'Lorchano'],
            ['student_number' => '20234872', 'first_name' => 'Mica', 'last_name' => 'Gumba'],
            ['student_number' => '20234709', 'first_name' => 'Abner', 'last_name' => 'Sumadia'],
            ['student_number' => '20235247', 'first_name' => 'Jaycee Cody', 'last_name' => 'Asna'],
            ['student_number' => '20235026', 'first_name' => 'Josh', 'last_name' => 'Sabio'],
            ['student_number' => '20235192', 'first_name' => 'Junarpaul', 'last_name' => 'Magistrado'],
            ['student_number' => '20235073', 'first_name' => 'Raven', 'last_name' => 'Davin'],
            ['student_number' => '20235311', 'first_name' => 'Joemar Philipp', 'last_name' => 'Nisnisan'],
        ];

        foreach ($students as $student) {
            DB::table('users')->insert([
                'first_name' => $student['first_name'],
                'last_name' => $student['last_name'],
                'email' => strtolower(str_replace(' ', '', $student['first_name'])) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Guard',
            'last_name' => 'User',
            'email' => 'guard@example.com',
            'password' => Hash::make('password'),
            'role' => 'guard',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
