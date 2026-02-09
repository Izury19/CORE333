@extends('layouts.app')

@section('content')
<style>
.maintenance-dashboard {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1.5rem;
}
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.dashboard-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.dashboard-title svg {
    width: 1.5rem;
    height: 1.5rem;
    color: #3b82f6;
}
.search-container {
    position: relative;
    min-width: 300px;
    max-width: 400px;
}
.search-input {
    width: 100%;
    padding: 0.75rem 1.25rem 0.75rem 2.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background-color: #f8fafc;
}
.search-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background-color: white;
}
.search-icon {
    position: absolute;
    left: 0.875rem;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    width: 1rem;
    height: 1rem;
}
.action-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background-color: #3b82f6;
    color: white;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    text-decoration: none;
}
.action-button:hover {
    background-color: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
.table-container {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    border: 1px solid #e2e8f0;
}
.table-responsive {
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
}
thead {
    background-color: #f8fafc;
}
th {
    padding: 1rem 1.25rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    border-bottom: 2px solid #e2e8f0;
}
tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.2s ease;
}
tbody tr:last-child {
    border-bottom: none;
}
tbody tr:hover {
    background-color: #f8fafc;
}
td {
    padding: 1rem 1.25rem;
    font-size: 0.875rem;
    color: #334155;
}
.equipment-name {
    font-weight: 600;
    color: #1e293b;
}
.recurring-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background-color: #dbeafe;
    color: #1d4ed8;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 0.25rem;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 80px;
    text-align: center;
}
.status-pending { 
    background-color: #fef3c7; 
    color: #92400e; 
}
.status-completed { 
    background-color: #dcfce7; 
    color: #166534; 
}
.priority-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}
.priority-critical { 
    background-color: #fee2e2; 
    color: #dc2626; 
}
.priority-high { 
    background-color: #ffedd5; 
    color: #ea580c; 
}
.priority-medium { 
    background-color: #fef9c3; 
    color: #ca8a04; 
}
.priority-low { 
    background-color: #dcfce7; 
    color: #166534; 
}
.action-links {
    display: flex;
    gap: 0.75rem;
}
.action-link {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    transition: all 0.2s ease;
}
.edit-link {
    color: #3b82f6;
    background-color: #eff6ff;
}
.edit-link:hover {
    color: #2563eb;
    background-color: #dbeafe;
}
.delete-link {
    color: #dc2626;
    background-color: #fef2f2;
}
.delete-link:hover {
    color: #b91c1c;
    background-color: #fee2e2;
}
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}
.empty-state svg {
    width: 4rem;
    height: 4rem;
    margin-bottom: 1rem;
    color: #cbd5e1;
}
.pagination-container {
    padding: 1.25rem 1.25rem 0;
    border-top: 1px solid #e2e8f0;
}
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.25rem;
}
.pagination a,
.pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}
.pagination a {
    color: #3b82f6;
    background-color: #eff6ff;
}
.pagination a:hover {
    background-color: #dbeafe;
}
.pagination .active {
    background-color: #3b82f6;
    color: white;
}
.pagination .disabled {
    color: #94a3b8;
    background-color: #f1f5f9;
    cursor: not-allowed;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: stretch;
    }
    .search-container {
        min-width: 100%;
    }
    .action-button {
        justify-content: center;
    }
    th, td {
        padding: 0.75rem 0.5rem;
        font-size: 0.8125rem;
    }
    .equipment-name {
        font-size: 0.875rem;
    }
}
</style>

<div class="maintenance-dashboard">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Maintenance Schedule
        </h1>
        
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <form method="GET" action="{{ route('maintenance.index') }}">
                <input type="text" 
                       name="search" 
                       class="search-input"
                       placeholder="Search equipment or maintenance type..."
                       value="{{ request('search') }}">
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Equipment</th>
                        <th>Type</th>
                        <th>Scheduled Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>AI Risk</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                    <tr>
                        <td>
                            <div class="equipment-name">{{ $schedule->equipment_name }}</div>
                            @if($schedule->is_recurring)
                                <span class="recurring-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="12" height="12">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Recurring
                                </span>
                            @endif
                        </td>
                        <td>{{ $schedule->maintenanceType->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('M d, Y') }}</td>
                        <td>
                            <span class="priority-badge priority-{{ $schedule->priority }}">
                                {{ ucfirst($schedule->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $schedule->status }}">
                                {{ ucfirst($schedule->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($schedule->ai_risk_score > 0)
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ number_format($schedule->ai_risk_score * 100, 0) }}%
                                    </span>
                                    @if($schedule->ai_risk_score >= 0.8)
                                        <span class="w-2 h-2 bg-red-500 rounded-full" title="High Risk"></span>
                                    @elseif($schedule->ai_risk_score >= 0.6)
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full" title="Medium Risk"></span>
                                    @else
                                        <span class="w-2 h-2 bg-green-500 rounded-full" title="Low Risk"></span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="{{ route('maintenance.edit', $schedule->maintenance_sched_id) }}" 
                                   class="action-link edit-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('maintenance.destroy', $schedule->maintenance_sched_id) }}" 
                                      method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="action-link delete-link"
                                            onclick="return confirm('Are you sure you want to delete this maintenance schedule? This action cannot be undone.')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="font-semibold text-lg mb-2">No maintenance schedules found</h3>
                                <p>Get started by creating your first maintenance schedule.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
        <div class="pagination-container">
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($schedules->onFirstPage())
                    <span class="disabled">&laquo; Previous</span>
                @else
                    <a href="{{ $schedules->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($schedules->links()->elements[0] as $page => $url)
                    @if ($page == $schedules->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($schedules->hasMorePages())
                    <a href="{{ $schedules->nextPageUrl() }}" rel="next">Next &raquo;</a>
                @else
                    <span class="disabled">Next &raquo;</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="mt-6 text-right">
        <a href="{{ route('maintenance.create') }}" class="action-button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Schedule
        </a>
    </div>
</div>
@endsection