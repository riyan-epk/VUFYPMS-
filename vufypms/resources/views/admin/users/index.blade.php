@extends('layouts.admin')
@section('title', 'Users')
@section('page-title', 'User Management')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, or VU-ID..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="student" {{ request('role')==='student'?'selected':'' }}>Student</option>
                    <option value="supervisor" {{ request('role')==='supervisor'?'selected':'' }}>Supervisor</option>
                    <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                    <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success w-100"><i class="bi bi-person-plus me-1"></i>Add User</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>User</th><th>Role</th><th>VU-ID</th><th>Department</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $u->profile_photo_url }}" class="rounded-circle" width="34" height="34" style="object-fit:cover;">
                            <div>
                                <div class="fw-semibold">{{ $u->name }}</div>
                                <small class="text-muted">{{ $u->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $u->role==='admin'?'bg-danger':($u->role==='supervisor'?'bg-success':'bg-primary') }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td><code>{{ $u->vu_id ?? '—' }}</code></td>
                    <td><small>{{ $u->department ?? '—' }}</small></td>
                    <td>
                        <span class="badge {{ $u->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $u->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td><small>{{ $u->created_at->format('M d, Y') }}</small></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.users.toggle-active', $u->id) }}">
                                @csrf
                                <button class="btn btn-sm {{ $u->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}" data-bs-toggle="tooltip" title="{{ $u->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="bi bi-{{ $u->is_active ? 'person-slash' : 'person-check' }}"></i>
                                </button>
                            </form>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="confirm-delete">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer">{{ $users->links() }}</div>
    @endif
</div>
@endsection
