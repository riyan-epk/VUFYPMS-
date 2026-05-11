<div class="mb-3">
    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $announcement?->title) }}" required>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="6" required>{{ old('content', $announcement?->content) }}</textarea>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
        <select name="type" class="form-select" required>
            <option value="general" {{ old('type', $announcement?->type) === 'general' ? 'selected' : '' }}>General</option>
            <option value="deadline" {{ old('type', $announcement?->type) === 'deadline' ? 'selected' : '' }}>Deadline</option>
            <option value="evaluation" {{ old('type', $announcement?->type) === 'evaluation' ? 'selected' : '' }}>Evaluation</option>
            <option value="schedule" {{ old('type', $announcement?->type) === 'schedule' ? 'selected' : '' }}>Schedule</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Target Role <span class="text-danger">*</span></label>
        <select name="target_role" class="form-select" required>
            <option value="all" {{ old('target_role', $announcement?->target_role) === 'all' ? 'selected' : '' }}>All Users</option>
            <option value="student" {{ old('target_role', $announcement?->target_role) === 'student' ? 'selected' : '' }}>Students Only</option>
            <option value="supervisor" {{ old('target_role', $announcement?->target_role) === 'supervisor' ? 'selected' : '' }}>Supervisors Only</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Visibility</label>
        <div class="form-check form-switch mt-2">
            <input type="checkbox" name="is_public" class="form-check-input" id="isPublic" value="1" {{ old('is_public', $announcement?->is_public) ? 'checked' : '' }}>
            <label class="form-check-label" for="isPublic">Show on Public Site</label>
        </div>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Publish Date</label>
        <input type="datetime-local" name="published_at" class="form-control"
            value="{{ old('published_at', $announcement?->published_at?->format('Y-m-d\TH:i')) }}">
        <div class="form-text">Leave blank to publish immediately.</div>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Expiry Date</label>
        <input type="datetime-local" name="expires_at" class="form-control"
            value="{{ old('expires_at', $announcement?->expires_at?->format('Y-m-d\TH:i')) }}">
        <div class="form-text">Leave blank for no expiry.</div>
    </div>
</div>
