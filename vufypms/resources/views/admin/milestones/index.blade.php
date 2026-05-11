@extends('layouts.admin')
@section('title', 'Milestones')
@section('page-title', 'Milestone Management')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle text-primary me-2"></i>Add Milestone</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.milestones.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                        <select name="semester_id" class="form-select" required>
                            <option value="">-- Select Semester --</option>
                            @foreach($semesters as $s)
                            <option value="{{ $s->id }}" {{ old('semester_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} {{ $s->is_active ? '(Active)' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Milestone Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g., SRS Submission" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="What is expected...">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Order <span class="text-danger">*</span></label>
                        <input type="number" name="order_index" class="form-control" value="{{ old('order_index', 1) }}" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>Add Milestone</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-flag-fill text-warning me-2"></i>All Milestones</div>
            <div class="table-responsive">
                <table class="table table-hover datatable mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Milestone</th><th>Semester</th><th>Due Date</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($milestones as $m)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $m->order_index }}</span></td>
                            <td>
                                <div class="fw-semibold">{{ $m->name }}</div>
                                @if($m->description)<small class="text-muted">{{ Str::limit($m->description, 60) }}</small>@endif
                            </td>
                            <td><small>{{ $m->semester->name }}</small></td>
                            <td>
                                <small class="{{ $m->isOverdue() ? 'text-danger fw-bold' : 'text-muted' }}">
                                    <i class="bi bi-calendar me-1"></i>{{ $m->due_date->format('M d, Y') }}
                                    @if($m->isOverdue()) <span class="badge bg-danger ms-1">Overdue</span> @endif
                                </small>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editMilestone{{ $m->id }}"><i class="bi bi-pencil"></i></button>
                                    <form method="POST" action="{{ route('admin.milestones.destroy', $m->id) }}" class="confirm-delete">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="editMilestone{{ $m->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Edit Milestone</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <form method="POST" action="{{ route('admin.milestones.update', $m->id) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-2"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" value="{{ $m->name }}" required></div>
                                            <div class="mb-2"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2">{{ $m->description }}</textarea></div>
                                            <div class="mb-2"><label class="form-label fw-semibold">Due Date</label><input type="date" name="due_date" class="form-control" value="{{ $m->due_date->format('Y-m-d') }}" required></div>
                                            <div class="mb-2"><label class="form-label fw-semibold">Order</label><input type="number" name="order_index" class="form-control" value="{{ $m->order_index }}" min="1" required></div>
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
                        <tr><td colspan="5" class="text-center text-muted py-4">No milestones created yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($milestones->hasPages())<div class="card-footer">{{ $milestones->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection
