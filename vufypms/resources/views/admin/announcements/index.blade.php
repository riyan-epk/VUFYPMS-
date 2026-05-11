@extends('layouts.admin')
@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Announcement</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover datatable mb-0">
            <thead class="table-light">
                <tr><th>Title</th><th>Type</th><th>Target</th><th>Public</th><th>Published</th><th>Expires</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($announcements as $a)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $a->title }}</div>
                        <small class="text-muted">By: {{ $a->creator->name }}</small>
                    </td>
                    <td>{!! $a->type_badge !!}</td>
                    <td><span class="badge bg-info-subtle text-info border border-info-subtle">{{ ucfirst($a->target_role) }}</span></td>
                    <td>
                        <span class="badge {{ $a->is_public ? 'bg-success' : 'bg-secondary' }}">
                            {{ $a->is_public ? 'Public' : 'Internal' }}
                        </span>
                    </td>
                    <td><small>{{ $a->published_at ? $a->published_at->format('M d, Y') : 'Draft' }}</small></td>
                    <td><small class="text-muted">{{ $a->expires_at ? $a->expires_at->format('M d, Y') : '—' }}</small></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.announcements.edit', $a->id) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.announcements.destroy', $a->id) }}" class="confirm-delete">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No announcements yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($announcements->hasPages())<div class="card-footer">{{ $announcements->links() }}</div>@endif
</div>
@endsection
