<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\Semester;
use App\Models\Team;
use App\Models\TeamMilestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index()
    {
        $semesters  = Semester::with('milestones')->get();
        $milestones = Milestone::with('semester')->orderBy('semester_id')->orderBy('order_index')->paginate(20);
        return view('admin.milestones.index', compact('milestones', 'semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'semester_id'  => ['required', 'exists:semesters,id'],
            'name'         => ['required', 'string', 'max:200'],
            'description'  => ['nullable', 'string', 'max:500'],
            'due_date'     => ['required', 'date'],
            'order_index'  => ['required', 'integer', 'min:1'],
        ]);

        $milestone = Milestone::create($request->only('semester_id', 'name', 'description', 'due_date', 'order_index'));

        $teams = Team::where('semester_id', $request->semester_id)->get();
        foreach ($teams as $team) {
            TeamMilestone::firstOrCreate(
                ['team_id' => $team->id, 'milestone_id' => $milestone->id],
                ['status' => 'pending']
            );
        }

        return back()->with('success', 'Milestone created and assigned to all teams in the semester.');
    }

    public function update(Request $request, int $id)
    {
        $milestone = Milestone::findOrFail($id);
        $request->validate([
            'name'        => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:500'],
            'due_date'    => ['required', 'date'],
            'order_index' => ['required', 'integer', 'min:1'],
        ]);
        $milestone->update($request->only('name', 'description', 'due_date', 'order_index'));
        return back()->with('success', 'Milestone updated.');
    }

    public function destroy(int $id)
    {
        $milestone = Milestone::findOrFail($id);
        TeamMilestone::where('milestone_id', $id)->delete();
        $milestone->delete();
        return back()->with('success', 'Milestone deleted.');
    }
}
