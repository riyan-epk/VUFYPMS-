<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Proposal;
use App\Models\Semester;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $semesterId = $request->semester_id ?? optional(Semester::current())->id;
        $semesters  = Semester::orderByDesc('start_date')->get();

        $proposalStatusData = Proposal::when($semesterId, fn($q) => $q->whereHas('team', fn($t) => $t->where('semester_id', $semesterId)))
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $domainData = Proposal::join('project_domains', 'proposals.domain_id', '=', 'project_domains.id')
            ->when($semesterId, fn($q) => $q->whereHas('team', fn($t) => $t->where('semester_id', $semesterId)))
            ->select('project_domains.name', DB::raw('count(*) as count'))
            ->groupBy('project_domains.name')
            ->get();

        $supervisorWorkload = User::where('role', 'supervisor')
            ->withCount(['supervisedTeams as team_count' => fn($q) => $q->when($semesterId, fn($t) => $t->where('semester_id', $semesterId))])
            ->having('team_count', '>', 0)
            ->get();

        $teamStatusData = Team::when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $evaluationSummary = Evaluation::when($semesterId, fn($q) => $q->whereHas('team', fn($t) => $t->where('semester_id', $semesterId)))
            ->select('type', DB::raw('count(*) as count'), DB::raw('avg(marks) as avg_marks'), DB::raw('max(marks) as max_marks'), DB::raw('min(marks) as min_marks'))
            ->groupBy('type')
            ->get();

        $submissionTimeline = Team::when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
            ->with('proposal')
            ->get();

        return view('admin.reports.index', compact(
            'semesters', 'semesterId', 'proposalStatusData', 'domainData',
            'supervisorWorkload', 'teamStatusData', 'evaluationSummary', 'submissionTimeline'
        ));
    }

    public function archive(Request $request)
    {
        $semesters = Semester::withCount('teams')->where('is_active', false)->latest()->paginate(10);
        $archivedTeams = Team::where('status', 'archived')
            ->with('proposal.domain', 'supervisor', 'semester')
            ->paginate(15);

        return view('admin.archive.index', compact('semesters', 'archivedTeams'));
    }
}
