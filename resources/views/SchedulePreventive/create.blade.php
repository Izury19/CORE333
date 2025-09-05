@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Add Maintenance Schedule</h2>

    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> May problema sa input mo.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('maintenance.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="equipment_name" class="form-label">Equipment Name</label>
            <input type="text" name="equipment_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Maintenance Type</label>
            <input type="text" name="type" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="scheduled_date" class="form-label">Scheduled Date</label>
            <input type="date" name="scheduled_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="pending" selected>Pending</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <div class="mb-3">
    <label for="technician_name" class="form-label">Assigned Technician</label>
    
    {{-- Technician dropdown auto fill --}}
    <select name="technician_name" class="form-select" required>
        <option value="">-- Select Technician --</option>
        @foreach($technicians as $technician)
            <option value="{{ $technician->name }}"
                {{ old('technician_name') == $technician->name ? 'selected' : '' }}>
                {{ $technician->name }}
            </option>
        @endforeach
    </select>
</div>

        <button type="submit" class="btn btn-success">Save Schedule</button>
        <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
