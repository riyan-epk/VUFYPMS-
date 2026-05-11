<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProjectDomainSeeder::class,
            SemesterSeeder::class,
            UserSeeder::class,
            MilestoneSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
