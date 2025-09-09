@extends('layouts.contract')

@section('content')
<style>
    body {
        background-color: #2e2e2e;
        font-family: 'Segoe UI', sans-serif;
        padding: 20px;
    }
    .container {
        background: #f9f9f9;
        padding: 2.5rem 3rem;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        max-width: 1200px; /* Palaki */
        margin-top: 50px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h2 {
        color: #2c3e50;
        margin-bottom: 1.8rem;
        font-weight: 700;
        letter-spacing: 1px;
    }

    form {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.5rem 2rem;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: 600;
        color: #34495e;
        margin-bottom: 0.4rem;
        white-space: nowrap;
    }

    input.form-control,
    select.form-select {
        border: 1.5px solid #ccc;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    input.form-control:focus,
    select.form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 6px #28a745;
        outline: none;
    }

    .btn-group {
        grid-column: span 5; /* Full width sa ilalim */
        margin-top: 1.5rem;
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
    }

    button.btn-success {
        background-color: #28a745;
        border: none;
        padding: 0.6rem 1.6rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        white-space: nowrap;
    }

    button.btn-success:hover {
        background-color: #218838;
    }

    a.btn-secondary {
        background-color: #6c757d;
        color: white;
        padding: 0.6rem 1.6rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        transition: background-color 0.3s ease;
        white-space: nowrap;
    }

    a.btn-secondary:hover {
        background-color: #5a6268;
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        form {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .btn-group {
            justify-content: center;
        }
    }
</style>

<div class="container">
    <h2>Edit Maintenance Schedule</h2>

    <form action="{{ route('maintenance.update', $schedule->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="equipment_name">Equipment Name</label>
            <input type="text" class="form-control" id="equipment_name" name="equipment_name" value="{{ $schedule->equipment_name }}" required>
        </div>

        <div class="form-group">
            <label for="maintenance_type_id">Maintenance Type</label>
            <select name="maintenance_type_id" class="form-select" id="maintenance_type_id" required>
                <option value="">-- Select Maintenance Type --</option>
                @foreach ($maintenanceTypes as $type)
                    <option value="{{ $type->id }}" {{ $schedule->maintenance_type_id == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="scheduled_date">Scheduled Date</label>
            <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" value="{{ $schedule->scheduled_date }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="pending" {{ $schedule->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $schedule->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div class="form-group">
            <label for="technician_name">Assigned Technician</label>
            <select name="technician_name" class="form-select" id="technician_name" required>
                <option value="">-- Select Technician --</option>
                @foreach ($technicians as $technician)
                    <option value="{{ $technician->name }}" {{ $schedule->technician_name == $technician->name ? 'selected' : '' }}>
                        {{ $technician->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="btn-group">
            <button type="submit" class="btn btn-success">Update Schedule</button>
            <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection
