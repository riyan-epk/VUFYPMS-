@extends('layouts.supervisor')
@section('title', 'Proposals')
@section('page-title', 'Proposal Reviews')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-file-earmark-check-fill text-primary me-2"></i>All Assigned Team Proposals</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Team</th><th>Title</th><th>Domain</th><th>Submitted</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($proposals as $p)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $p->team->name }}</div>
                        <small class="text-muted">{{ $p->team->members->count() }} member(s)</small>
                    </td>
                    <td>{{ Str::limit($p->title, 40) }}</td>
                    <td><span class="badge bg-info-subtle text-info border border-info-subtle">{{ $p->domain->name }}</span></td>
                    <td><small>{{ $p->submitted_at ? $p->submitted_at->format('M d, Y') : 'N/A' }}</small></td>
                    <td>{!! $p->status_badge !!}</td>
                    <td>
                        <a href="{{ route('supervisor.proposals.show', $p->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye me-1"></i>Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No proposals assigned yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($proposals->hasPages())
    <div class="card-footer">{{ $proposals->links() }}</div>
    @endif
</div>
@endsection
