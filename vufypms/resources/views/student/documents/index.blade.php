@extends('layouts.student')
@section('title', 'Documents')
@section('page-title', 'Document Management')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-upload text-primary me-2"></i>Upload Document</div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.documents.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Document Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="proposal">Proposal Document</option>
                            <option value="srs">Software Requirements Specification (SRS)</option>
                            <option value="design">Design Document</option>
                            <option value="progress_report">Progress Report</option>
                            <option value="final_report">Final Report</option>
                            <option value="presentation">Presentation</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.txt" required>
                        <div class="form-text">PDF, DOC, DOCX, PPT, PPTX, ZIP, TXT · Max 10MB</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-cloud-upload me-1"></i>Upload</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        @php
            $types = ['proposal' => 'Proposal', 'srs' => 'SRS', 'design' => 'Design', 'progress_report' => 'Progress Report', 'final_report' => 'Final Report', 'presentation' => 'Presentation', 'other' => 'Other'];
        @endphp
        @foreach($types as $key => $label)
        @if(isset($documents[$key]) && $documents[$key]->count())
        <div class="card mb-3">
            <div class="card-header fw-semibold">
                <i class="bi bi-folder2-open text-warning me-2"></i>{{ $label }}
            </div>
            <div class="list-group list-group-flush">
                @foreach($documents[$key] as $doc)
                <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="{{ $doc->type_icon }} fs-5"></i>
                        <div>
                            <div class="fw-semibold small">{{ $doc->original_name }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                v{{ $doc->version }} · {{ $doc->file_size_human }} · {{ $doc->created_at->format('M d, Y') }}
                                · {{ $doc->uploader->name }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('student.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download"></i>
                        </a>
                        <form method="POST" action="{{ route('student.documents.destroy', $doc->id) }}" class="confirm-delete">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach

        @if($documents->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-folder2-open text-muted" style="font-size:3rem;"></i>
            <p class="text-muted mt-2">No documents uploaded yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection
