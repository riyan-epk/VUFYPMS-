@extends('layouts.app')

@section('sidebar-nav')
<span class="nav-label">Main</span>
<a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<span class="nav-label">User Management</span>
<a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
    <i class="bi bi-people-fill"></i> All Users
</a>
<a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
    <i class="bi bi-person-plus-fill"></i> Add User
</a>

<span class="nav-label">Project Config</span>
<a href="{{ route('admin.domains.index') }}" class="nav-link {{ request()->routeIs('admin.domains.*') ? 'active' : '' }}">
    <i class="bi bi-grid-fill"></i> Project Domains
</a>
<a href="{{ route('admin.semesters.index') }}" class="nav-link {{ request()->routeIs('admin.semesters.*') ? 'active' : '' }}">
    <i class="bi bi-calendar3"></i> Semesters
</a>
<a href="{{ route('admin.milestones.index') }}" class="nav-link {{ request()->routeIs('admin.milestones.*') ? 'active' : '' }}">
    <i class="bi bi-flag-fill"></i> Milestones
</a>

<span class="nav-label">Team Management</span>
<a href="{{ route('admin.teams.index') }}" class="nav-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}">
    <i class="bi bi-diagram-3-fill"></i> Teams
</a>

<span class="nav-label">Communication</span>
<a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
    <i class="bi bi-megaphone-fill"></i> Announcements
</a>

<span class="nav-label">Analytics</span>
<a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-fill"></i> Reports
</a>
<a href="{{ route('admin.archive.index') }}" class="nav-link {{ request()->routeIs('admin.archive.*') ? 'active' : '' }}">
    <i class="bi bi-archive-fill"></i> Archive
</a>
@endsection
