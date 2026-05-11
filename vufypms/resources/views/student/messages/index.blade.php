@extends('layouts.student')
@section('title', 'Messages')
@section('page-title', 'Messages with Supervisor')

@section('content')
@if(!$supervisor)
<div class="text-center py-5">
    <i class="bi bi-chat-dots text-muted" style="font-size:3rem;"></i>
    <p class="text-muted mt-2">No supervisor assigned yet. Messaging will be available once a supervisor is assigned to your team.</p>
</div>
@else
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <img src="{{ $supervisor->profile_photo_url }}" class="rounded-circle" width="32" height="32" style="object-fit:cover;">
                <div>
                    <div class="fw-semibold">{{ $supervisor->name }}</div>
                    <small class="text-muted">{{ $supervisor->designation ?? 'Supervisor' }}</small>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="chatBox" style="height:400px; overflow-y:auto; padding:1rem; background:#f8f9fa;">
                    @forelse($messages as $msg)
                    <div class="d-flex mb-3 {{ $msg->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                        <img src="{{ $msg->sender->profile_photo_url }}" class="rounded-circle flex-shrink-0" width="32" height="32" style="object-fit:cover;">
                        <div class="mx-2" style="max-width:75%;">
                            <div class="p-3 rounded-3 {{ $msg->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-white border' }}" style="word-break:break-word;">
                                {{ $msg->content }}
                            </div>
                            <div class="text-muted mt-1" style="font-size:0.72rem; text-align: {{ $msg->sender_id === auth()->id() ? 'right' : 'left' }};">
                                {{ $msg->created_at->format('M d, H:i') }}
                                @if($msg->sender_id === auth()->id() && $msg->is_read)
                                    <i class="bi bi-check-all text-primary ms-1"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-chat-dots mb-2 d-block" style="font-size:2rem;"></i>
                        No messages yet. Start the conversation!
                    </div>
                    @endforelse
                </div>
                <div class="p-3 border-top bg-white">
                    <form method="POST" action="{{ route('student.messages.store') }}" id="msgForm">
                        @csrf
                        <div class="input-group">
                            <textarea name="content" class="form-control" rows="2" placeholder="Type your message..." required id="msgInput"></textarea>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-person-badge-fill text-success me-2"></i>Supervisor Info</div>
            <div class="card-body text-center">
                <img src="{{ $supervisor->profile_photo_url }}" class="rounded-circle mb-2" width="64" height="64" style="object-fit:cover;">
                <h6 class="fw-bold mb-0">{{ $supervisor->name }}</h6>
                <p class="text-muted small">{{ $supervisor->designation ?? 'Supervisor' }}</p>
                @if($supervisor->department)
                <p class="text-muted small"><i class="bi bi-building me-1"></i>{{ $supervisor->department }}</p>
                @endif
                <p class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $supervisor->email }}</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    const chatBox = document.getElementById('chatBox');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

    document.getElementById('msgInput')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('msgForm').submit();
        }
    });
</script>
@endpush
