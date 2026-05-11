@extends('layouts.supervisor')
@section('title', 'Evaluations')
@section('page-title', 'Evaluation Management')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle text-success me-2"></i>Add Evaluation</div>
            <div class="card-body">
                <form method="POST" action="{{ route('supervisor.evaluations.store') }}">
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
                        <label class="form-label fw-semibold">Evaluation Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="proposal_defense" {{ old('type')==='proposal_defense'?'selected':'' }}>Proposal Defense</option>
                            <option value="progress_review" {{ old('type')==='progress_review'?'selected':'' }}>Progress Review</option>
                            <option value="final_defense" {{ old('type')==='final_defense'?'selected':'' }}>Final Defense</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Marks <span class="text-danger">*</span></label>
                            <input type="number" name="marks" class="form-control" value="{{ old('marks') }}" min="0" max="100" step="0.5" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Max Marks</label>
                            <input type="number" name="max_marks" class="form-control" value="{{ old('max_marks', 100) }}" min="1" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Evaluation Date <span class="text-danger">*</span></label>
                        <input type="date" name="evaluation_date" class="form-control" value="{{ old('evaluation_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Overall remarks...">{{ old('remarks') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Recommendations</label>
                        <textarea name="recommendations" class="form-control" rows="2" placeholder="Recommendations for improvement...">{{ old('recommendations') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-save me-1"></i>Save Evaluation</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-clipboard2-check-fill text-success me-2"></i>Evaluation History</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Team</th><th>Type</th><th>Marks</th><th>Grade</th><th>Date</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $ev)
                        <tr>
                            <td class="fw-semibold">{{ $ev->team->name }}</td>
                            <td><span class="badge bg-info-subtle text-info border border-info-subtle">{{ $ev->type_label }}</span></td>
                            <td>
                                <span class="fw-bold text-primary">{{ $ev->marks }}</span>
                                <span class="text-muted">/ {{ $ev->max_marks }}</span>
                                <div class="progress mt-1" style="height:4px;width:80px;">
                                    <div class="progress-bar bg-{{ $ev->percentage >= 60 ? 'success' : 'danger' }}" style="width:{{ $ev->percentage }}%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-{{ $ev->percentage >= 60 ? 'success' : 'danger' }}">{{ $ev->grade }}</span></td>
                            <td><small>{{ $ev->evaluation_date->format('M d, Y') }}</small></td>
                            <td><a href="{{ route('supervisor.evaluations.edit', $ev->id) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No evaluations entered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($evaluations->hasPages())
            <div class="card-footer">{{ $evaluations->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
