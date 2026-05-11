@extends('layouts.app')

@section('sidebar-nav')
<span class="nav-label">Main</span>
<a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<span class="nav-label">My Project</span>
<a href="{{ route('student.team.index') }}" class="nav-link {{ request()->routeIs('student.team.*') ? 'active' : '' }}">
    <i class="bi bi-people-fill"></i> My Team
</a>
<a href="{{ route('student.proposal.index') }}" class="nav-link {{ request()->routeIs('student.proposal.*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-text-fill"></i> Proposal
</a>
<a href="{{ route('student.documents.index') }}" class="nav-link {{ request()->routeIs('student.documents.*') ? 'active' : '' }}">
    <i class="bi bi-folder2-open"></i> Documents
</a>
<a href="{{ route('student.milestones.index') }}" class="nav-link {{ request()->routeIs('student.milestones.*') ? 'active' : '' }}">
    <i class="bi bi-flag-fill"></i> Milestones
</a>

<span class="nav-label">Communication</span>
<a href="{{ route('student.messages.index') }}" class="nav-link {{ request()->routeIs('student.messages.*') ? 'active' : '' }}">
    <i class="bi bi-chat-dots-fill"></i> Messages
    @php
        $unreadMsgs = 0;
        $tm = \App\Models\TeamMember::where('student_id', auth()->id())->where('status','active')->first();
        if ($tm) {
            $unreadMsgs = \App\Models\Message::where('team_id', $tm->team_id)->where('receiver_id', auth()->id())->where('is_read', false)->count();
        }
    @endphp
    @if($unreadMsgs > 0)
        <span class="badge bg-danger ms-auto">{{ $unreadMsgs }}</span>
    @endif
</a>

<span class="nav-label">Evaluations</span>
<a href="{{ route('student.evaluations.index') }}" class="nav-link {{ request()->routeIs('student.evaluations.*') ? 'active' : '' }}">
    <i class="bi bi-award-fill"></i> My Evaluations
</a>
<a href="{{ route('student.presentations.index') }}" class="nav-link {{ request()->routeIs('student.presentations.*') ? 'active' : '' }}">
    <i class="bi bi-camera-video-fill"></i> Presentations
</a>
@endsection
