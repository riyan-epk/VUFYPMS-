<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $teams = Team::where('supervisor_id', $user->id)->with('members.student')->get();

        $evaluations = Evaluation::where('evaluator_id', $user->id)
            ->with('team.members.student')
            ->orderByDesc('evaluation_date')
            ->paginate(10);

        return view('supervisor.evaluations.index', compact('evaluations', 'teams'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'team_id'         => ['required', 'exists:teams,id'],
            'type'            => ['required', 'in:proposal_defense,progress_review,final_defense'],
            'marks'           => ['required', 'numeric', 'min:0', 'max:100'],
            'max_marks'       => ['required', 'numeric', 'min:1'],
            'remarks'         => ['nullable', 'string', 'max:2000'],
            'recommendations' => ['nullable', 'string', 'max:2000'],
            'evaluation_date' => ['required', 'date'],
        ]);

        Team::where('supervisor_id', $user->id)->findOrFail($request->team_id);

        $evaluation = Evaluation::create([
            'team_id'         => $request->team_id,
            'evaluator_id'    => $user->id,
            'type'            => $request->type,
            'marks'           => $request->marks,
            'max_marks'       => $request->max_marks,
            'remarks'         => $request->remarks,
            'recommendations' => $request->recommendations,
            'evaluation_date' => $request->evaluation_date,
        ]);

        $team = Team::find($request->team_id);
        foreach ($team->members as $member) {
            Notification::send(
                $member->student_id,
                'Evaluation Added',
                "Your supervisor has entered marks for " . ucfirst(str_replace('_', ' ', $request->type)) . ". Marks: {$request->marks}/{$request->max_marks}",
                'success',
                route('student.evaluations.index')
            );
        }

        return back()->with('success', 'Evaluation recorded successfully.');
    }

    public function edit(int $id)
    {
        $user       = Auth::user();
        $evaluation = Evaluation::where('evaluator_id', $user->id)->with('team')->findOrFail($id);
        $teams      = Team::where('supervisor_id', $user->id)->get();
        return view('supervisor.evaluations.edit', compact('evaluation', 'teams'));
    }

    public function update(Request $request, int $id)
    {
        $user       = Auth::user();
        $evaluation = Evaluation::where('evaluator_id', $user->id)->findOrFail($id);

        $request->validate([
            'marks'           => ['required', 'numeric', 'min:0', 'max:100'],
            'max_marks'       => ['required', 'numeric', 'min:1'],
            'remarks'         => ['nullable', 'string', 'max:2000'],
            'recommendations' => ['nullable', 'string', 'max:2000'],
            'evaluation_date' => ['required', 'date'],
        ]);

        $evaluation->update($request->only('marks', 'max_marks', 'remarks', 'recommendations', 'evaluation_date'));
        return redirect()->route('supervisor.evaluations.index')->with('success', 'Evaluation updated.');
    }
}
