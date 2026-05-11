@extends('layouts.supervisor')
@section('title', 'Supervisor Dashboard')
@section('page-title', 'Supervisor Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1e3a5f,#1565C0);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Total Teams</div><h4 class="fw-bold mt-1 mb-0">{{ $totalTeams }}</h4></div>
                <i class="bi bi-people-fill opacity-50" style="font-size:2rem;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1b5e20,#388e3c);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Active Teams</div><h4 class="fw-bold mt-1 mb-0">{{ $activeTeams }}</h4></div>
                <i class="bi bi-check-circle-fill opacity-50" style="font-size:2rem;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#e65100,#f57c00);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Pending Reviews</div><h4 class="fw-bold mt-1 mb-0">{{ $pendingProposals }}</h4></div>
                <i class="bi bi-hourglass-split opacity-50" style="font-size:2rem;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#4a148c,#7b1fa2);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Upcoming Meetings</div><h4 class="fw-bold mt-1 mb-0">{{ $upcomingMeetings->count() }}</h4></div>
                <i class="bi bi-calendar-event-fill opacity-50" style="font-size:2rem;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people-fill text-primary me-2"></i>My Teams</span>
                <a href="{{ route('supervisor.teams.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Team</th><th>Proposal Status</th><th>Members</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($teams->take(6) as $t)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $t->name }}</div>
                                <small class="text-muted">{{ $t->semester->name ?? 'N/A' }}</small>
                            </td>
                            <td>{!! $t->proposal ? $t->proposal->status_badge : '<span class="badge bg-secondary">No Proposal</span>' !!}</td>
                            <td><span class="badge bg-light text-dark border">{{ $t->members->count() }} member(s)</span></td>
                            <td><a href="{{ route('supervisor.teams.show', $t->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No teams assigned yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($recentActivity->count())
        <div class="card">
            <div class="card-header"><i class="bi bi-activity text-success me-2"></i>Recent Document Uploads</div>
            <div class="list-group list-group-flush">
                @foreach($recentActivity as $doc)
                <div class="list-group-item d-flex align-items-center gap-3">
                    <i class="{{ $doc->type_icon }} fs-4"></i>
                    <div>
                        <div class="fw-semibold small">{{ $doc->original_name }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $doc->team->name }} · {{ $doc->type_label }} · {{ $doc->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        @if($upcomingMeetings->count())
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event-fill text-info me-2"></i>Upcoming Meetings</span>
                <a href="{{ route('supervisor.meetings.index') }}" class="btn btn-sm btn-outline-info">All</a>
            </div>
            <div class="list-group list-group-flush">
                @foreach($upcomingMeetings as $m)
                <div class="list-group-item">
                    <div class="fw-semibold small">{{ $m->title }}</div>
                    <div class="text-muted" style="font-size:0.75rem;"><i class="bi bi-people me-1"></i>{{ $m->team->name }}</div>
                    <div class="text-muted" style="font-size:0.75rem;"><i class="bi bi-calendar me-1"></i>{{ $m->scheduled_at->format('M d, Y H:i') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($notifications->count())
        <div class="card">
            <div class="card-header"><i class="bi bi-bell-fill text-warning me-2"></i>Notifications</div>
            <div class="list-group list-group-flush">
                @foreach($notifications as $n)
                <a href="{{ $n->link ?? '#' }}" class="list-group-item list-group-item-action">
                    <div class="fw-semibold small">{{ $n->title }}</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ Str::limit($n->message, 70) }}</div>
                    <div class="text-muted" style="font-size:0.72rem;">{{ $n->created_at->diffForHumans() }}</div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
