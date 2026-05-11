@extends('layouts.admin')
@section('title', 'Teams')
@section('page-title', 'Team Management')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.teams.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by team name or project title..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="semester_id" class="form-select">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $s)
                    <option value="{{ $s->id }}" {{ request('semester_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['forming','active','under_review','approved','completed','archived'] as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$st)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i>Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Team</th><th>Semester</th><th>Supervisor</th><th>Proposal</th><th>Members</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($teams as $t)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $t->name }}</div>
                        @if($t->project_title)<small class="text-muted">{{ Str::limit($t->project_title, 35) }}</small>@endif
                    </td>
                    <td><small>{{ $t->semester?->name ?? '—' }}</small></td>
                    <td>
                        @if($t->supervisor)
                            <small class="text-success"><i class="bi bi-person-check me-1"></i>{{ $t->supervisor->name }}</small>
                        @else
                            <span class="badge bg-warning text-dark">Unassigned</span>
                        @endif
                    </td>
                    <td>{!! $t->proposal ? $t->proposal->status_badge : '<span class="badge bg-secondary">None</span>' !!}</td>
                    <td><span class="badge bg-light text-dark border">{{ $t->members->count() }}</span></td>
                    <td>{!! $t->status_badge !!}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.teams.show', $t->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></a>
                            @if(!$t->supervisor)
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#assignSup{{ $t->id }}" data-bs-toggle="tooltip" title="Assign Supervisor"><i class="bi bi-person-plus"></i></button>
                            @endif
                        </div>
                    </td>
                </tr>
                <div class="modal fade" id="assignSup{{ $t->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">Assign Supervisor — {{ $t->name }}</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                            <form method="POST" action="{{ route('admin.teams.assign-supervisor', $t->id) }}">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label fw-semibold">Select Supervisor</label>
                                    <select name="supervisor_id" class="form-select" required>
                                        <option value="">-- Choose Supervisor --</option>
                                        @foreach($supervisors as $sv)
                                        <option value="{{ $sv->id }}">{{ $sv->name }} ({{ $sv->supervisedTeams->count() ?? 0 }} teams)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success"><i class="bi bi-person-check me-1"></i>Assign</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No teams found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($teams->hasPages())<div class="card-footer">{{ $teams->links() }}</div>@endif
</div>
@endsection
