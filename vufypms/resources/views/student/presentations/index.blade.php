@extends('layouts.student')
@section('title', 'Presentations')
@section('page-title', 'Presentations & Meetings')

@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <h5 class="fw-bold mb-3"><i class="bi bi-camera-video-fill text-primary me-2"></i>Scheduled Presentations</h5>
        @forelse($presentations as $p)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0">{{ $p->type_label }}</h6>
                    {!! $p->status_badge !!}
                </div>
                <div class="row g-2 text-muted small">
                    <div class="col-sm-6"><i class="bi bi-calendar me-1"></i>{{ $p->scheduled_at->format('M d, Y — H:i') }}</div>
                    <div class="col-sm-6"><i class="bi bi-clock me-1"></i>Duration: {{ $p->duration_minutes }} minutes</div>
                    @if($p->venue) <div class="col-sm-6"><i class="bi bi-geo-alt me-1"></i>{{ $p->venue }}</div> @endif
                    @if($p->online_link)
                    <div class="col-sm-6">
                        <i class="bi bi-link-45deg me-1"></i>
                        <a href="{{ $p->online_link }}" target="_blank">Join Online</a>
                    </div>
                    @endif
                </div>
                @if($p->panel_info)
                <div class="mt-2 p-2 bg-light rounded small"><i class="bi bi-people me-1"></i><strong>Panel:</strong> {{ $p->panel_info }}</div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-4 bg-light rounded-3">
            <i class="bi bi-camera-video text-muted" style="font-size:2.5rem;"></i>
            <p class="text-muted mt-2 mb-0">No presentations scheduled yet.</p>
        </div>
        @endforelse
    </div>

    <div class="col-lg-5">
        <h5 class="fw-bold mb-3"><i class="bi bi-calendar-event-fill text-success me-2"></i>Supervisor Meetings</h5>
        @forelse($meetings as $m)
        <div class="card mb-2">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-semibold">{{ $m->title }}</div>
                        <small class="text-muted"><i class="bi bi-calendar me-1"></i>{{ $m->scheduled_at->format('M d, Y H:i') }}</small><br>
                        @if($m->venue) <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $m->venue }}</small> @endif
                        @if($m->meeting_link) <br><small><a href="{{ $m->meeting_link }}" target="_blank" class="text-primary"><i class="bi bi-camera-video me-1"></i>Join Online</a></small> @endif
                    </div>
                    {!! $m->status_badge !!}
                </div>
                @if($m->notes)
                <p class="text-muted small mt-2 mb-0">{{ $m->notes }}</p>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-4 bg-light rounded-3">
            <i class="bi bi-calendar-x text-muted" style="font-size:2.5rem;"></i>
            <p class="text-muted mt-2 mb-0">No meetings scheduled yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
