<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $teams = Team::where('supervisor_id', $user->id)
            ->with('proposal.domain', 'members.student', 'semester', 'teamMilestones.milestone')
            ->paginate(10);

        return view('supervisor.teams.index', compact('teams'));
    }

    public function show(int $id)
    {
        $user = Auth::user();
        $team = Team::where('supervisor_id', $user->id)
            ->with('proposal.domain', 'members.student', 'semester', 'teamMilestones.milestone', 'documents.uploader', 'evaluations.evaluator', 'meetings')
            ->findOrFail($id);

        $milestones = $team->teamMilestones()->with('milestone')->get()->sortBy('milestone.order_index');
        $completedMilestones = $milestones->where('status', 'completed')->count();
        $progress = $milestones->count() > 0 ? round(($completedMilestones / $milestones->count()) * 100) : 0;

        return view('supervisor.teams.show', compact('team', 'milestones', 'progress', 'completedMilestones'));
    }

    public function documents(int $id)
    {
        $user = Auth::user();
        $team = Team::where('supervisor_id', $user->id)->findOrFail($id);

        $documents = Document::where('team_id', $team->id)
            ->where('status', 'active')
            ->with('uploader')
            ->latest()
            ->get()
            ->groupBy('type');

        return view('supervisor.teams.documents', compact('team', 'documents'));
    }

    public function downloadDocument(int $id, int $docId)
    {
        $user     = Auth::user();
        $team     = Team::where('supervisor_id', $user->id)->findOrFail($id);
        $document = Document::where('team_id', $team->id)->findOrFail($docId);

        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($document->file_path, $document->original_name);
    }
}
