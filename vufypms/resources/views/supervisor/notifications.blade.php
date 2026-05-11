@extends('layouts.supervisor')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">All Notifications</h5>
    <form method="POST" action="{{ route('supervisor.notifications.read-all') }}">
        @csrf
        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-check-all me-1"></i>Mark All Read</button>
    </form>
</div>
@forelse($notifications as $n)
<div class="card mb-2 {{ !$n->is_read ? 'border-primary border-2' : '' }}">
    <div class="card-body d-flex align-items-start gap-3 py-3">
        <i class="{{ $n->icon }} fs-4 flex-shrink-0 mt-1"></i>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start">
                <div class="fw-semibold {{ !$n->is_read ? 'text-primary' : '' }}">{{ $n->title }}</div>
                @if(!$n->is_read)<span class="badge bg-primary" style="font-size:0.65rem;">New</span>@endif
            </div>
            <p class="text-muted small mb-1">{{ $n->message }}</p>
            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $n->created_at->diffForHumans() }}</small>
        </div>
        @if($n->link)
        <a href="{{ $n->link }}" class="btn btn-sm btn-outline-primary flex-shrink-0">View</a>
        @endif
    </div>
</div>
@empty
<div class="text-center py-5">
    <i class="bi bi-bell-slash text-muted" style="font-size:3rem;"></i>
    <p class="text-muted mt-2">No notifications.</p>
</div>
@endforelse
<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
