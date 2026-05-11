@extends('layouts.admin')
@section('title', 'Add User')
@section('page-title', 'Add New User')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-plus-fill text-success me-2"></i>Create New User</span>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    @php $user = null; @endphp
                    @include('admin.users._form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success"><i class="bi bi-person-check me-1"></i>Create User</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
