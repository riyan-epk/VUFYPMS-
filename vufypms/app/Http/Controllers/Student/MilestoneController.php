<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\TeamMilestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilestoneController extends Controller
{
    private function getTeam()
    {
        $member = TeamMember::where('student_id', Auth::id())->where('status', 'active')->first();
        return $member ? $member->team : null;
    }

    public function index()
    {
        $team = $this->getTeam();
        if (!$team) return redirect()->route('student.team.index')->with('error', 'Join a team first.');

        $milestones = $team->teamMilestones()
            ->with('milestone')
            ->orderBy('created_at')
            ->get()
            ->sortBy('milestone.order_index');

        $total     = $milestones->count();
        $completed = $milestones->where('status', 'completed')->count();
        $progress  = $total > 0 ? round(($completed / $total) * 100) : 0;

        return view('student.milestones.index', compact('team', 'milestones', 'total', 'completed', 'progress'));
    }

    public function update(Request $request, int $id)
    {
        $team      = $this->getTeam();
        $milestone = TeamMilestone::where('team_id', $team->id)->findOrFail($id);

        $request->validate([
            'status'           => ['required', 'in:pending,in_progress,completed'],
            'completion_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $data = ['status' => $request->status, 'completion_notes' => $request->completion_notes];
        if ($request->status === 'completed') {
            $data['completed_at'] = now();
        }

        $milestone->update($data);

        return back()->with('success', 'Milestone status updated.');
    }
}
