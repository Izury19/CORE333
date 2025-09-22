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

        {{-- Equipment Name --}}
        <div class="mb-3">
            <label for="equipment_name" class="form-label">Equipment Name</label>
            <input type="text" name="equipment_name" class="form-control" value="{{ old('equipment_name') }}" required>
        </div>

        {{-- Maintenance Type --}}
        <div class="mb-3">
            <label for="maintenance_type_id" class="form-label">Maintenance Type</label>
            <select name="maintenance_type_id" id="maintenance_type_id" class="form-select" required>
                <option value="">-- Select Maintenance Type --</option>
                @foreach($maintenanceTypes as $type)
                    <option value="{{ $type->id }}" {{ old('maintenance_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Scheduled Date --}}
        <div class="mb-3">
            <label for="scheduled_date" class="form-label">Scheduled Date</label>
            <input type="date" name="scheduled_date" class="form-control" value="{{ old('scheduled_date') }}" required>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        {{-- Technician --}}
        <div class="mb-3">
            <label for="technician_name" class="form-label">Assigned Technician</label>
            <select name="technician_name" id="technician_name" class="form-select" required>
                <option value="">-- Select Technician --</option>
                @foreach($technicians as $technician)
                    <option value="{{ $technician->name }}" {{ old('technician_name') == $technician->name ? 'selected' : '' }}>
                        {{ $technician->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Buttons --}}
        <button type="submit" class="btn btn-success">Save Schedule</button>
        <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
