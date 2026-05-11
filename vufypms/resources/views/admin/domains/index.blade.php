@extends('layouts.admin')
@section('title', 'Project Domains')
@section('page-title', 'Project Domains')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle text-primary me-2"></i>Add Domain</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.domains.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Domain Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g., Artificial Intelligence" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief description...">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>Add Domain</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-grid-fill text-primary me-2"></i>All Domains</div>
            <div class="table-responsive">
                <table class="table table-hover datatable mb-0">
                    <thead class="table-light">
                        <tr><th>Domain Name</th><th>Description</th><th>Projects</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($domains as $d)
                        <tr>
                            <td class="fw-semibold">{{ $d->name }}</td>
                            <td><small class="text-muted">{{ $d->description ?? '—' }}</small></td>
                            <td><span class="badge bg-primary">{{ $d->proposals_count }}</span></td>
                            <td>
                                <span class="badge {{ $d->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $d->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editDomain{{ $d->id }}"><i class="bi bi-pencil"></i></button>
                                    @if($d->proposals_count == 0)
                                    <form method="POST" action="{{ route('admin.domains.destroy', $d->id) }}" class="confirm-delete">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="editDomain{{ $d->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Edit Domain</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <form method="POST" action="{{ route('admin.domains.update', $d->id) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $d->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Description</label>
                                                <textarea name="description" class="form-control" rows="2">{{ $d->description }}</textarea>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ $d->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label">Active</label>
                                            </div>
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
                        <tr><td colspan="5" class="text-center text-muted py-4">No domains added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($domains->hasPages())
            <div class="card-footer">{{ $domains->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
