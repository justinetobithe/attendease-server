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
            ['student_number' => '20235235', 'strand_acronym' => 'TVL'],
            ['student_number' => '20234872', 'strand_acronym' => 'TVL'],
            ['student_number' => '20234709', 'strand_acronym' => 'TVL'],
            ['student_number' => '20235247', 'strand_acronym' => 'TVL'],
            ['student_number' => '20235026', 'strand_acronym' => 'TVL'],
            ['student_number' => '20235192', 'strand_acronym' => 'TVL'],
            ['student_number' => '20235073', 'strand_acronym' => 'TVL'],
            ['student_number' => '20235311', 'strand_acronym' => 'TVL'],
        ];

        foreach ($students as $student) {
            $user = DB::table('users')->where('email', strtolower(str_replace(' ', '', $student['student_number'])) . '@example.com')->first();
            $strand = DB::table('strands')->where('acronym', $student['strand_acronym'])->first();

            if ($user && $strand) {
                DB::table('students')->insert([
                    'user_id' => $user->id,
                    'student_number' => $student['student_number'],
                    'strand_id' => $strand->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
