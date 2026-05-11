<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamMember;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $teamMember = TeamMember::where('student_id', $user->id)->where('status', 'active')
            ->with('team.members.student', 'team.supervisor', 'team.proposal', 'team.semester')
            ->first();
        $team = $teamMember ? $teamMember->team : null;
        $semester = Semester::current();

        $pendingInvitations = TeamInvitation::where('invited_student_id', $user->id)
            ->where('status', 'pending')
            ->with('team.creator', 'inviter')
            ->get();

        return view('student.team.index', compact('user', 'team', 'teamMember', 'semester', 'pendingInvitations'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (TeamMember::where('student_id', $user->id)->where('status', 'active')->exists()) {
            return back()->with('error', 'You are already a member of a team.');
        }

        $semester = Semester::current();
        if (!$semester) {
            return back()->with('error', 'No active semester found. Please contact the administrator.');
        }

        $request->validate([
            'team_name' => ['required', 'string', 'max:200'],
        ]);

        $team = Team::create([
            'name'        => $request->team_name,
            'semester_id' => $semester->id,
            'created_by'  => $user->id,
            'status'      => 'forming',
        ]);

        TeamMember::create([
            'team_id'    => $team->id,
            'student_id' => $user->id,
            'role'       => 'leader',
            'status'     => 'active',
            'joined_at'  => now(),
        ]);

        return redirect()->route('student.team.index')->with('success', 'Team "' . $team->name . '" created successfully! You are the team leader.');
    }

    public function invite(Request $request)
    {
        $user = Auth::user();
        $teamMember = TeamMember::where('student_id', $user->id)->where('status', 'active')->where('role', 'leader')->first();

        if (!$teamMember) {
            return back()->with('error', 'Only the team leader can send invitations.');
        }

        $request->validate([
            'student_vu_id' => ['required', 'string'],
        ]);

        $invitee = User::where('vu_id', $request->student_vu_id)->where('role', 'student')->first();

        if (!$invitee) {
            return back()->with('error', 'No student found with VU-ID: ' . $request->student_vu_id);
        }

        if ($invitee->id === $user->id) {
            return back()->with('error', 'You cannot invite yourself.');
        }

        if (TeamMember::where('student_id', $invitee->id)->where('status', 'active')->exists()) {
            return back()->with('error', $invitee->name . ' is already a member of another team.');
        }

        $team = $teamMember->team;

        if ($team->members()->count() >= 4) {
            return back()->with('error', 'Maximum team size (4 members) reached.');
        }

        if (TeamInvitation::where('team_id', $team->id)->where('invited_student_id', $invitee->id)->where('status', 'pending')->exists()) {
            return back()->with('error', 'An invitation has already been sent to ' . $invitee->name . '.');
        }

        TeamInvitation::create([
            'team_id'            => $team->id,
            'invited_student_id' => $invitee->id,
            'invited_by'         => $user->id,
            'status'             => 'pending',
        ]);

        Notification::send($invitee->id, 'Team Invitation', "You have been invited to join team \"{$team->name}\" by {$user->name}.", 'info', route('student.team.invitations'));

        return back()->with('success', 'Invitation sent to ' . $invitee->name . ' (' . $invitee->vu_id . ').');
    }

    public function invitations()
    {
        $user = Auth::user();
        $invitations = TeamInvitation::where('invited_student_id', $user->id)
            ->with('team.creator', 'team.members.student', 'inviter')
            ->latest()
            ->paginate(10);
        return view('student.team.invitations', compact('invitations'));
    }

    public function acceptInvitation(Request $request, int $id)
    {
        $user       = Auth::user();
        $invitation = TeamInvitation::where('invited_student_id', $user->id)->where('status', 'pending')->findOrFail($id);

        if (TeamMember::where('student_id', $user->id)->where('status', 'active')->exists()) {
            $invitation->update(['status' => 'cancelled']);
            return back()->with('error', 'You are already in a team. This invitation has been cancelled.');
        }

        $invitation->update(['status' => 'accepted']);

        TeamMember::create([
            'team_id'    => $invitation->team_id,
            'student_id' => $user->id,
            'role'       => 'member',
            'status'     => 'active',
            'joined_at'  => now(),
        ]);

        TeamInvitation::where('invited_student_id', $user->id)->where('status', 'pending')->where('id', '!=', $id)->update(['status' => 'cancelled']);

        Notification::send($invitation->invited_by, 'Invitation Accepted', "{$user->name} accepted your team invitation.", 'success', route('student.team.index'));

        return redirect()->route('student.team.index')->with('success', 'You have joined the team successfully!');
    }

    public function rejectInvitation(Request $request, int $id)
    {
        $user       = Auth::user();
        $invitation = TeamInvitation::where('invited_student_id', $user->id)->where('status', 'pending')->findOrFail($id);
        $invitation->update(['status' => 'rejected']);

        return back()->with('success', 'Invitation rejected.');
    }

    public function searchStudents(Request $request)
    {
        $query = $request->get('q', '');
        $students = User::where('role', 'student')
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('vu_id', 'like', "%$query%")->orWhere('name', 'like', "%$query%");
            })
            ->whereDoesntHave('teamMember', function ($q) { $q->where('status', 'active'); })
            ->select('id', 'name', 'vu_id', 'email')
            ->limit(10)
            ->get();

        return response()->json($students);
    }
}
