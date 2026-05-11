<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $teams = Team::where('supervisor_id', $user->id)->with('members.student')->get();

        $meetings = Meeting::where('supervisor_id', $user->id)
            ->with('team')
            ->orderByDesc('scheduled_at')
            ->paginate(10);

        return view('supervisor.meetings.index', compact('meetings', 'teams'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'team_id'      => ['required', 'exists:teams,id'],
            'title'        => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'venue'        => ['nullable', 'string', 'max:255'],
            'meeting_link' => ['nullable', 'url', 'max:500'],
            'notes'        => ['nullable', 'string', 'max:1000'],
        ]);

        Team::where('supervisor_id', $user->id)->findOrFail($request->team_id);

        $meeting = Meeting::create([
            'team_id'      => $request->team_id,
            'supervisor_id'=> $user->id,
            'title'        => $request->title,
            'scheduled_at' => $request->scheduled_at,
            'venue'        => $request->venue,
            'meeting_link' => $request->meeting_link,
            'notes'        => $request->notes,
        ]);

        $team = Team::find($request->team_id);
        foreach ($team->members as $member) {
            Notification::send(
                $member->student_id,
                'Meeting Scheduled',
                "A meeting \"{$meeting->title}\" has been scheduled on " . $meeting->scheduled_at->format('M d, Y H:i'),
                'info',
                route('student.presentations.index')
            );
        }

        return back()->with('success', 'Meeting scheduled successfully.');
    }

    public function update(Request $request, int $id)
    {
        $user    = Auth::user();
        $meeting = Meeting::where('supervisor_id', $user->id)->findOrFail($id);

        $request->validate([
            'status'       => ['required', 'in:scheduled,completed,cancelled'],
            'notes'        => ['nullable', 'string', 'max:1000'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $meeting->update($request->only('status', 'notes', 'scheduled_at'));
        return back()->with('success', 'Meeting updated.');
    }

    public function destroy(int $id)
    {
        $user    = Auth::user();
        $meeting = Meeting::where('supervisor_id', $user->id)->findOrFail($id);
        $meeting->delete();
        return back()->with('success', 'Meeting deleted.');
    }
}
