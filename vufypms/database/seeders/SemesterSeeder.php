<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        Semester::firstOrCreate(
            ['name' => 'Spring 2025'],
            [
                'start_date'     => '2025-01-15',
                'end_date'       => '2025-06-30',
                'proposal_start' => '2025-01-20',
                'proposal_end'   => '2025-02-15',
                'is_active'      => false,
            ]
        );

        Semester::firstOrCreate(
            ['name' => 'Fall 2025'],
            [
                'start_date'     => '2025-08-01',
                'end_date'       => '2026-01-31',
                'proposal_start' => '2025-08-10',
                'proposal_end'   => '2025-09-10',
                'is_active'      => true,
            ]
        );
    }
}
