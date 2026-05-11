@extends('layouts.student')
@section('title', 'Team Invitations')
@section('page-title', 'Team Invitations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">All Invitations</h5>
    <a href="{{ route('student.team.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Team</a>
</div>

@forelse($invitations as $inv)
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h6 class="fw-bold mb-1">Team: {{ $inv->team->name }}</h6>
                <p class="text-muted small mb-1"><i class="bi bi-person me-1"></i>Invited by: {{ $inv->inviter->name }} ({{ $inv->inviter->vu_id }})</p>
                <p class="text-muted small mb-1"><i class="bi bi-people me-1"></i>Current members: {{ $inv->team->members->count() }}</p>
                <p class="text-muted small mb-0"><i class="bi bi-clock me-1"></i>{{ $inv->created_at->diffForHumans() }}</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if($inv->status === 'pending')
                    <form method="POST" action="{{ route('student.team.invitations.accept', $inv->id) }}">
                        @csrf
                        <button class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i>Accept</button>
                    </form>
                    <form method="POST" action="{{ route('student.team.invitations.reject', $inv->id) }}">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-x-lg me-1"></i>Reject</button>
                    </form>
                @else
                    <span class="badge {{ $inv->status === 'accepted' ? 'bg-success' : ($inv->status === 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                        {{ ucfirst($inv->status) }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5">
    <i class="bi bi-envelope-open text-muted" style="font-size:3rem;"></i>
    <p class="text-muted mt-2">No invitations found.</p>
</div>
@endforelse
<div>{{ $invitations->links() }}</div>
@endsection
