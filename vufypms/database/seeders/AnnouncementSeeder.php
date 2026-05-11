<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        $announcements = [
            [
                'title'       => 'Welcome to FYP Semester Fall 2025',
                'content'     => 'The Final Year Project program for Fall 2025 is now open. Students are required to form their teams by the deadline and submit project proposals through this portal. Please read the guidelines carefully before proceeding.',
                'type'        => 'general',
                'is_public'   => true,
                'target_role' => 'all',
                'published_at'=> now(),
            ],
            [
                'title'       => 'Team Formation Deadline — August 24, 2025',
                'content'     => 'All students must form their FYP teams (2-4 members) and register in the system by August 24, 2025. Teams not formed by this deadline will not be eligible for supervisor assignment.',
                'type'        => 'deadline',
                'is_public'   => true,
                'target_role' => 'student',
                'published_at'=> now(),
                'expires_at'  => now()->addDays(30),
            ],
            [
                'title'       => 'Proposal Submission Window Open',
                'content'     => 'The proposal submission window is now open. Students with confirmed teams may submit their project proposals through the portal. The deadline for proposal submission is September 10, 2025.',
                'type'        => 'deadline',
                'is_public'   => true,
                'target_role' => 'student',
                'published_at'=> now(),
                'expires_at'  => now()->addDays(45),
            ],
            [
                'title'       => 'Supervisor Assignment Notice for Supervisors',
                'content'     => 'Team assignments will be communicated within 5 working days after the team formation deadline. Supervisors are requested to review assigned team proposals promptly and provide feedback within 7 days of submission.',
                'type'        => 'general',
                'is_public'   => false,
                'target_role' => 'supervisor',
                'published_at'=> now(),
            ],
            [
                'title'       => 'FYP Evaluation Schedule — Fall 2025',
                'content'     => 'The evaluation schedule for Fall 2025 is as follows: Proposal Defense (October 2025), Mid-Semester Progress Review (November 2025), Final Defense (January 2026). All evaluations will be conducted by the assigned supervisor and a panel.',
                'type'        => 'evaluation',
                'is_public'   => true,
                'target_role' => 'all',
                'published_at'=> now(),
            ],
        ];

        foreach ($announcements as $data) {
            Announcement::create(array_merge($data, ['created_by' => $admin->id]));
        }

        $this->command->info('Announcements seeded.');
    }
}
