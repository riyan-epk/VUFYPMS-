@extends('layouts.student')
@section('title', 'My Team')
@section('page-title', 'Team Management')

@section('content')

@if($pendingInvitations->count())
<div class="alert alert-info d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-envelope-fill fs-4"></i>
    <div>You have <strong>{{ $pendingInvitations->count() }}</strong> pending team invitation(s).
        <a href="{{ route('student.team.invitations') }}" class="alert-link ms-1">View Invitations</a>
    </div>
</div>
@endif

@if(!$team)
<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-plus-circle text-primary me-2"></i>Create a New Team</div>
            <div class="card-body">
                @if(!$semester)
                    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i>No active semester. Please wait for admin to activate a semester.</div>
                @else
                <form method="POST" action="{{ route('student.team.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Team Name <span class="text-danger">*</span></label>
                        <input type="text" name="team_name" class="form-control @error('team_name') is-invalid @enderror" value="{{ old('team_name') }}" placeholder="e.g., Code Crusaders" required>
                    </div>
                    <div class="alert alert-info small"><i class="bi bi-info-circle me-1"></i>You will become the <strong>Team Leader</strong> automatically.</div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>Create Team</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-envelope-fill text-success me-2"></i>Join via Invitation</div>
            <div class="card-body text-center py-4">
                <i class="bi bi-people text-muted" style="font-size:3rem;"></i>
                <p class="text-muted mt-2">Ask your team leader to send you an invitation using your VU-ID.</p>
                <p class="fw-semibold">Your VU-ID: <span class="badge bg-primary">{{ $user->vu_id }}</span></p>
                <a href="{{ route('student.team.invitations') }}" class="btn btn-outline-success">
                    <i class="bi bi-envelope me-1"></i>View Invitations
                </a>
            </div>
        </div>
    </div>
</div>

@else
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people-fill text-primary me-2"></i>{{ $team->name }}</span>
                {!! $team->status_badge !!}
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Semester</div>
                        <div class="fw-semibold">{{ $team->semester->name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Supervisor</div>
                        <div class="fw-semibold">
                            @if($team->supervisor)
                                <i class="bi bi-person-check-fill text-success me-1"></i>{{ $team->supervisor->name }}
                                @if($team->supervisor->designation)
                                    <small class="text-muted">({{ $team->supervisor->designation }})</small>
                                @endif
                            @else
                                <span class="text-warning"><i class="bi bi-clock me-1"></i>Awaiting assignment</span>
                            @endif
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-2">Team Members</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr><th>Name</th><th>VU-ID</th><th>Role</th><th>Joined</th></tr>
                        </thead>
                        <tbody>
                            @foreach($team->members as $m)
                            <tr>
                                <td>
                                    <img src="{{ $m->student->profile_photo_url }}" class="rounded-circle me-2" width="28" height="28" style="object-fit:cover;">
                                    {{ $m->student->name }}
                                    @if($m->student_id === $user->id) <span class="badge bg-info text-dark ms-1">You</span> @endif
                                </td>
                                <td><code>{{ $m->student->vu_id }}</code></td>
                                <td><span class="badge {{ $m->role === 'leader' ? 'bg-primary' : 'bg-secondary' }}">{{ ucfirst($m->role) }}</span></td>
                                <td><small>{{ $m->joined_at?->format('M d, Y') ?? 'N/A' }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($teamMember && $teamMember->role === 'leader' && $team->members->count() < 4)
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-person-plus text-success me-2"></i>Invite Member</div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.team.invite') }}" id="inviteForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Student VU-ID</label>
                        <input type="text" name="student_vu_id" id="vuIdInput" class="form-control" placeholder="e.g., BC200400002" required>
                        <div class="form-text">Enter the VU-ID of the student you want to invite.</div>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-send me-1"></i>Send Invitation</button>
                </form>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle text-info me-2"></i>Team Info</div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted small">Members</span>
                    <span class="fw-semibold">{{ $team->members->count() }} / 4</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted small">Your Role</span>
                    <span class="fw-semibold">{{ ucfirst($teamMember->role) }}</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted small">Proposal</span>
                    <span>{!! $team->proposal ? $team->proposal->status_badge : '<span class="badge bg-secondary">None</span>' !!}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
