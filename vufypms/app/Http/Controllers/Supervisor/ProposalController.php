<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Proposal;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $proposals = Proposal::whereHas('team', fn($q) => $q->where('supervisor_id', $user->id))
            ->with('team.members.student', 'domain')
            ->latest()
            ->paginate(10);

        return view('supervisor.proposals.index', compact('proposals'));
    }

    public function show(int $id)
    {
        $user     = Auth::user();
        $proposal = Proposal::whereHas('team', fn($q) => $q->where('supervisor_id', $user->id))
            ->with('team.members.student', 'domain', 'reviewer')
            ->findOrFail($id);

        if (in_array($proposal->status, ['submitted'])) {
            $proposal->update(['status' => 'under_review']);
        }

        return view('supervisor.proposals.show', compact('proposal'));
    }

    public function review(Request $request, int $id)
    {
        $user     = Auth::user();
        $proposal = Proposal::whereHas('team', fn($q) => $q->where('supervisor_id', $user->id))->findOrFail($id);

        $request->validate([
            'action'         => ['required', 'in:approve,revise,reject'],
            'revision_notes' => ['required_if:action,revise,reject', 'nullable', 'string', 'max:2000'],
        ]);

        $statusMap = [
            'approve' => 'approved',
            'revise'  => 'revision_required',
            'reject'  => 'rejected',
        ];

        $proposal->update([
            'status'         => $statusMap[$request->action],
            'revision_notes' => $request->revision_notes,
            'reviewed_at'    => now(),
            'reviewed_by'    => $user->id,
        ]);

        if ($request->action === 'approve') {
            $proposal->team->update(['status' => 'approved']);
        } elseif ($request->action === 'revise') {
            $proposal->team->update(['status' => 'forming']);
        }

        $messages = [
            'approve' => ['title' => 'Proposal Approved!', 'msg' => "Your project proposal has been approved by your supervisor.", 'type' => 'success'],
            'revise'  => ['title' => 'Proposal Needs Revision', 'msg' => "Your supervisor has requested revisions on your proposal.", 'type' => 'warning'],
            'reject'  => ['title' => 'Proposal Rejected', 'msg' => "Your project proposal has been rejected. Please review the feedback.", 'type' => 'danger'],
        ];

        foreach ($proposal->team->members as $member) {
            Notification::send(
                $member->student_id,
                $messages[$request->action]['title'],
                $messages[$request->action]['msg'],
                $messages[$request->action]['type'],
                route('student.proposal.index')
            );
        }

        return redirect()->route('supervisor.proposals.index')
            ->with('success', 'Proposal review submitted successfully.');
    }
}
