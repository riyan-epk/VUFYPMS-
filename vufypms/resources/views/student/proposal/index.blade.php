@extends('layouts.student')
@section('title', 'Project Proposal')
@section('page-title', 'Project Proposal')

@section('content')
@if(!$team)
    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i>You must be in a team before submitting a proposal. <a href="{{ route('student.team.index') }}">Create or join a team</a>.</div>
@elseif(!$proposal)
<div class="card">
    <div class="card-header"><i class="bi bi-file-earmark-plus text-primary me-2"></i>Create New Proposal</div>
    <div class="card-body">
        <form method="POST" action="{{ route('student.proposal.store') }}">
            @csrf
            @include('student.proposal._form', ['proposal' => null])
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save as Draft</button>
        </form>
    </div>
</div>
@else
<div class="row g-3">
    <div class="col-lg-8">
        @if($proposal->isEditable())
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-pencil-fill text-warning me-2"></i>Edit Proposal</div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.proposal.update', $proposal->id) }}">
                    @csrf @method('PUT')
                    @include('student.proposal._form', ['proposal' => $proposal])
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Save Changes</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#submitModal">
                            <i class="bi bi-send me-1"></i>Submit for Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Proposal Details</span>
                {!! $proposal->status_badge !!}
            </div>
            <div class="card-body">
                <h5 class="fw-bold">{{ $proposal->title }}</h5>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-3">{{ $proposal->domain->name }}</span>
                <h6 class="text-muted fw-semibold">Abstract</h6>
                <p class="mb-3" style="line-height:1.8;">{{ $proposal->abstract }}</p>
                <h6 class="text-muted fw-semibold">Tools & Technologies</h6>
                <p class="mb-3">{{ $proposal->tools_technologies }}</p>
                @if($proposal->objectives)
                <h6 class="text-muted fw-semibold">Objectives</h6>
                <p>{{ $proposal->objectives }}</p>
                @endif
                @if($proposal->submitted_at)
                <hr>
                <small class="text-muted"><i class="bi bi-clock me-1"></i>Submitted: {{ $proposal->submitted_at->format('M d, Y H:i') }}</small>
                @endif
            </div>
        </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-info-circle text-info me-2"></i>Status</div>
            <div class="card-body">
                <div class="text-center mb-3">{!! $proposal->status_badge !!}</div>
                @if($proposal->revision_notes)
                <div class="alert alert-warning">
                    <h6 class="fw-bold"><i class="bi bi-pencil me-1"></i>Supervisor Notes:</h6>
                    <p class="mb-0 small">{{ $proposal->revision_notes }}</p>
                </div>
                @endif
                @if($proposal->reviewed_at)
                <small class="text-muted d-block">Reviewed: {{ $proposal->reviewed_at->format('M d, Y') }}</small>
                @endif
                @if($proposal->reviewer)
                <small class="text-muted d-block">Reviewer: {{ $proposal->reviewer->name }}</small>
                @endif
            </div>
        </div>
        @if($proposal->isEditable())
        <div class="d-grid">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#submitModal">
                <i class="bi bi-send me-1"></i>Submit for Review
            </button>
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Confirm Submission</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p>Are you sure you want to submit this proposal for supervisor review?</p>
                <p class="text-muted small">Once submitted, you won't be able to edit it until the supervisor requests revisions.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('student.proposal.submit', $proposal->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
