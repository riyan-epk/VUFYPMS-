<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@vu.edu.pk'],
            [
                'name'        => 'System Administrator',
                'vu_id'       => 'ADMIN001',
                'role'        => 'admin',
                'password'    => 'password',
                'department'  => 'IT Department',
                'designation' => 'System Administrator',
                'is_active'   => true,
            ]
        );

        $supervisors = [
            ['name' => 'Dr. Ahmad Raza', 'email' => 'supervisor@vu.edu.pk', 'vu_id' => 'SUP001', 'designation' => 'Associate Professor', 'department' => 'Computer Science'],
            ['name' => 'Dr. Sara Khan', 'email' => 'sara.khan@vu.edu.pk', 'vu_id' => 'SUP002', 'designation' => 'Assistant Professor', 'department' => 'Software Engineering'],
            ['name' => 'Dr. Bilal Ahmed', 'email' => 'bilal.ahmed@vu.edu.pk', 'vu_id' => 'SUP003', 'designation' => 'Professor', 'department' => 'Computer Science'],
        ];

        foreach ($supervisors as $sv) {
            User::firstOrCreate(
                ['email' => $sv['email']],
                array_merge($sv, ['role' => 'supervisor', 'password' => 'password', 'is_active' => true])
            );
        }

        $students = [
            ['name' => 'Ali Hassan', 'email' => 'student@vu.edu.pk', 'vu_id' => 'BC200400001'],
            ['name' => 'Ayesha Siddiqui', 'email' => 'ayesha@vu.edu.pk', 'vu_id' => 'BC200400002'],
            ['name' => 'Umar Farooq', 'email' => 'umar@vu.edu.pk', 'vu_id' => 'BC200400003'],
            ['name' => 'Fatima Malik', 'email' => 'fatima@vu.edu.pk', 'vu_id' => 'BC200400004'],
            ['name' => 'Hamza Sheikh', 'email' => 'hamza@vu.edu.pk', 'vu_id' => 'BC210400005'],
            ['name' => 'Zainab Noor', 'email' => 'zainab@vu.edu.pk', 'vu_id' => 'BC210400006'],
        ];

        foreach ($students as $s) {
            User::firstOrCreate(
                ['email' => $s['email']],
                array_merge($s, ['role' => 'student', 'password' => 'password', 'is_active' => true])
            );
        }

        $this->command->info('Users seeded: 1 admin, 3 supervisors, 6 students.');
        $this->command->info('Default password for all: password');
    }
}
