@extends('layouts.supervisor')
@section('title', 'Team Documents')
@section('page-title', 'Documents — {{ $team->name }}')

@section('content')
<div class="d-flex gap-2 mb-3">
    <a href="{{ route('supervisor.teams.show', $team->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Team</a>
</div>

@php $types = ['proposal'=>'Proposal','srs'=>'SRS','design'=>'Design','progress_report'=>'Progress Report','final_report'=>'Final Report','presentation'=>'Presentation','other'=>'Other']; @endphp

@foreach($types as $key => $label)
@if(isset($documents[$key]) && $documents[$key]->count())
<div class="card mb-3">
    <div class="card-header fw-semibold"><i class="bi bi-folder2-open text-warning me-2"></i>{{ $label }}</div>
    <div class="list-group list-group-flush">
        @foreach($documents[$key] as $doc)
        <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="{{ $doc->type_icon }} fs-5"></i>
                <div>
                    <div class="fw-semibold small">{{ $doc->original_name }}</div>
                    <div class="text-muted" style="font-size:0.75rem;">v{{ $doc->version }} · {{ $doc->file_size_human }} · {{ $doc->created_at->format('M d, Y') }} · {{ $doc->uploader->name }}</div>
                    @if($doc->notes) <div class="text-muted" style="font-size:0.72rem;">{{ $doc->notes }}</div> @endif
                </div>
            </div>
            <a href="{{ route('supervisor.teams.documents.download', [$team->id, $doc->id]) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-download me-1"></i>Download
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif
@endforeach

@if($documents->isEmpty())
<div class="text-center py-5">
    <i class="bi bi-folder2-open text-muted" style="font-size:3rem;"></i>
    <p class="text-muted mt-2">No documents uploaded by this team yet.</p>
</div>
@endif
@endsection
