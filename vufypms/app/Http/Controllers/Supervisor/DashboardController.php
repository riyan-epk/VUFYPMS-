<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $teams = Team::where('supervisor_id', $user->id)
            ->with('proposal', 'members', 'semester')
            ->get();

        $pendingProposals = $teams->filter(fn($t) => $t->proposal && in_array($t->proposal->status, ['submitted', 'under_review']))->count();
        $activeTeams      = $teams->where('status', 'active')->count();
        $totalTeams       = $teams->count();

        $upcomingMeetings = \App\Models\Meeting::where('supervisor_id', $user->id)
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->with('team')
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        $recentActivity = \App\Models\Document::whereIn('team_id', $teams->pluck('id'))
            ->with('team', 'uploader')
            ->latest()
            ->take(5)
            ->get();

        $announcements = Announcement::published()
            ->where(function ($q) { $q->where('target_role', 'supervisor')->orWhere('target_role', 'all'); })
            ->latest('published_at')->take(5)->get();

        $notifications = $user->userNotifications()->where('is_read', false)->latest()->take(5)->get();

        return view('supervisor.dashboard', compact(
            'user', 'teams', 'totalTeams', 'activeTeams',
            'pendingProposals', 'upcomingMeetings', 'recentActivity',
            'announcements', 'notifications'
        ));
    }

    public function messages(int $teamId)
    {
        $user = Auth::user();
        $team = Team::where('supervisor_id', $user->id)->findOrFail($teamId);

        Message::where('team_id', $team->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = $team->messages()->with('sender')->get();
        $teams    = Team::where('supervisor_id', $user->id)->with('members.student')->get();

        return view('supervisor.messages', compact('team', 'messages', 'teams'));
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'team_id' => ['required', 'exists:teams,id'],
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $team = Team::where('supervisor_id', $user->id)->findOrFail($request->team_id);

        $studentIds = $team->members()->pluck('student_id');

        Message::create([
            'team_id'     => $team->id,
            'sender_id'   => $user->id,
            'receiver_id' => $studentIds->first(),
            'content'     => $request->content,
        ]);

        foreach ($studentIds as $studentId) {
            Notification::send($studentId, 'New Message from Supervisor', "Your supervisor sent a message.", 'info', route('student.messages.index'));
        }

        return back()->with('success', 'Message sent to team.');
    }

    public function notifications()
    {
        $notifications = Auth::user()->userNotifications()->latest()->paginate(20);
        return view('supervisor.notifications', compact('notifications'));
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
