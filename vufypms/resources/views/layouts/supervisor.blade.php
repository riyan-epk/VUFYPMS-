@extends('layouts.app')

@section('sidebar-nav')
<span class="nav-label">Main</span>
<a href="{{ route('supervisor.dashboard') }}" class="nav-link {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<span class="nav-label">Project Management</span>
<a href="{{ route('supervisor.proposals.index') }}" class="nav-link {{ request()->routeIs('supervisor.proposals.*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-check-fill"></i> Proposals
    @php
        $pendingProposals = \App\Models\Proposal::whereHas('team', fn($q) => $q->where('supervisor_id', auth()->id()))
            ->whereIn('status', ['submitted','under_review'])->count();
    @endphp
    @if($pendingProposals > 0)
        <span class="badge bg-warning text-dark ms-auto">{{ $pendingProposals }}</span>
    @endif
</a>
<a href="{{ route('supervisor.teams.index') }}" class="nav-link {{ request()->routeIs('supervisor.teams.*') ? 'active' : '' }}">
    <i class="bi bi-people-fill"></i> My Teams
</a>

<span class="nav-label">Schedule & Evaluation</span>
<a href="{{ route('supervisor.meetings.index') }}" class="nav-link {{ request()->routeIs('supervisor.meetings.*') ? 'active' : '' }}">
    <i class="bi bi-calendar-event-fill"></i> Meetings
</a>
<a href="{{ route('supervisor.evaluations.index') }}" class="nav-link {{ request()->routeIs('supervisor.evaluations.*') ? 'active' : '' }}">
    <i class="bi bi-clipboard2-check-fill"></i> Evaluations
</a>
@endsection
