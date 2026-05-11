<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ProjectDomain;
use App\Models\Proposal;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{
    private function getTeam()
    {
        $member = TeamMember::where('student_id', Auth::id())->where('status', 'active')->first();
        return $member ? $member->team : null;
    }

    public function index()
    {
        $team     = $this->getTeam();
        $domains  = ProjectDomain::active()->get();
        $proposal = $team ? $team->proposal : null;
        return view('student.proposal.index', compact('team', 'domains', 'proposal'));
    }

    public function store(Request $request)
    {
        $team = $this->getTeam();
        if (!$team) return back()->with('error', 'You must be in a team first.');
        if ($team->proposal) return back()->with('error', 'Your team already has a proposal.');

        $validated = $request->validate([
            'title'             => ['required', 'string', 'max:500'],
            'abstract'          => ['required', 'string', 'min:100'],
            'domain_id'         => ['required', 'exists:project_domains,id'],
            'tools_technologies'=> ['required', 'string'],
            'objectives'        => ['nullable', 'string'],
        ]);

        $validated['team_id'] = $team->id;
        $validated['status']  = 'draft';

        Proposal::create($validated);

        return redirect()->route('student.proposal.index')->with('success', 'Proposal saved as draft.');
    }

    public function update(Request $request, int $id)
    {
        $team     = $this->getTeam();
        $proposal = Proposal::where('team_id', $team->id)->findOrFail($id);

        if (!$proposal->isEditable()) {
            return back()->with('error', 'This proposal cannot be edited in its current status.');
        }

        $validated = $request->validate([
            'title'             => ['required', 'string', 'max:500'],
            'abstract'          => ['required', 'string', 'min:100'],
            'domain_id'         => ['required', 'exists:project_domains,id'],
            'tools_technologies'=> ['required', 'string'],
            'objectives'        => ['nullable', 'string'],
        ]);

        $proposal->update($validated);

        return redirect()->route('student.proposal.index')->with('success', 'Proposal updated successfully.');
    }

    public function submit(Request $request, int $id)
    {
        $team     = $this->getTeam();
        $proposal = Proposal::where('team_id', $team->id)->findOrFail($id);

        if (!$proposal->canSubmit()) {
            return back()->with('error', 'This proposal cannot be submitted in its current status.');
        }

        $proposal->update([
            'status'       => 'submitted',
            'submitted_at' => now(),
            'revision_notes' => null,
        ]);

        $team->update(['status' => 'under_review']);

        if ($team->supervisor) {
            Notification::send(
                $team->supervisor_id,
                'New Proposal Submitted',
                "Team \"{$team->name}\" has submitted their project proposal for review.",
                'info',
                route('supervisor.proposals.show', $proposal->id)
            );
        }

        return redirect()->route('student.proposal.index')->with('success', 'Proposal submitted successfully! Awaiting supervisor review.');
    }
}
