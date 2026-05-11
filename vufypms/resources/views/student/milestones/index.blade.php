@extends('layouts.student')
@section('title', 'Milestones')
@section('page-title', 'Milestone Tracking')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">Overall Progress</h6>
            <span class="fw-bold text-primary">{{ $completed }} / {{ $total }} completed</span>
        </div>
        <div class="progress" style="height: 12px; border-radius: 6px;">
            <div class="progress-bar bg-success" style="width: {{ $progress }}%;">{{ $progress }}%</div>
        </div>
    </div>
</div>

<div class="row g-3">
    @forelse($milestones as $tm)
    <div class="col-md-6">
        <div class="card h-100 {{ $tm->milestone->isOverdue() && $tm->status !== 'completed' ? 'border-danger border-2' : '' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0">{{ $tm->milestone->name }}</h6>
                    {!! $tm->status_badge !!}
                </div>
                @if($tm->milestone->description)
                <p class="text-muted small mb-2">{{ $tm->milestone->description }}</p>
                @endif
                <div class="d-flex gap-3 mb-3 text-muted small">
                    <span><i class="bi bi-calendar me-1"></i>Due: {{ $tm->milestone->due_date->format('M d, Y') }}</span>
                    @if($tm->completed_at)
                    <span class="text-success"><i class="bi bi-check-circle me-1"></i>Done: {{ $tm->completed_at->format('M d, Y') }}</span>
                    @endif
                    @if($tm->milestone->isOverdue() && $tm->status !== 'completed')
                    <span class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Overdue</span>
                    @endif
                </div>
                <form method="POST" action="{{ route('student.milestones.update', $tm->id) }}" class="d-flex gap-2 align-items-end flex-wrap">
                    @csrf
                    <div class="flex-grow-1">
                        <label class="form-label small mb-1">Update Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="pending" {{ $tm->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $tm->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $tm->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-lg me-1"></i>Update</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-flag text-muted" style="font-size:3rem;"></i>
        <p class="text-muted mt-2">No milestones assigned yet. Check back after a supervisor is assigned.</p>
    </div>
    @endforelse
</div>
@endsection
