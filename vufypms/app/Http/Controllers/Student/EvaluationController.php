<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
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

        $evaluations = $team->evaluations()->with('evaluator')->get();
        return view('student.evaluations.index', compact('team', 'evaluations'));
    }

    public function presentations()
    {
        $team = $this->getTeam();
        if (!$team) return redirect()->route('student.team.index')->with('error', 'Join a team first.');

        $presentations = $team->presentations()->get();
        $meetings      = $team->meetings()->with('supervisor')->get();
        return view('student.presentations.index', compact('team', 'presentations', 'meetings'));
    }
}
