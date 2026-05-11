@extends('layouts.student')
@section('title', 'Student Dashboard')
@section('page-title', 'Student Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1e3a5f,#1565C0);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Team Status</div><h4 class="fw-bold mt-1 mb-0">{{ $team ? ucfirst(str_replace('_',' ',$team->status)) : 'No Team' }}</h4></div>
                <i class="bi bi-people-fill opacity-50" style="font-size:2rem;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1b5e20,#2e7d32);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Proposal</div><h4 class="fw-bold mt-1 mb-0">{{ $proposal ? ucfirst(str_replace('_',' ',$proposal->status)) : 'Not Created' }}</h4></div>
                <i class="bi bi-file-earmark-text-fill opacity-50" style="font-size:2rem;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#e65100,#f57c00);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Pending Milestones</div><h4 class="fw-bold mt-1 mb-0">{{ $pendingMilestones }}</h4></div>
                <i class="bi bi-flag-fill opacity-50" style="font-size:2rem;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#880e4f,#ad1457);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Unread Messages</div><h4 class="fw-bold mt-1 mb-0">{{ $unreadMessages }}</h4></div>
                <i class="bi bi-chat-dots-fill opacity-50" style="font-size:2rem;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        @if(!$team)
        <div class="card mb-3 border-warning border-2">
            <div class="card-body text-center py-4">
                <i class="bi bi-people text-warning" style="font-size:3rem;"></i>
                <h5 class="fw-bold mt-2">You're not in a team yet!</h5>
                <p class="text-muted">Create or join a team to start your FYP journey.</p>
                <a href="{{ route('student.team.index') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Create / Join Team</a>
            </div>
        </div>
        @else
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people-fill text-primary me-2"></i>My Team</span>
                <a href="{{ route('student.team.index') }}" class="btn btn-sm btn-outline-primary">View Team</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small">Team Name</div>
                        <div class="fw-semibold">{{ $team->name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Status</div>
                        <div>{!! $team->status_badge !!}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Supervisor</div>
                        <div class="fw-semibold">{{ $team->supervisor?->name ?? 'Not assigned yet' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Members</div>
                        <div class="fw-semibold">{{ $team->members->count() }} member(s)</div>
                    </div>
                </div>
                <hr>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($team->members as $m)
                    <span class="badge bg-light text-dark border">
                        <i class="bi bi-person-fill me-1"></i>{{ $m->student->name }} ({{ $m->role }})
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if($upcomingMilestones->count())
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-flag-fill text-warning me-2"></i>Upcoming Milestones</span>
                <a href="{{ route('student.milestones.index') }}" class="btn btn-sm btn-outline-warning">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($upcomingMilestones->take(4) as $tm)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $tm->milestone->name }}</div>
                            <small class="text-muted"><i class="bi bi-calendar me-1"></i>Due: {{ $tm->milestone->due_date->format('M d, Y') }}</small>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            {!! $tm->status_badge !!}
                            @if($tm->milestone->isOverdue() && $tm->status !== 'completed')
                                <span class="badge bg-danger">Overdue</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        @if($notifications->count())
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-bell-fill text-info me-2"></i>Notifications</div>
            <div class="list-group list-group-flush">
                @foreach($notifications as $n)
                <a href="{{ $n->link ?? '#' }}" class="list-group-item list-group-item-action">
                    <i class="{{ $n->icon }} me-2"></i>
                    <div class="fw-semibold small">{{ $n->title }}</div>
                    <div class="text-muted" style="font-size:0.78rem;">{{ Str::limit($n->message, 70) }}</div>
                    <div class="text-muted" style="font-size:0.72rem;"><i class="bi bi-clock me-1"></i>{{ $n->created_at->diffForHumans() }}</div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($announcements->count())
        <div class="card">
            <div class="card-header"><i class="bi bi-megaphone-fill text-primary me-2"></i>Announcements</div>
            <div class="list-group list-group-flush">
                @foreach($announcements as $a)
                <div class="list-group-item">
                    <div class="fw-semibold small">{{ $a->title }}</div>
                    <div class="text-muted" style="font-size:0.78rem;">{{ Str::limit(strip_tags($a->content), 80) }}</div>
                    <div class="mt-1">{!! $a->type_badge !!}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
