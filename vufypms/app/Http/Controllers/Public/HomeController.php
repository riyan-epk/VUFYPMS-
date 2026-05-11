<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Proposal;
use App\Models\ProjectDomain;

class HomeController extends Controller
{
    public function index()
    {
        $announcements = Announcement::published()->public()->latest('published_at')->take(5)->get();
        $completedProjects = Proposal::where('status', 'approved')
            ->with(['domain', 'team'])
            ->latest()
            ->take(6)
            ->get();
        return view('public.home', compact('announcements', 'completedProjects'));
    }

    public function announcements()
    {
        $announcements = Announcement::published()->public()
            ->latest('published_at')
            ->paginate(10);
        return view('public.announcements', compact('announcements'));
    }

    public function projects()
    {
        $query = Proposal::where('status', 'approved')->with(['domain', 'team.members.student']);

        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('abstract', 'like', '%' . request('search') . '%');
        }

        if (request('domain')) {
            $query->where('domain_id', request('domain'));
        }

        $projects = $query->latest()->paginate(12);
        $domains  = ProjectDomain::active()->get();

        return view('public.projects', compact('projects', 'domains'));
    }

    public function guidelines()
    {
        return view('public.guidelines');
    }
}
