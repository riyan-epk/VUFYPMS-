@extends('layouts.admin')
@section('title', 'Reports')
@section('page-title', 'Analytics & Reports')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Filter by Semester</label>
                <select name="semester_id" class="form-select">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $s)
                    <option value="{{ $s->id }}" {{ $semesterId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Apply</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-semibold"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Proposal Status</div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="proposalStatusChart" style="max-height:240px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-semibold"><i class="bi bi-pie-chart-fill text-warning me-2"></i>Team Status</div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="teamStatusChart" style="max-height:240px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-bar-chart-fill text-success me-2"></i>Projects by Domain</div>
            <div class="card-body">
                <canvas id="domainChart" style="max-height:200px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header fw-semibold"><i class="bi bi-people-fill text-info me-2"></i>Supervisor Workload</div>
            <div class="list-group list-group-flush">
                @forelse($supervisorWorkload as $sv)
                <div class="list-group-item d-flex justify-content-between">
                    <div class="small fw-semibold">{{ $sv->name }}</div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress" style="width:80px;height:6px;">
                            <div class="progress-bar bg-primary" style="width:{{ min($sv->team_count * 20, 100) }}%"></div>
                        </div>
                        <span class="badge bg-primary">{{ $sv->team_count }}</span>
                    </div>
                </div>
                @empty
                <div class="list-group-item text-center text-muted">No data.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if($evaluationSummary->count())
<div class="card">
    <div class="card-header fw-semibold"><i class="bi bi-clipboard2-data-fill text-danger me-2"></i>Evaluation Summary</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Type</th><th>Count</th><th>Avg Marks</th><th>Highest</th><th>Lowest</th></tr>
            </thead>
            <tbody>
                @foreach($evaluationSummary as $ev)
                <tr>
                    <td class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $ev->type)) }}</td>
                    <td><span class="badge bg-primary">{{ $ev->count }}</span></td>
                    <td>{{ round($ev->avg_marks, 1) }}</td>
                    <td class="text-success fw-bold">{{ $ev->max_marks }}</td>
                    <td class="text-danger fw-bold">{{ $ev->min_marks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
const proposalData = @json($proposalStatusData);
const teamData     = @json($teamStatusData);
const domainData   = @json($domainData);
const colors = ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1','#0dcaf0','#6c757d'];

new Chart(document.getElementById('proposalStatusChart'), {
    type: 'doughnut',
    data: { labels: Object.keys(proposalData).map(s=>s.replace(/_/g,' ')), datasets: [{ data: Object.values(proposalData), backgroundColor: colors }] },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('teamStatusChart'), {
    type: 'doughnut',
    data: { labels: Object.keys(teamData).map(s=>s.replace(/_/g,' ')), datasets: [{ data: Object.values(teamData), backgroundColor: colors }] },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('domainChart'), {
    type: 'bar',
    data: { labels: domainData.map(d=>d.name), datasets: [{ label: 'Projects', data: domainData.map(d=>d.count), backgroundColor: '#198754', borderRadius: 6 }] },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
@endpush
