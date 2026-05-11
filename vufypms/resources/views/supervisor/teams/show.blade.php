@extends('layouts.supervisor')
@section('title', 'Team Details')
@section('page-title', 'Team: {{ $team->name }}')

@section('content')
<div class="d-flex gap-2 mb-3">
    <a href="{{ route('supervisor.teams.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>All Teams</a>
    <a href="{{ route('supervisor.messages.index', $team->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-chat-dots me-1"></i>Message Team</a>
    <a href="{{ route('supervisor.teams.documents', $team->id) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-folder2-open me-1"></i>Documents</a>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-people-fill text-primary me-2"></i>Team Info</div>
            <div class="card-body">
                <div class="mb-2">
                    <div class="text-muted small">Team Name</div>
                    <div class="fw-bold">{{ $team->name }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Status</div>
                    {!! $team->status_badge !!}
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Members</div>
                    @foreach($team->members as $m)
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <img src="{{ $m->student->profile_photo_url }}" class="rounded-circle" width="26" height="26" style="object-fit:cover;">
                        <span class="small">{{ $m->student->name }} <code class="ms-1">{{ $m->student->vu_id }}</code>
                        @if($m->role === 'leader') <span class="badge bg-primary ms-1" style="font-size:0.65rem;">Leader</span> @endif
                        </span>
                    </div>
                    @endforeach
                </div>
                @if($team->proposal)
                <div>
                    <div class="text-muted small">Proposal Status</div>
                    {!! $team->proposal->status_badge !!}
                </div>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-flag-fill text-warning me-2"></i>Milestones ({{ $completedMilestones }}/{{ $milestones->count() }})</div>
            <div class="card-body p-2">
                <div class="progress mb-2" style="height:8px;"><div class="progress-bar bg-success" style="width:{{ $progress }}%"></div></div>
                @foreach($milestones as $tm)
                <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                    <div>
                        <div class="small fw-semibold">{{ $tm->milestone->name }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">Due: {{ $tm->milestone->due_date->format('M d, Y') }}</div>
                    </div>
                    {!! $tm->status_badge !!}
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        @if($team->proposal)
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Proposal</div>
            <div class="card-body">
                <h6 class="fw-bold">{{ $team->proposal->title }}</h6>
                <span class="badge bg-primary mb-2">{{ $team->proposal->domain->name }}</span>
                <p class="text-muted small">{{ Str::limit($team->proposal->abstract, 200) }}</p>
                @if(in_array($team->proposal->status, ['submitted', 'under_review']))
                <a href="{{ route('supervisor.proposals.show', $team->proposal->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil me-1"></i>Review Proposal</a>
                @endif
            </div>
        </div>
        @endif

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clipboard2-check-fill text-success me-2"></i>Evaluations</span>
                <a href="{{ route('supervisor.evaluations.index') }}" class="btn btn-sm btn-outline-success">Add Evaluation</a>
            </div>
            <div class="card-body p-0">
                @forelse($team->evaluations as $ev)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <div><div class="fw-semibold small">{{ $ev->type_label }}</div>
                        <small class="text-muted">{{ $ev->evaluation_date->format('M d, Y') }}</small></div>
                        <div class="text-end">
                            <div class="fw-bold text-primary">{{ $ev->marks }}/{{ $ev->max_marks }}</div>
                            <div class="small text-muted">{{ $ev->percentage }}% — {{ $ev->grade }}</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3 small">No evaluations yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
