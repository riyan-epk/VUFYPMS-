@extends('layouts.supervisor')
@section('title', 'Review Proposal')
@section('page-title', 'Review Proposal')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>{{ Str::limit($proposal->title, 50) }}</span>
                {!! $proposal->status_badge !!}
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <div class="text-muted small">Team</div>
                        <div class="fw-semibold">{{ $proposal->team->name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Domain</div>
                        <span class="badge bg-primary">{{ $proposal->domain->name }}</span>
                    </div>
                </div>

                <h6 class="fw-bold text-muted">Project Title</h6>
                <p class="fw-semibold fs-5">{{ $proposal->title }}</p>

                <h6 class="fw-bold text-muted">Abstract</h6>
                <p style="line-height:1.8;">{{ $proposal->abstract }}</p>

                <h6 class="fw-bold text-muted">Tools & Technologies</h6>
                <p>{{ $proposal->tools_technologies }}</p>

                @if($proposal->objectives)
                <h6 class="fw-bold text-muted">Objectives</h6>
                <p>{{ $proposal->objectives }}</p>
                @endif

                <hr>
                <h6 class="fw-bold text-muted">Team Members</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($proposal->team->members as $m)
                    <span class="badge bg-light text-dark border">
                        <i class="bi bi-person me-1"></i>{{ $m->student->name }} ({{ $m->student->vu_id }})
                        @if($m->role === 'leader') <i class="bi bi-star-fill text-warning ms-1"></i> @endif
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if(in_array($proposal->status, ['submitted', 'under_review']))
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-pencil-fill text-warning me-2"></i>Submit Review</div>
            <div class="card-body">
                <form method="POST" action="{{ route('supervisor.proposals.review', $proposal->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Decision <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2 flex-column">
                            <div class="form-check p-2 border rounded {{ old('action') === 'approve' ? 'border-success bg-success-subtle' : '' }}">
                                <input class="form-check-input" type="radio" name="action" value="approve" id="approve" {{ old('action') === 'approve' ? 'checked' : '' }} required>
                                <label class="form-check-label text-success fw-semibold" for="approve"><i class="bi bi-check-circle me-1"></i>Approve Proposal</label>
                            </div>
                            <div class="form-check p-2 border rounded {{ old('action') === 'revise' ? 'border-warning bg-warning-subtle' : '' }}">
                                <input class="form-check-input" type="radio" name="action" value="revise" id="revise" {{ old('action') === 'revise' ? 'checked' : '' }}>
                                <label class="form-check-label text-warning fw-semibold" for="revise"><i class="bi bi-arrow-clockwise me-1"></i>Request Revision</label>
                            </div>
                            <div class="form-check p-2 border rounded {{ old('action') === 'reject' ? 'border-danger bg-danger-subtle' : '' }}">
                                <input class="form-check-input" type="radio" name="action" value="reject" id="reject" {{ old('action') === 'reject' ? 'checked' : '' }}>
                                <label class="form-check-label text-danger fw-semibold" for="reject"><i class="bi bi-x-circle me-1"></i>Reject Proposal</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Review Notes</label>
                        <textarea name="revision_notes" class="form-control" rows="4" placeholder="Provide detailed feedback, revision requirements, or reason for rejection...">{{ old('revision_notes') }}</textarea>
                        <div class="form-text">Required if requesting revision or rejecting.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send me-1"></i>Submit Review</button>
                </form>
            </div>
        </div>
        @else
        <div class="card mb-3">
            <div class="card-header fw-semibold">Review Result</div>
            <div class="card-body">
                <div class="text-center mb-3">{!! $proposal->status_badge !!}</div>
                @if($proposal->revision_notes)
                <div class="alert alert-warning small">{{ $proposal->revision_notes }}</div>
                @endif
                @if($proposal->reviewed_at)
                <small class="text-muted">Reviewed: {{ $proposal->reviewed_at->format('M d, Y') }}</small>
                @endif
            </div>
        </div>
        @endif

        <div class="d-grid">
            <a href="{{ route('supervisor.proposals.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Proposals
            </a>
        </div>
    </div>
</div>
@endsection
