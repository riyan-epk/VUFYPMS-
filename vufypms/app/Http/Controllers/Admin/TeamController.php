<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\Notification;
use App\Models\Presentation;
use App\Models\Semester;
use App\Models\Team;
use App\Models\TeamMilestone;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $query = Team::with('proposal', 'supervisor', 'semester', 'members');

        if ($request->semester_id) $query->where('semester_id', $request->semester_id);
        if ($request->status)      $query->where('status', $request->status);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('project_title', 'like', '%' . $request->search . '%');
            });
        }

        $teams      = $query->latest()->paginate(20)->withQueryString();
        $semesters  = Semester::orderByDesc('start_date')->get();
        $supervisors = User::where('role', 'supervisor')->where('is_active', true)->get();

        return view('admin.teams.index', compact('teams', 'semesters', 'supervisors'));
    }

    public function show(int $id)
    {
        $team = Team::with('proposal.domain', 'supervisor', 'semester', 'members.student', 'teamMilestones.milestone', 'documents', 'evaluations.evaluator', 'presentations', 'meetings.supervisor')->findOrFail($id);
        $supervisors = User::where('role', 'supervisor')->where('is_active', true)->get();
        return view('admin.teams.show', compact('team', 'supervisors'));
    }

    public function assignSupervisor(Request $request, int $id)
    {
        $team = Team::findOrFail($id);
        $request->validate([
            'supervisor_id' => ['required', 'exists:users,id'],
        ]);

        $supervisor = User::where('role', 'supervisor')->findOrFail($request->supervisor_id);
        $team->update(['supervisor_id' => $supervisor->id, 'status' => 'active']);

        $milestones = Milestone::where('semester_id', $team->semester_id)->get();
        foreach ($milestones as $milestone) {
            TeamMilestone::firstOrCreate(
                ['team_id' => $team->id, 'milestone_id' => $milestone->id],
                ['status' => 'pending']
            );
        }

        foreach ($team->members as $member) {
            Notification::send(
                $member->student_id,
                'Supervisor Assigned',
                "{$supervisor->name} has been assigned as your project supervisor.",
                'success',
                route('student.team.index')
            );
        }

        Notification::send(
            $supervisor->id,
            'New Team Assigned',
            "Team \"{$team->name}\" has been assigned to you for supervision.",
            'info',
            route('supervisor.teams.show', $team->id)
        );

        return back()->with('success', "Supervisor {$supervisor->name} assigned to team {$team->name}.");
    }

    public function archive(int $id)
    {
        $team = Team::findOrFail($id);
        $team->update(['status' => 'archived']);
        return back()->with('success', 'Team archived.');
    }

    public function schedulePresentation(Request $request)
    {
        $request->validate([
            'team_id'          => ['required', 'exists:teams,id'],
            'type'             => ['required', 'in:proposal_defense,progress_review,final_defense'],
            'scheduled_at'     => ['required', 'date'],
            'venue'            => ['nullable', 'string', 'max:255'],
            'online_link'      => ['nullable', 'url'],
            'panel_info'       => ['nullable', 'string', 'max:500'],
            'duration_minutes' => ['nullable', 'integer', 'min:10'],
        ]);

        $presentation = Presentation::create($request->only('team_id', 'type', 'scheduled_at', 'venue', 'online_link', 'panel_info', 'duration_minutes'));

        $team = Team::find($request->team_id);
        $typeLabel = $presentation->type_label;
        foreach ($team->members as $member) {
            Notification::send(
                $member->student_id,
                'Presentation Scheduled',
                "{$typeLabel} scheduled on " . $presentation->scheduled_at->format('M d, Y H:i'),
                'info',
                route('student.presentations.index')
            );
        }

        return back()->with('success', 'Presentation scheduled.');
    }

    public function updatePresentation(Request $request, int $id)
    {
        $presentation = Presentation::findOrFail($id);
        $request->validate(['status' => ['required', 'in:scheduled,completed,postponed,cancelled']]);
        $presentation->update($request->only('status', 'scheduled_at', 'venue', 'online_link'));
        return back()->with('success', 'Presentation updated.');
    }
}
