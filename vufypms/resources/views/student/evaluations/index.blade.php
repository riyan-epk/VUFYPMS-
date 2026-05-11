@extends('layouts.student')
@section('title', 'My Evaluations')
@section('page-title', 'Evaluation History')

@section('content')
@forelse($evaluations as $ev)
<div class="card mb-3">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-5">
                <h6 class="fw-bold mb-1">{{ $ev->type_label }}</h6>
                <small class="text-muted"><i class="bi bi-person me-1"></i>Evaluator: {{ $ev->evaluator->name }}</small><br>
                <small class="text-muted"><i class="bi bi-calendar me-1"></i>{{ $ev->evaluation_date->format('M d, Y') }}</small>
            </div>
            <div class="col-md-3 text-center">
                <div class="display-6 fw-bold text-primary">{{ $ev->marks ?? 'N/A' }}</div>
                <small class="text-muted">out of {{ $ev->max_marks }}</small>
                <div>
                    @if($ev->marks !== null)
                    <div class="progress mt-1" style="height:6px;">
                        <div class="progress-bar bg-{{ $ev->percentage >= 60 ? 'success' : 'danger' }}" style="width:{{ $ev->percentage }}%"></div>
                    </div>
                    <small class="text-muted">{{ $ev->percentage }}% — Grade: <strong>{{ $ev->grade }}</strong></small>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                @if($ev->remarks)
                <div class="small text-muted fw-semibold">Remarks:</div>
                <p class="small mb-1">{{ $ev->remarks }}</p>
                @endif
                @if($ev->recommendations)
                <div class="small text-muted fw-semibold">Recommendations:</div>
                <p class="small mb-0">{{ $ev->recommendations }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5">
    <i class="bi bi-award text-muted" style="font-size:3rem;"></i>
    <p class="text-muted mt-2">No evaluations recorded yet.</p>
</div>
@endforelse
@endsection
