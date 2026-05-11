<?php

namespace Database\Seeders;

use App\Models\Milestone;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class MilestoneSeeder extends Seeder
{
    public function run(): void
    {
        $semester = Semester::where('is_active', true)->first();
        if (!$semester) {
            $this->command->warn('No active semester found. Skipping milestones seeder.');
            return;
        }

        $milestones = [
            ['name' => 'Team Formation', 'description' => 'Form your project team (2-4 members) and register in the system.', 'due_date' => now()->addDays(14)->toDateString(), 'order_index' => 1],
            ['name' => 'Proposal Submission', 'description' => 'Submit your project proposal for supervisor review.', 'due_date' => now()->addDays(28)->toDateString(), 'order_index' => 2],
            ['name' => 'SRS Document', 'description' => 'Submit the Software Requirements Specification (SRS) document.', 'due_date' => now()->addDays(56)->toDateString(), 'order_index' => 3],
            ['name' => 'Design Document', 'description' => 'Submit the system design and architecture document.', 'due_date' => now()->addDays(84)->toDateString(), 'order_index' => 4],
            ['name' => 'Progress Review (Mid)', 'description' => 'Mid-semester progress review and evaluation by the supervisor.', 'due_date' => now()->addDays(105)->toDateString(), 'order_index' => 5],
            ['name' => 'Progress Report', 'description' => 'Submit the progress report documenting development status.', 'due_date' => now()->addDays(126)->toDateString(), 'order_index' => 6],
            ['name' => 'Final Report', 'description' => 'Submit the complete final project report.', 'due_date' => now()->addDays(154)->toDateString(), 'order_index' => 7],
            ['name' => 'Final Defense', 'description' => 'Final project defense and presentation to the evaluation panel.', 'due_date' => now()->addDays(168)->toDateString(), 'order_index' => 8],
        ];

        foreach ($milestones as $m) {
            Milestone::firstOrCreate(
                ['semester_id' => $semester->id, 'name' => $m['name']],
                array_merge($m, ['semester_id' => $semester->id])
            );
        }

        $this->command->info('Milestones seeded for semester: ' . $semester->name);
    }
}
