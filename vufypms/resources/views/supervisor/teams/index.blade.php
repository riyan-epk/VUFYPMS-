@extends('layouts.supervisor')
@section('title', 'My Teams')
@section('page-title', 'My Teams')

@section('content')
<div class="row g-3">
    @forelse($teams as $team)
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0">{{ $team->name }}</h6>
                    {!! $team->status_badge !!}
                </div>
                @if($team->proposal)
                <p class="text-muted small mb-2"><i class="bi bi-file-text me-1"></i>{{ Str::limit($team->proposal->title, 50) }}</p>
                @endif
                <div class="d-flex gap-3 mb-3 text-muted small">
                    <span><i class="bi bi-people me-1"></i>{{ $team->members->count() }} member(s)</span>
                    <span><i class="bi bi-calendar me-1"></i>{{ $team->semester->name ?? 'N/A' }}</span>
                </div>

                @php
                    $total = $team->teamMilestones->count();
                    $done  = $team->teamMilestones->where('status', 'completed')->count();
                    $pct   = $total > 0 ? round(($done / $total) * 100) : 0;
                @endphp
                @if($total > 0)
                <div class="mb-2">
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Progress</span><span>{{ $done }}/{{ $total }}</span></div>
                    <div class="progress" style="height:6px;"><div class="progress-bar bg-success" style="width:{{ $pct }}%"></div></div>
                </div>
                @endif

                <div class="d-flex gap-2 mt-2">
                    <a href="{{ route('supervisor.teams.show', $team->id) }}" class="btn btn-sm btn-primary flex-grow-1">
                        <i class="bi bi-eye me-1"></i>View Details
                    </a>
                    <a href="{{ route('supervisor.messages.index', $team->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-chat-dots"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-people text-muted" style="font-size:3rem;"></i>
        <p class="text-muted mt-2">No teams assigned yet.</p>
    </div>
    @endforelse
</div>
@if($teams->hasPages())
<div class="mt-3">{{ $teams->links() }}</div>
@endif
@endsection
