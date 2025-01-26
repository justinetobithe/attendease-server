<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            ['user_id' => 1, 'student_number' => '20235235', 'strand_id' => 1],
            ['user_id' => 2, 'student_number' => '20234872', 'strand_id' => 1],
            ['user_id' => 3, 'student_number' => '20234709', 'strand_id' => 1],
            ['user_id' => 4, 'student_number' => '20235247', 'strand_id' => 1],
            ['user_id' => 5, 'student_number' => '20235026', 'strand_id' => 1],
            ['user_id' => 6, 'student_number' => '20235192', 'strand_id' => 1],
            ['user_id' => 7, 'student_number' => '20235073', 'strand_id' => 1],
            ['user_id' => 8, 'student_number' => '20235311', 'strand_id' => 1],
        ];

        foreach ($students as $student) {
            DB::table('students')->insert([
                'user_id' => $student['user_id'],
                'student_number' => $student['student_number'],
                'strand_id' => $student['strand_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
