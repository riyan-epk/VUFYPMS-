<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Proposal;
use App\Models\Semester;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents    = User::where('role', 'student')->count();
        $totalSupervisors = User::where('role', 'supervisor')->count();
        $totalTeams       = Team::count();
        $activeSemester   = Semester::current();

        $proposalStats = Proposal::select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status');
        $domainData    = Proposal::where('status', 'approved')
            ->join('project_domains', 'proposals.domain_id', '=', 'project_domains.id')
            ->select('project_domains.name', DB::raw('count(*) as count'))
            ->groupBy('project_domains.name')
            ->get();

        $supervisorWorkload = User::where('role', 'supervisor')
            ->withCount(['supervisedTeams as team_count'])
            ->orderByDesc('team_count')
            ->take(10)
            ->get();

        $recentTeams = Team::with('proposal', 'supervisor', 'semester', 'members')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents', 'totalSupervisors', 'totalTeams', 'activeSemester',
            'proposalStats', 'domainData', 'supervisorWorkload', 'recentTeams'
        ));
    }
}
