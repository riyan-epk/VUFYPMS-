@extends('layouts.supervisor')
@section('title', 'Messages')
@section('page-title', 'Team Messages')

@section('content')
<div class="row g-3">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-people-fill text-primary me-2"></i>Teams</div>
            <div class="list-group list-group-flush">
                @foreach($teams as $t)
                <a href="{{ route('supervisor.messages.index', $t->id) }}"
                   class="list-group-item list-group-item-action {{ $t->id === $team->id ? 'active' : '' }}">
                    <div class="fw-semibold small">{{ $t->name }}</div>
                    <div class="{{ $t->id === $team->id ? 'text-white-50' : 'text-muted' }}" style="font-size:0.72rem;">
                        {{ $t->members->count() }} member(s)
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-people-fill text-primary"></i>
                <div class="fw-semibold">{{ $team->name }}</div>
                <span class="ms-auto text-muted small">{{ $team->members->count() }} member(s)</span>
            </div>
            <div class="card-body p-0">
                <div id="chatBox" style="height:400px;overflow-y:auto;padding:1rem;background:#f8f9fa;">
                    @forelse($messages as $msg)
                    <div class="d-flex mb-3 {{ $msg->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                        <img src="{{ $msg->sender->profile_photo_url }}" class="rounded-circle flex-shrink-0" width="32" height="32" style="object-fit:cover;">
                        <div class="mx-2" style="max-width:75%;">
                            <div class="p-3 rounded-3 {{ $msg->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-white border' }}" style="word-break:break-word;">
                                <div class="fw-semibold mb-1" style="font-size:0.75rem;{{ $msg->sender_id === auth()->id() ? 'color:rgba(255,255,255,0.7)' : 'color:#6c757d' }}">{{ $msg->sender->name }}</div>
                                {{ $msg->content }}
                            </div>
                            <div class="text-muted mt-1" style="font-size:0.72rem;text-align:{{ $msg->sender_id === auth()->id() ? 'right' : 'left' }};">
                                {{ $msg->created_at->format('M d, H:i') }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-chat-dots mb-2 d-block" style="font-size:2rem;"></i>No messages yet.
                    </div>
                    @endforelse
                </div>
                <div class="p-3 border-top bg-white">
                    <form method="POST" action="{{ route('supervisor.messages.store') }}" id="msgForm">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $team->id }}">
                        <div class="input-group">
                            <textarea name="content" class="form-control" rows="2" placeholder="Type your message to the team..." required id="msgInput"></textarea>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    const cb = document.getElementById('chatBox');
    if (cb) cb.scrollTop = cb.scrollHeight;
    document.getElementById('msgInput')?.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); document.getElementById('msgForm').submit(); }
    });
</script>
@endpush
