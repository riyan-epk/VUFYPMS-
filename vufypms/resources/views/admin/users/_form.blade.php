<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user?->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="student" {{ old('role', $user?->role) === 'student' ? 'selected' : '' }}>Student</option>
            <option value="supervisor" {{ old('role', $user?->role) === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
            <option value="admin" {{ old('role', $user?->role) === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">VU-ID</label>
        <input type="text" name="vu_id" class="form-control @error('vu_id') is-invalid @enderror" value="{{ old('vu_id', $user?->vu_id) }}" placeholder="BC200400001">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Phone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user?->phone) }}" placeholder="+92 300 1234567">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Department</label>
        <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department', $user?->department) }}" placeholder="Computer Science">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Designation</label>
        <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" value="{{ old('designation', $user?->designation) }}" placeholder="Assistant Professor">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Password {{ isset($user) ? '(leave blank to keep current)' : '' }} <span class="{{ !isset($user) ? 'text-danger' : '' }}">{{ !isset($user) ? '*' : '' }}</span></label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ !isset($user) ? 'required' : '' }} placeholder="Min 8 characters">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password">
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="isActive">Account Active</label>
        </div>
    </div>
</div>
