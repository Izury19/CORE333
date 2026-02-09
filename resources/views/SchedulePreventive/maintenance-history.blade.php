@extends('layouts.app')

@section('content')
<style>
.history-dashboard {
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
    color: #0d9488;
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
    border-color: #0d9488;
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
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
    background-color: #f0fdfa;
}
th {
    padding: 1rem 1.25rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #0f766e;
    border-bottom: 2px solid #ccfbf1;
}
tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.2s ease;
}
tbody tr:last-child {
    border-bottom: none;
}
tbody tr:hover {
    background-color: #f0fdfa;
}
td {
    padding: 1rem 1.25rem;
    font-size: 0.875rem;
    color: #334155;
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
.status-completed { 
    background-color: #dcfce7; 
    color: #166534; 
}
.status-overdue { 
    background-color: #fee2e2; 
    color: #dc2626; 
}
.status-pending { 
    background-color: #fef3c7; 
    color: #92400e; 
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
    color: #ccfbf1;
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
    color: #0d9488;
    background-color: #f0fdfa;
}
.pagination a:hover {
    background-color: #ccfbf1;
}
.pagination .active {
    background-color: #0d9488;
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
    th, td {
        padding: 0.75rem 0.5rem;
        font-size: 0.8125rem;
    }
}
</style>

<div class="history-dashboard">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Maintenance History Log
        </h1>
        
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <form method="GET" action="{{ route('maintenance-history') }}">
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
                        <th>Completed Date</th>
                        <th>Status</th>
                        <th>Proof</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historyLogs as $log)
                    <tr>
                        <td>{{ $log->equipment_name }}</td>
                        <td>{{ $log->maintenanceType->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->scheduled_date)->format('M d, Y') }}</td>
                        <td>
                            @if($log->completed_at)
                                {{ \Carbon\Carbon::parse($log->completed_at)->format('M d, Y') }}
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($log->status == 'completed')
                                <span class="status-badge status-completed">Completed</span>
                            @elseif($log->status == 'pending' && \Carbon\Carbon::parse($log->scheduled_date)->isPast())
                                <span class="status-badge status-overdue">Overdue</span>
                            @else
                                <span class="status-badge status-pending">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($log->proof_image)
                                <a href="{{ asset('storage/' . $log->proof_image) }}" 
                                   target="_blank" 
                                   class="text-teal-600 hover:text-teal-800 font-medium">
                                    View Proof
                                </a>
                            @else
                                <span class="text-gray-400">No Proof</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="font-semibold text-lg mb-2">No maintenance history found</h3>
                                <p>Completed maintenance schedules will appear here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($historyLogs->hasPages())
        <div class="pagination-container">
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($historyLogs->onFirstPage())
                    <span class="disabled">&laquo; Previous</span>
                @else
                    <a href="{{ $historyLogs->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($historyLogs->links()->elements[0] as $page => $url)
                    @if ($page == $historyLogs->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($historyLogs->hasMorePages())
                    <a href="{{ $historyLogs->nextPageUrl() }}" rel="next">Next &raquo;</a>
                @else
                    <span class="disabled">Next &raquo;</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection