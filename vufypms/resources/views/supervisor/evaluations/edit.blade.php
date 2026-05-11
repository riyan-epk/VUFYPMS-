@extends('layouts.supervisor')
@section('title', 'Edit Evaluation')
@section('page-title', 'Edit Evaluation')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-pencil-fill text-warning me-2"></i>Edit Evaluation</span>
                <a href="{{ route('supervisor.evaluations.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
            </div>
            <div class="card-body">
                <div class="alert alert-info small mb-3">
                    <i class="bi bi-info-circle me-1"></i>Editing evaluation for team: <strong>{{ $evaluation->team->name }}</strong> — Type: <strong>{{ $evaluation->type_label }}</strong>
                </div>
                <form method="POST" action="{{ route('supervisor.evaluations.update', $evaluation->id) }}">
                    @csrf @method('PUT')
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Marks <span class="text-danger">*</span></label>
                            <input type="number" name="marks" class="form-control" value="{{ old('marks', $evaluation->marks) }}" min="0" step="0.5" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Max Marks</label>
                            <input type="number" name="max_marks" class="form-control" value="{{ old('max_marks', $evaluation->max_marks) }}" min="1" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Evaluation Date <span class="text-danger">*</span></label>
                        <input type="date" name="evaluation_date" class="form-control" value="{{ old('evaluation_date', $evaluation->evaluation_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $evaluation->remarks) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Recommendations</label>
                        <textarea name="recommendations" class="form-control" rows="2">{{ old('recommendations', $evaluation->recommendations) }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning flex-grow-1"><i class="bi bi-save me-1"></i>Update Evaluation</button>
                        <a href="{{ route('supervisor.evaluations.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
