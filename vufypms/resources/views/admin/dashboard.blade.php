@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1e3a5f,#1565C0);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Total Students</div><h3 class="fw-bold mt-1 mb-0">{{ $totalStudents }}</h3></div>
                <i class="bi bi-mortarboard-fill opacity-50" style="font-size:2.5rem;"></i>
            </div>
            <a href="{{ route('admin.users.index', ['role'=>'student']) }}" class="text-white-50 small">View all <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1b5e20,#388e3c);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Supervisors</div><h3 class="fw-bold mt-1 mb-0">{{ $totalSupervisors }}</h3></div>
                <i class="bi bi-person-badge-fill opacity-50" style="font-size:2.5rem;"></i>
            </div>
            <a href="{{ route('admin.users.index', ['role'=>'supervisor']) }}" class="text-white-50 small">View all <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#e65100,#f57c00);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Total Teams</div><h3 class="fw-bold mt-1 mb-0">{{ $totalTeams }}</h3></div>
                <i class="bi bi-diagram-3-fill opacity-50" style="font-size:2.5rem;"></i>
            </div>
            <a href="{{ route('admin.teams.index') }}" class="text-white-50 small">View all <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#4a148c,#7b1fa2);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Active Semester</div><h5 class="fw-bold mt-1 mb-0">{{ $activeSemester?->name ?? 'None' }}</h5></div>
                <i class="bi bi-calendar3 opacity-50" style="font-size:2.5rem;"></i>
            </div>
            <a href="{{ route('admin.semesters.index') }}" class="text-white-50 small">Manage <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-semibold"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Proposal Status Distribution</div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="proposalChart" style="max-height:220px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-semibold"><i class="bi bi-bar-chart-fill text-success me-2"></i>Projects by Domain</div>
            <div class="card-body">
                <canvas id="domainChart" style="max-height:220px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history text-warning me-2"></i>Recent Teams</span>
                <a href="{{ route('admin.teams.index') }}" class="btn btn-sm btn-outline-warning">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Team</th><th>Supervisor</th><th>Proposal</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($recentTeams as $t)
                        <tr>
                            <td><div class="fw-semibold">{{ $t->name }}</div><small class="text-muted">{{ $t->semester->name ?? '' }}</small></td>
                            <td><small>{{ $t->supervisor?->name ?? '<span class="text-warning">Unassigned</span>' }}</small></td>
                            <td>{!! $t->proposal ? $t->proposal->status_badge : '<span class="badge bg-secondary">None</span>' !!}</td>
                            <td>{!! $t->status_badge !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-person-badge-fill text-info me-2"></i>Supervisor Workload</div>
            <div class="list-group list-group-flush">
                @forelse($supervisorWorkload as $sv)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold small">{{ $sv->name }}</div>
                        <small class="text-muted">{{ $sv->designation ?? 'Supervisor' }}</small>
                    </div>
                    <span class="badge bg-primary rounded-pill">{{ $sv->team_count }} team(s)</span>
                </div>
                @empty
                <div class="list-group-item text-center text-muted">No supervisors with teams yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const proposalData = @json($proposalStats);
const domainData   = @json($domainData);

new Chart(document.getElementById('proposalChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(proposalData).map(s => s.replace('_',' ').replace(/\b\w/g,c=>c.toUpperCase())),
        datasets: [{ data: Object.values(proposalData), backgroundColor: ['#6c757d','#0d6efd','#ffc107','#dc3545','#198754','#343a40'], borderWidth: 2 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('domainChart'), {
    type: 'bar',
    data: {
        labels: domainData.map(d => d.name),
        datasets: [{ label: 'Projects', data: domainData.map(d => d.count), backgroundColor: '#1565C0', borderRadius: 6 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
@endpush
