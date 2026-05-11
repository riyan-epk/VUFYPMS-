@extends('layouts.admin')
@section('title', 'Semesters')
@section('page-title', 'Semester Management')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle text-primary me-2"></i>Add Semester</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.semesters.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Semester Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g., Fall 2024" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Proposal Submission Opens</label>
                        <input type="date" name="proposal_start" class="form-control" value="{{ old('proposal_start') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Proposal Submission Closes</label>
                        <input type="date" name="proposal_end" class="form-control" value="{{ old('proposal_end') }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>Create Semester</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-calendar3 text-primary me-2"></i>All Semesters</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Name</th><th>Period</th><th>Teams</th><th>Milestones</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($semesters as $s)
                        <tr>
                            <td class="fw-semibold">{{ $s->name }}</td>
                            <td><small class="text-muted">{{ $s->start_date->format('M d, Y') }} — {{ $s->end_date->format('M d, Y') }}</small></td>
                            <td><span class="badge bg-primary">{{ $s->teams_count }}</span></td>
                            <td><span class="badge bg-info">{{ $s->milestones_count }}</span></td>
                            <td>
                                @if($s->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    @if(!$s->is_active)
                                    <form method="POST" action="{{ route('admin.semesters.activate', $s->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Activate"><i class="bi bi-play-fill"></i></button>
                                    </form>
                                    @endif
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editSem{{ $s->id }}"><i class="bi bi-pencil"></i></button>
                                    @if($s->teams_count == 0)
                                    <form method="POST" action="{{ route('admin.semesters.destroy', $s->id) }}" class="confirm-delete">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="editSem{{ $s->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Edit Semester</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <form method="POST" action="{{ route('admin.semesters.update', $s->id) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-2"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" value="{{ $s->name }}" required></div>
                                            <div class="mb-2"><label class="form-label fw-semibold">Start Date</label><input type="date" name="start_date" class="form-control" value="{{ $s->start_date->format('Y-m-d') }}" required></div>
                                            <div class="mb-2"><label class="form-label fw-semibold">End Date</label><input type="date" name="end_date" class="form-control" value="{{ $s->end_date->format('Y-m-d') }}" required></div>
                                            <div class="mb-2"><label class="form-label fw-semibold">Proposal Start</label><input type="date" name="proposal_start" class="form-control" value="{{ $s->proposal_start?->format('Y-m-d') }}"></div>
                                            <div class="mb-2"><label class="form-label fw-semibold">Proposal End</label><input type="date" name="proposal_end" class="form-control" value="{{ $s->proposal_end?->format('Y-m-d') }}"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-warning">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No semesters created yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($semesters->hasPages())
            <div class="card-footer">{{ $semesters->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
