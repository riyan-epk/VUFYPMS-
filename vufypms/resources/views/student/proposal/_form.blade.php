<div class="mb-3">
    <label class="form-label fw-semibold">Project Title <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $proposal?->title) }}"
        placeholder="e.g., Smart Library Management System" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Project Domain <span class="text-danger">*</span></label>
    <select name="domain_id" class="form-select @error('domain_id') is-invalid @enderror" required>
        <option value="">-- Select Domain --</option>
        @foreach($domains as $d)
            <option value="{{ $d->id }}" {{ old('domain_id', $proposal?->domain_id) == $d->id ? 'selected' : '' }}>
                {{ $d->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Abstract <span class="text-danger">*</span></label>
    <textarea name="abstract" class="form-control @error('abstract') is-invalid @enderror" rows="5"
        placeholder="Provide a detailed description of your project (minimum 100 characters)..." required>{{ old('abstract', $proposal?->abstract) }}</textarea>
    <div class="form-text">Minimum 100 characters required.</div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Tools & Technologies <span class="text-danger">*</span></label>
    <textarea name="tools_technologies" class="form-control @error('tools_technologies') is-invalid @enderror" rows="3"
        placeholder="e.g., Laravel, MySQL, Bootstrap, jQuery, XAMPP..." required>{{ old('tools_technologies', $proposal?->tools_technologies) }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Project Objectives</label>
    <textarea name="objectives" class="form-control @error('objectives') is-invalid @enderror" rows="4"
        placeholder="List the main objectives of your project...">{{ old('objectives', $proposal?->objectives) }}</textarea>
</div>
