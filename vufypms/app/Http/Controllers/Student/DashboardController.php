<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $teamMember = TeamMember::where('student_id', $user->id)->where('status', 'active')->with('team.proposal', 'team.supervisor', 'team.members')->first();
        $team       = $teamMember ? $teamMember->team : null;

        $upcomingMilestones = collect();
        $pendingMilestones  = 0;
        $proposal           = null;
        $unreadMessages     = 0;
        $recentEvaluations  = collect();

        if ($team) {
            $proposal = $team->proposal;
            $upcomingMilestones = $team->teamMilestones()
                ->whereIn('status', ['pending', 'in_progress'])
                ->with('milestone')
                ->get()
                ->sortBy('milestone.due_date');
            $pendingMilestones = $upcomingMilestones->count();

            if ($team->supervisor) {
                $unreadMessages = \App\Models\Message::where('team_id', $team->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count();
            }

            $recentEvaluations = $team->evaluations()->take(3)->get();
        }

        $announcements   = Announcement::published()
            ->where(function ($q) { $q->where('is_public', true)->orWhere('target_role', 'student')->orWhere('target_role', 'all'); })
            ->latest('published_at')->take(5)->get();

        $notifications   = $user->userNotifications()->where('is_read', false)->latest()->take(5)->get();

        return view('student.dashboard', compact(
            'user', 'team', 'teamMember', 'proposal',
            'upcomingMilestones', 'pendingMilestones',
            'unreadMessages', 'recentEvaluations',
            'announcements', 'notifications'
        ));
    }

    public function notifications()
    {
        $notifications = Auth::user()->userNotifications()->latest()->paginate(20);
        return view('student.notifications', compact('notifications'));
    }

    public function markNotificationRead(Request $request, int $id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead(Request $request)
    {
        Auth::user()->userNotifications()->where('is_read', false)->update(['is_read' => true, 'read_at' => now()]);
        return back()->with('success', 'All notifications marked as read.');
    }
}
