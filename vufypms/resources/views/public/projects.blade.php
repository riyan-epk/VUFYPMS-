@extends('layouts.guest')
@section('title', 'Published Projects')
@section('content')
<div class="container py-5">
    <h3 class="fw-bold mb-1"><i class="bi bi-search text-primary me-2"></i>Search Published Projects</h3>
    <p class="text-muted mb-4">Browse approved final year projects for reference and idea exploration.</p>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('projects.search') }}" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by title or abstract..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="domain" class="form-select">
                        <option value="">All Domains</option>
                        @foreach($domains as $d)
                            <option value="{{ $d->id }}" {{ request('domain') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse($projects as $p)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $p->domain->name ?? 'N/A' }}</span>
                        <span class="badge bg-success">Approved</span>
                    </div>
                    <h6 class="fw-bold mb-2">{{ $p->title }}</h6>
                    <p class="text-muted small mb-3">{{ Str::limit($p->abstract, 120) }}</p>
                    <div class="text-muted small">
                        <i class="bi bi-people me-1"></i>
                        {{ $p->team->members->pluck('student.name')->implode(', ') }}
                    </div>
                    <div class="text-muted small mt-1">
                        <i class="bi bi-tools me-1"></i>{{ Str::limit($p->tools_technologies, 60) }}
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-folder2-open text-muted" style="font-size:3rem;"></i>
            <p class="text-muted mt-2">No projects found matching your criteria.</p>
        </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $projects->withQueryString()->links() }}</div>
</div>
@endsection
