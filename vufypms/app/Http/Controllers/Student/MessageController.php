<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Notification;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    private function getTeam()
    {
        $member = TeamMember::where('student_id', Auth::id())->where('status', 'active')->first();
        return $member ? $member->team : null;
    }

    public function index()
    {
        $user = Auth::user();
        $team = $this->getTeam();

        if (!$team) return redirect()->route('student.team.index')->with('error', 'Join a team first.');
        if (!$team->supervisor_id) return view('student.messages.index', ['team' => $team, 'messages' => collect(), 'supervisor' => null]);

        Message::where('team_id', $team->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages   = $team->messages()->with('sender')->get();
        $supervisor = $team->supervisor;

        return view('student.messages.index', compact('team', 'messages', 'supervisor'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $team = $this->getTeam();

        if (!$team || !$team->supervisor_id) return back()->with('error', 'No supervisor assigned yet.');

        $request->validate(['content' => ['required', 'string', 'max:2000']]);

        Message::create([
            'team_id'     => $team->id,
            'sender_id'   => $user->id,
            'receiver_id' => $team->supervisor_id,
            'content'     => $request->content,
        ]);

        Notification::send($team->supervisor_id, 'New Message', "Team \"{$team->name}\" sent you a message.", 'info', route('supervisor.messages.index', $team->id));

        return back()->with('success', 'Message sent.');
    }

    public function unreadCount()
    {
        $user = Auth::user();
        $team = $this->getTeam();
        $count = 0;

        if ($team) {
            $count = Message::where('team_id', $team->id)->where('receiver_id', $user->id)->where('is_read', false)->count();
        }

        return response()->json(['count' => $count]);
    }
}
