@extends('layouts.maintenance')

@section('content')

<style>
body {
    background-color: #2e2e2e;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 0;
    margin: 0;
}
h2.mb-4 {
    font-weight: 900;
    color: #f0f0f0;
    text-align: center;
    letter-spacing: 1.5px;
    margin: 40px 0 30px;
}

.container {
    background-color: #fff;
    border-radius: 16px;
    padding: 40px 30px;
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.2);
    max-width: 1000px; 
    margin: 40px auto;
}
.card h4 {
    color: #0d6efd;
    font-weight: 700;
    margin-bottom: 25px;
    font-size: 1.5rem;
    letter-spacing: 0.05em;
}
.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    font-weight: 600;
    box-shadow: 0 3px 6px rgba(13, 110, 253, 0.4);
    transition: all 0.3s ease;
}
.btn-primary:hover, .btn-primary:focus {
    background-color: #0b5ed7;
    border-color: #0a58ca;
    box-shadow: 0 5px 12px rgba(11, 94, 215, 0.6);
    transform: translateY(-2px);
}
.btn-sm {
    margin-right: 6px;
    transition: background-color 0.2s ease;
}
.btn-sm.btn-info:hover {
    background-color: #0a58ca;
}
.btn-sm.btn-danger:hover {
    background-color: #c82333;
}
form.mb-4 input.form-control {
    border-radius: 10px;
    border: 1.5px solid #ced4da;
    transition: border-color 0.3s ease;
}
form.mb-4 input.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 8px rgba(13, 110, 253, 0.4);
}
.alert-success,
.alert-danger {
    margin-top: 15px;
    border-radius: 10px;
    font-weight: 600;
    transition: opacity 0.6s ease, transform 0.6s ease, max-height 0.6s ease;
    overflow: hidden;
}

/* Table styles */
.table-responsive {
    margin-top: 35px;
    overflow-x: auto;
}
table {
    table-layout: fixed;
    width: 100%;
    border-collapse: collapse;
}
table th {
    background-color: #0d6efd;
    color: white;
    text-align: center;
    font-weight: 700;
    letter-spacing: 0.05em;
    padding: 15px 12px;
    user-select: none;
    white-space: nowrap;
    overflow: hidden;
}
table th:nth-child(1), table td:nth-child(1) { width: 12%; }
table th:nth-child(2), table td:nth-child(2) { width: 18%; }
table th:nth-child(3), table td:nth-child(3) { width: 16%; }
table th:nth-child(4), table td:nth-child(4) { width: 14%; }
table th:nth-child(5), table td:nth-child(5) { width: 20%; }
table th:nth-child(6), table td:nth-child(6) { width: 10%; }
table th:nth-child(7), table td:nth-child(7) { width: 13%; }
table td {
    vertical-align: middle;
    padding: 12px 10px;
    text-align: center;
    font-size: 0.95rem;
    color: #333;
    word-wrap: break-word;
    white-space: normal;
}
.table-danger {
    background-color: #f8d7da !important;
    color: #842029;
    font-weight: 600;
}
tbody tr:hover {
    background-color: #e9f0ff;
    cursor: pointer;
    transition: background-color 0.25s ease;
}

/* Badges */
.badge {
    font-size: 0.9em;
    padding: 0.5em 0.8em;
    border-radius: 20px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.badge-pending { background-color: #f0ad4e; color: #212529; }
.badge-completed { background-color: #87CEEB; color: #212529; }
.badge-overdue { background-color: #e74c3c; color: #fff; }
.badge.bg-secondary { background-color: #e2e3e5; color: #41464b; }

/* Responsive */
@media (max-width: 767px) {
    .container { padding: 30px 20px; max-width: 95%; }
    form.mb-4 .row.g-2 > div { flex: 100% !important; max-width: 100% !important; }
    .btn.btn-primary.mt-2 { width: 100%; }
}

/* Search Input + Clear Button */
#searchInput {
    height: 45px;
    font-size: 15px;
    padding-right: 40px; /* space para hindi dumikit ang text sa X */
}
#clearSearch {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    font-size: 18px;
    color: #888;
    cursor: pointer;
    display: none;
}
#clearSearch:hover {
    color: #dc3545; /* red kapag hover */
}
</style>

<div class="container mt-4">
    <h2 class="mb-4" style="color: #0d6efd;">🛠 Maintenance Schedule</h2>

    {{-- Search --}}
    <form action="{{ route('maintenance.index') }}" method="GET" class="mb-4" id="searchForm">
        <div class="position-relative" style="width:100%; max-width:500px; margin:auto;">
            <input type="text" name="search" id="searchInput"
                   class="form-control rounded-pill ps-3 pe-5"
                   placeholder="Search equipment, type, or technician..."
                   value="{{ request('search') }}">

            {{-- ❌ Clear button --}}
            <button type="button" id="clearSearch">&times;</button>
        </div>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </form>

    {{-- Add Form --}}
    <div class="card mb-4 p-3">
        <h4><i class="fas fa-plus-circle"></i> Add New Schedule</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('maintenance.store') }}" method="POST" class="row gy-3 gx-4 mt-1">
            @csrf
            <div class="col-md-6">
                <label for="equipment_name" class="form-label">Equipment Name</label>
                <input type="text" class="form-control" id="equipment_name" name="equipment_name"
                       value="{{ old('equipment_name') }}" required>
            </div>
            <div class="col-md-6">
                <label for="maintenance_type_id" class="form-label">Maintenance Type</label>
                <select class="form-control" id="maintenance_type_id" name="maintenance_type_id" required>
                    <option value="">-- Select Maintenance Type --</option>
                    @foreach ($maintenanceTypes as $type)
                        <option value="{{ $type->maintenance_types_id }}" 
                            {{ old('maintenance_type_id') == $type->maintenance_types_id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="scheduled_date" class="form-label">Scheduled Date</label>
                <input type="date" class="form-control" id="scheduled_date" name="scheduled_date"
                       value="{{ old('scheduled_date') }}" required>
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-12">
                <label for="technician_name" class="form-label">Assigned Technician</label>
                <input list="technicians" class="form-control" id="technician_name" name="technician_name"
                       value="{{ old('technician_name') }}" required>
                <datalist id="technicians">
                    @foreach($technicians as $technician)
                        <option value="{{ $technician->name }}">
                    @endforeach
                </datalist>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary mt-2 rounded-pill">
                    <i class="fas fa-plus"></i> Add Schedule
                </button>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Equipment</th>
                    <th>Maintenance Type</th>
                    <th>Scheduled Date</th>
                    <th>Status</th>
                    <th>Assigned Technician</th>
                    <th>Days Left</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    @php
                        $scheduledDate = \Carbon\Carbon::parse($schedule->scheduled_date);
                        $daysLeft = now()->diffInDays($scheduledDate, false);
                        $isOverdue = $daysLeft < 0 && $schedule->status == 'pending';
                    @endphp
                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                        <td>{{ $schedule->equipment_name }}</td>
                        <td>{{ $schedule->maintenanceType->name ?? 'N/A' }}</td>
                        <td>{{ $scheduledDate->format('F d, Y') }}</td>
                        <td>
                            @if($schedule->status == 'pending')
                                <span class="badge badge-pending"><i class="fas fa-clock me-1"></i> Pending</span>
                            @elseif($schedule->status == 'completed')
                                <span class="badge badge-completed"><i class="fas fa-check-circle me-1"></i> Completed</span>
                            @elseif($schedule->status == 'overdue')
                                <span class="badge badge-overdue"><i class="fas fa-exclamation-triangle me-1"></i> Overdue</span>
                            @else
                                <span class="badge bg-secondary">Unknown</span>
                            @endif
                        </td>
                        <td>{{ $schedule->technician_name }}</td>
                        <td>
                            @php $daysLeftInt = (int) round($daysLeft); @endphp
                            @if($daysLeftInt > 0)
                                <span class="text-primary">{{ $daysLeftInt }} day(s)</span>
                            @elseif($daysLeftInt == 0)
                                <span class="text-warning">Today</span>
                            @else
                                <span class="text-danger">{{ abs($daysLeftInt) }} day(s) overdue</span>
                            @endif
                        </td>
                        <td class="d-flex align-items-center">
                            <a href="{{ route('maintenance.edit', $schedule->maintenance_sched_id) }}" class="btn btn-sm btn-info me-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('maintenance.destroy', $schedule->maintenance_sched_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger px-2 py-1 delete-btn">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($schedules->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center text-muted">No maintenance schedules found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $schedules->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

{{-- Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    let deleteForm;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            deleteForm = this.closest('form');
            deleteModal.show();
        });
    });

    confirmDeleteBtn.addEventListener('click', function() {
        if (deleteForm) deleteForm.submit();
    });
});

document.addEventListener("DOMContentLoaded", function() {
    let alerts = document.querySelectorAll(".alert-success, .alert-danger");
    alerts.forEach(alertBox => {
        setTimeout(() => {
            alertBox.style.maxHeight = alertBox.scrollHeight + "px";
            alertBox.style.opacity = "1";
            setTimeout(() => {
                alertBox.style.opacity = "0";
                alertBox.style.transform = "translateY(-20px)";
                alertBox.style.maxHeight = "0";
                setTimeout(() => alertBox.remove(), 600);
            }, 4000);
        }, 100);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const clearBtn = document.getElementById("clearSearch");
    const searchForm = document.getElementById("searchForm");

    function toggleClearBtn() {
        clearBtn.style.display = searchInput.value ? "block" : "none";
    }

    clearBtn.addEventListener("click", function () {
        searchInput.value = "";
        searchForm.submit(); // balik sa default
    });

    searchInput.addEventListener("input", toggleClearBtn);

    toggleClearBtn();
});
</script>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteConfirmModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this maintenance schedule? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger rounded-pill">Yes, Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection
