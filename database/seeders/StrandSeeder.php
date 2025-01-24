<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $strands = [
            ['name' => 'Technical-Vocational-Livelihood', 'acronym' => 'TVL'],
            ['name' => 'Science, Technology, Engineering, and Mathematics', 'acronym' => 'STEM'],
            ['name' => 'Humanities and Social Sciences', 'acronym' => 'HUMSS'],
            ['name' => 'General Academic Strand', 'acronym' => 'GAS'],
            ['name' => 'Accountancy, Business, and Management', 'acronym' => 'ABM'],
        ];

        DB::table('strands')->insert($strands);
    }
}
