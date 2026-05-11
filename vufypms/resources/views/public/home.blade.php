@extends('layouts.guest')
@section('title', 'Home')

@section('content')
<section class="hero-section text-white">
    <div class="container text-center">
        <i class="bi bi-mortarboard-fill" style="font-size:4rem; opacity:0.9;"></i>
        <h1 class="fw-bold mt-3 mb-3">FYP Management System</h1>
        <p class="lead mb-4 opacity-75">Virtual University of Pakistan — Streamlining the entire Final Year Project lifecycle for students, supervisors, and administrators.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 fw-semibold">
                <i class="bi bi-person-plus me-2"></i>Student Registration
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </a>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="card h-100 p-4">
                    <i class="bi bi-people-fill text-primary mb-3" style="font-size:2.5rem;"></i>
                    <h5 class="fw-bold">Team Formation</h5>
                    <p class="text-muted small">Create teams, invite members, and manage your project group efficiently.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 p-4">
                    <i class="bi bi-file-earmark-text-fill text-success mb-3" style="font-size:2.5rem;"></i>
                    <h5 class="fw-bold">Proposal Tracking</h5>
                    <p class="text-muted small">Submit, review, and track project proposals with real-time status updates.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 p-4">
                    <i class="bi bi-flag-fill text-warning mb-3" style="font-size:2.5rem;"></i>
                    <h5 class="fw-bold">Milestone Tracking</h5>
                    <p class="text-muted small">Monitor project phases, deadlines, and progress milestones in real time.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 p-4">
                    <i class="bi bi-award-fill text-danger mb-3" style="font-size:2.5rem;"></i>
                    <h5 class="fw-bold">Evaluation System</h5>
                    <p class="text-muted small">Structured evaluation with marks, remarks, and transparent feedback.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@if($announcements->count())
<section class="py-4 bg-white">
    <div class="container">
        <h4 class="fw-bold mb-4"><i class="bi bi-megaphone-fill text-primary me-2"></i>Latest Announcements</h4>
        <div class="row g-3">
            @foreach($announcements as $a)
            <div class="col-md-6">
                <div class="card border-start border-primary border-3 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold mb-0">{{ $a->title }}</h6>
                            {!! $a->type_badge !!}
                        </div>
                        <p class="text-muted small mb-1">{{ Str::limit(strip_tags($a->content), 120) }}</p>
                        <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $a->published_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('announcements') }}" class="btn btn-outline-primary btn-sm">View All Announcements</a>
        </div>
    </div>
</section>
@endif

<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <h3 class="fw-bold mb-3">Who Uses VUFYPMS?</h3>
                <div class="d-flex gap-3 mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;"><i class="bi bi-person-fill"></i></div>
                    <div><h6 class="fw-bold mb-0">Students</h6><p class="text-muted small mb-0">Manage teams, proposals, documents, and track evaluation progress.</p></div>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;"><i class="bi bi-person-badge-fill"></i></div>
                    <div><h6 class="fw-bold mb-0">Supervisors</h6><p class="text-muted small mb-0">Review proposals, monitor progress, schedule meetings, and enter evaluations.</p></div>
                </div>
                <div class="d-flex gap-3">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;"><i class="bi bi-shield-fill"></i></div>
                    <div><h6 class="fw-bold mb-0">Administrators</h6><p class="text-muted small mb-0">Full system control: users, semesters, assignments, reports, and archives.</p></div>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div class="p-4 rounded-3" style="background:linear-gradient(135deg,#1e3a5f,#1565C0);">
                    <h5 class="text-white fw-bold mb-3">Quick Stats</h5>
                    <div class="row g-3 text-white">
                        <div class="col-6"><div class="p-3 rounded-2" style="background:rgba(255,255,255,0.1);"><h3 class="fw-bold mb-0">15+</h3><small>Modules</small></div></div>
                        <div class="col-6"><div class="p-3 rounded-2" style="background:rgba(255,255,255,0.1);"><h3 class="fw-bold mb-0">16</h3><small>DB Tables</small></div></div>
                        <div class="col-6"><div class="p-3 rounded-2" style="background:rgba(255,255,255,0.1);"><h3 class="fw-bold mb-0">4</h3><small>User Roles</small></div></div>
                        <div class="col-6"><div class="p-3 rounded-2" style="background:rgba(255,255,255,0.1);"><h3 class="fw-bold mb-0">70+</h3><small>Routes</small></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
