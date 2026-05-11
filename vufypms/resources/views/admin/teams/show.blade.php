@extends('layouts.admin')
@section('title', 'Team Details')
@section('page-title', 'Team: {{ $team->name }}')

@section('content')
<div class="d-flex gap-2 mb-3 flex-wrap">
    <a href="{{ route('admin.teams.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>All Teams</a>
    @if($team->status !== 'archived')
    <form method="POST" action="{{ route('admin.teams.archive', $team->id) }}" class="confirm-delete">
        @csrf
        <button class="btn btn-sm btn-outline-dark"><i class="bi bi-archive me-1"></i>Archive Team</button>
    </form>
    @endif
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-info-circle-fill text-primary me-2"></i>Team Info</div>
            <div class="card-body">
                <div class="mb-2"><div class="text-muted small">Name</div><div class="fw-bold">{{ $team->name }}</div></div>
                <div class="mb-2"><div class="text-muted small">Semester</div><div>{{ $team->semester?->name ?? '—' }}</div></div>
                <div class="mb-2"><div class="text-muted small">Status</div>{!! $team->status_badge !!}</div>
                <div class="mb-3">
                    <div class="text-muted small">Supervisor</div>
                    @if($team->supervisor)
                        <div class="fw-semibold text-success"><i class="bi bi-person-check me-1"></i>{{ $team->supervisor->name }}</div>
                    @else
                        <span class="text-warning">Unassigned</span>
                    @endif
                </div>

                <h6 class="fw-bold mb-2">Members</h6>
                @foreach($team->members as $m)
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="{{ $m->student->profile_photo_url }}" class="rounded-circle" width="28" height="28" style="object-fit:cover;">
                    <div>
                        <div class="small fw-semibold">{{ $m->student->name }}</div>
                        <div class="text-muted" style="font-size:0.72rem;"><code>{{ $m->student->vu_id }}</code> · {{ ucfirst($m->role) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @if(!$team->supervisor)
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-person-plus text-success me-2"></i>Assign Supervisor</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.teams.assign-supervisor', $team->id) }}">
                    @csrf
                    <div class="mb-3">
                        <select name="supervisor_id" class="form-select" required>
                            <option value="">-- Select Supervisor --</option>
                            @foreach($supervisors as $sv)
                            <option value="{{ $sv->id }}">{{ $sv->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-person-check me-1"></i>Assign</button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-8">
        @if($team->proposal)
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Proposal</div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold">{{ $team->proposal->title }}</h6>
                    {!! $team->proposal->status_badge !!}
                </div>
                <span class="badge bg-primary mb-2">{{ $team->proposal->domain->name }}</span>
                <p class="text-muted small">{{ Str::limit($team->proposal->abstract, 200) }}</p>
            </div>
        </div>
        @endif

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-camera-video-fill text-danger me-2"></i>Schedule Presentation</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.presentations.store') }}" class="row g-2">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                    <div class="col-md-4">
                        <select name="type" class="form-select" required>
                            <option value="">Type</option>
                            <option value="proposal_defense">Proposal Defense</option>
                            <option value="progress_review">Progress Review</option>
                            <option value="final_defense">Final Defense</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="datetime-local" name="scheduled_at" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="duration_minutes" class="form-control" placeholder="Duration (min)" value="60" min="10">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="venue" class="form-control" placeholder="Venue (optional)">
                    </div>
                    <div class="col-md-6">
                        <input type="url" name="online_link" class="form-control" placeholder="Online link (optional)">
                    </div>
                    <div class="col-12">
                        <textarea name="panel_info" class="form-control" rows="1" placeholder="Panel members info (optional)"></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-danger"><i class="bi bi-calendar-plus me-1"></i>Schedule Presentation</button>
                    </div>
                </form>
            </div>
        </div>

        @if($team->presentations->count())
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-camera-video text-danger me-2"></i>Presentations</div>
            <div class="list-group list-group-flush">
                @foreach($team->presentations as $p)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold small">{{ $p->type_label }}</div>
                        <small class="text-muted"><i class="bi bi-calendar me-1"></i>{{ $p->scheduled_at->format('M d, Y H:i') }}</small>
                    </div>
                    {!! $p->status_badge !!}
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($team->evaluations->count())
        <div class="card">
            <div class="card-header"><i class="bi bi-award-fill text-warning me-2"></i>Evaluations</div>
            <div class="list-group list-group-flush">
                @foreach($team->evaluations as $ev)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold small">{{ $ev->type_label }}</div>
                        <small class="text-muted">{{ $ev->evaluator->name }} · {{ $ev->evaluation_date->format('M d, Y') }}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary">{{ $ev->marks }}/{{ $ev->max_marks }}</div>
                        <small class="text-muted">Grade: {{ $ev->grade }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
