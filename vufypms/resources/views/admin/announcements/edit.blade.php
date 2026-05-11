@extends('layouts.admin')
@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-pencil-fill text-warning me-2"></i>Edit Announcement</span>
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.announcements.update', $announcement->id) }}">
                    @csrf @method('PUT')
                    @include('admin.announcements._form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Update</button>
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
