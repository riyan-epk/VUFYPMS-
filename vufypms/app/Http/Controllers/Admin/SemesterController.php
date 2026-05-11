<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\Semester;
use App\Models\Team;
use App\Models\TeamMilestone;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::withCount('teams', 'milestones')->latest()->paginate(10);
        return view('admin.semesters.index', compact('semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after:start_date'],
            'proposal_start' => ['nullable', 'date', 'after_or_equal:start_date'],
            'proposal_end'   => ['nullable', 'date', 'after:proposal_start'],
        ]);

        Semester::create($request->only('name', 'start_date', 'end_date', 'proposal_start', 'proposal_end'));
        return back()->with('success', 'Semester created successfully.');
    }

    public function update(Request $request, int $id)
    {
        $semester = Semester::findOrFail($id);
        $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after:start_date'],
            'proposal_start' => ['nullable', 'date'],
            'proposal_end'   => ['nullable', 'date'],
        ]);

        $semester->update($request->only('name', 'start_date', 'end_date', 'proposal_start', 'proposal_end'));
        return back()->with('success', 'Semester updated.');
    }

    public function activate(int $id)
    {
        Semester::where('is_active', true)->update(['is_active' => false]);
        $semester = Semester::findOrFail($id);
        $semester->update(['is_active' => true]);

        $teams = Team::where('semester_id', $semester->id)->get();
        $milestones = $semester->milestones;

        foreach ($teams as $team) {
            foreach ($milestones as $milestone) {
                TeamMilestone::firstOrCreate(
                    ['team_id' => $team->id, 'milestone_id' => $milestone->id],
                    ['status' => 'pending']
                );
            }
        }

        return back()->with('success', "Semester \"{$semester->name}\" is now active.");
    }

    public function destroy(int $id)
    {
        $semester = Semester::withCount('teams')->findOrFail($id);
        if ($semester->teams_count > 0) return back()->with('error', 'Cannot delete semester with existing teams.');
        $semester->delete();
        return back()->with('success', 'Semester deleted.');
    }
}
