@extends('layouts.guest')
@section('title', 'Announcements')
@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-megaphone-fill text-primary" style="font-size:1.8rem;"></i>
        <div>
            <h3 class="fw-bold mb-0">Public Announcements</h3>
            <p class="text-muted mb-0">Important notices and updates from the FYP department</p>
        </div>
    </div>

    @if($announcements->count())
        @foreach($announcements as $a)
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold mb-0">{{ $a->title }}</h5>
                    <div class="d-flex gap-2 flex-shrink-0 ms-3">
                        {!! $a->type_badge !!}
                    </div>
                </div>
                <div class="text-muted mb-3" style="line-height:1.7;">{{ $a->content }}</div>
                <div class="text-muted small">
                    <i class="bi bi-clock me-1"></i> Published {{ $a->published_at->format('M d, Y') }}
                    @if($a->expires_at)
                        &nbsp;·&nbsp;<i class="bi bi-calendar-x me-1"></i>Expires {{ $a->expires_at->format('M d, Y') }}
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        <div class="mt-3">{{ $announcements->links() }}</div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-megaphone text-muted" style="font-size:3rem;"></i>
            <p class="text-muted mt-2">No announcements yet.</p>
        </div>
    @endif
</div>
@endsection
