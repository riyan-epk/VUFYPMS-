@extends('layouts.admin')
@section('title', 'Archive')
@section('page-title', 'Project Archive')

@section('content')
<div class="card mb-3">
    <div class="card-header fw-semibold"><i class="bi bi-archive-fill text-secondary me-2"></i>Past Semesters</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Semester</th><th>Period</th><th>Teams</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse($semesters as $s)
                <tr>
                    <td class="fw-semibold">{{ $s->name }}</td>
                    <td><small class="text-muted">{{ $s->start_date->format('M d, Y') }} — {{ $s->end_date->format('M d, Y') }}</small></td>
                    <td><span class="badge bg-primary">{{ $s->teams_count }}</span></td>
                    <td><span class="badge bg-secondary">Archived</span></td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-3">No past semesters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($semesters->hasPages())<div class="card-footer">{{ $semesters->links() }}</div>@endif
</div>

<div class="card">
    <div class="card-header fw-semibold"><i class="bi bi-folder2 text-secondary me-2"></i>Archived Teams & Projects</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Team</th>
                    <th>Project Title</th>
                    <th>Domain</th>
                    <th>Supervisor</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody>
                @forelse($archivedTeams as $t)
                <tr>
                    <td class="fw-semibold">{{ $t->name }}</td>
                    <td><small>{{ $t->proposal?->title ?? '—' }}</small></td>
                    <td>
                        @if($t->proposal?->domain)
                            <span class="badge bg-info-subtle text-info border border-info-subtle">{{ $t->proposal->domain->name }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td><small>{{ $t->supervisor?->name ?? '—' }}</small></td>
                    <td><small>{{ $t->semester?->name ?? '—' }}</small></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2 opacity-25"></i>No archived teams found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($archivedTeams->hasPages())<div class="card-footer">{{ $archivedTeams->links() }}</div>@endif
</div>
@endsection
