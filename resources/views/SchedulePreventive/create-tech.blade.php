@extends('layouts.maintenance')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">➕ Add Technician</h2>

    <form action="{{ route('technicians.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Profile Picture</label>
            <input type="file" name="image" class="form-control">
            <small class="text-muted">Optional. Allowed: jpeg, png, jpg, gif. Max size: 2MB.</small>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('technicians.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
