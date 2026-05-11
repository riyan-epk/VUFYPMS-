@extends('layouts.supervisor')
@section('title', 'Meetings')
@section('page-title', 'Meeting Management')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle text-primary me-2"></i>Schedule New Meeting</div>
            <div class="card-body">
                <form method="POST" action="{{ route('supervisor.meetings.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Team <span class="text-danger">*</span></label>
                        <select name="team_id" class="form-select" required>
                            <option value="">-- Select Team --</option>
                            @foreach($teams as $t)
                            <option value="{{ $t->id }}" {{ old('team_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Meeting Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g., Progress Discussion" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Venue</label>
                        <input type="text" name="venue" class="form-control" value="{{ old('venue') }}" placeholder="e.g., Room 201 or Online">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Meeting Link (Online)</label>
                        <input type="url" name="meeting_link" class="form-control" value="{{ old('meeting_link') }}" placeholder="https://meet.google.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Agenda or notes...">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-calendar-plus me-1"></i>Schedule Meeting</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-calendar-event-fill text-info me-2"></i>All Meetings</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Title</th><th>Team</th><th>Scheduled</th><th>Venue</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($meetings as $m)
                        <tr>
                            <td><div class="fw-semibold">{{ $m->title }}</div></td>
                            <td>{{ $m->team->name }}</td>
                            <td><small>{{ $m->scheduled_at->format('M d, Y H:i') }}</small></td>
                            <td>
                                @if($m->venue) <small>{{ $m->venue }}</small> @endif
                                @if($m->meeting_link) <a href="{{ $m->meeting_link }}" target="_blank" class="btn btn-xs btn-outline-primary ms-1" style="font-size:0.7rem;padding:1px 6px;"><i class="bi bi-camera-video"></i></a> @endif
                            </td>
                            <td>{!! $m->status_badge !!}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('supervisor.meetings.update', $m->id) }}">
                                        @csrf @method('PUT')
                                        <select name="status" class="form-select form-select-sm" style="width:120px;" onchange="this.form.submit()">
                                            <option value="scheduled" {{ $m->status==='scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            <option value="completed" {{ $m->status==='completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $m->status==='cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                    <form method="POST" action="{{ route('supervisor.meetings.destroy', $m->id) }}" class="confirm-delete">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No meetings scheduled.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($meetings->hasPages())
            <div class="card-footer">{{ $meetings->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
