@extends('layouts.maintenance')

@section('content')
<style>
    body {
        background-color: #f8f9fa;
        font-family: "Segoe UI", sans-serif;
    }

    h2 {
        font-weight: 700;
        color: #2c3e50;
    }

    .card-custom {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        padding: 20px;
    }

    .table th {
        background-color: #2c3e50;
        color: #fff;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
    }

    .search-bar {
        max-width: 400px;
        position: relative;
    }

    .clear-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        font-size: 18px;
        color: #999;
        cursor: pointer;
        display: none;
    }

    .clear-btn:hover {
        color: #333;
    }

    .badge {
        font-size: 0.85rem;
        padding: 6px 10px;
        border-radius: 8px;
    }
</style>

<div class="container py-4">
    <h2 class="mb-4">📋 Maintenance History Log</h2>

    <div class="card-custom">
        <!-- Search Form -->
        <form method="GET" action="{{ route('maintenance-history') }}" class="mb-3 d-flex">
            <div class="search-bar flex-grow-1 me-2">
                <input type="text" name="search" id="searchInput" class="form-control"
                       placeholder="🔍 Search equipment, technician, or type"
                       value="{{ request('search') }}">
                <button type="button" id="clearBtn" class="clear-btn">&times;</button>
            </div>
            <button type="submit" class="btn btn-primary px-4">Search</button>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Equipment</th>
                        <th>Maintenance Type</th>
                        <th>Scheduled Date</th>
                        <th>Technician</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($historyLogs as $log)
                        <tr>
                            <td class="fw-bold">{{ $log->maintenance_sched_id }}</td>
                            <td>{{ $log->equipment_name }}</td>
                            <td>{{ optional($log->maintenanceType)->name ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->scheduled_date)->format('M d, Y') }}</td>
                            <td>{{ $log->technician_name }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $log->status === 'completed' ? 'primary' : 
                                    ($log->status === 'pending' ? 'warning' : 'danger') 
                                }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                🚫 No maintenance history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $historyLogs->links() }}
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearBtn');

    // Show or hide the X button depende sa laman ng input
    searchInput.addEventListener('input', () => {
        clearBtn.style.display = searchInput.value.length > 0 ? 'block' : 'none';
    });

    // Clear input + redirect back to dashboard
    clearBtn.addEventListener('click', () => {
        window.location.href = "{{ route('maintenance-history') }}";
    });

    // Initial check kung may value na galing sa request
    if (searchInput.value.length > 0) {
        clearBtn.style.display = 'block';
    }
</script>
@endsection
