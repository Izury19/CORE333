@extends('layouts.app')

@section('content')
<style>
.notifications-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem;
}
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.page-title svg {
    width: 1.5rem;
    height: 1.5rem;
    color: #3b82f6;
}
.notification-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.25rem;
    border-left: 4px solid #3b82f6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.notification-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.notification-card.unread {
    background: #f8fafc;
    border-left-color: #ef4444;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.1);
}
.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.notification-type {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    margin-right: 0.5rem;
}
.type-upcoming { background: #dcfce7; color: #166534; }
.type-overdue { background: #fee2e2; color: #dc2626; }
.type-completed { background: #dbeafe; color: #1d4ed8; }
.notification-message {
    font-weight: 600;
    margin: 0.5rem 0;
    color: #1e293b;
    line-height: 1.5;
}
.notification-date {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #64748b;
}
.empty-state svg {
    width: 80px;
    height: 80px;
    margin-bottom: 1.5rem;
    color: #cbd5e1;
}
.empty-state h3 {
    font-weight: 700;
    font-size: 1.25rem;
    color: #334155;
    margin-bottom: 0.5rem;
}
.empty-state p {
    font-size: 1rem;
    line-height: 1.6;
    max-width: 500px;
    margin: 0 auto;
}
.pagination-container {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}
.pagination {
    display: flex;
    gap: 0.25rem;
}
.pagination a,
.pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
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
    .notifications-container {
        padding: 1rem;
    }
    .page-title {
        font-size: 1.5rem;
    }
    .notification-card {
        padding: 1.25rem;
    }
    .notification-header {
        flex-direction: column;
        align-items: stretch;
    }
    .notification-type {
        align-self: flex-start;
    }
}
</style>

<div class="notifications-container">
    <div class="page-header">
        <h1 class="page-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            Maintenance Notifications
        </h1>
    </div>
    
    @if($notifications->count() > 0)
        @foreach($notifications as $notification)
        <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}">
            <div class="notification-header">
                <span class="notification-type type-{{ $notification->notification_type }}">
                    {{ ucfirst(str_replace('_', ' ', $notification->notification_type)) }}
                </span>
                <span class="notification-date">
                    {{ $notification->created_at->format('M d, Y \a\t H:i') }}
                </span>
            </div>
            <div class="notification-message">
                {{ $notification->message }}
            </div>
            @if($notification->equipment_name)
                <div class="text-sm text-gray-600 mt-2">
                    Equipment: <span class="font-medium">{{ $notification->equipment_name }}</span>
                </div>
            @endif
        </div>
        @endforeach
        
        <div class="pagination-container">
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($notifications->onFirstPage())
                    <span class="disabled">&laquo; Previous</span>
                @else
                    <a href="{{ $notifications->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $currentPage = $notifications->currentPage();
                    $lastPage = $notifications->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                @if($start > 1)
                    <a href="{{ $notifications->url(1) }}">1</a>
                    @if($start > 2)
                        <span>...</span>
                    @endif
                @endif

                @for($i = $start; $i <= $end; $i++)
                    @if($i == $currentPage)
                        <span class="active">{{ $i }}</span>
                    @else
                        <a href="{{ $notifications->url($i) }}">{{ $i }}</a>
                    @endif
                @endfor

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <span>...</span>
                    @endif
                    <a href="{{ $notifications->url($lastPage) }}">{{ $lastPage }}</a>
                @endif

                {{-- Next Page Link --}}
                @if ($notifications->hasMorePages())
                    <a href="{{ $notifications->nextPageUrl() }}" rel="next">Next &raquo;</a>
                @else
                    <span class="disabled">Next &raquo;</span>
                @endif
            </div>
        </div>
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <h3>No notifications yet</h3>
            <p>Maintenance notifications will appear here when schedules are created, completed, or become overdue. Create your first maintenance schedule to get started!</p>
        </div>
    @endif
</div>
@endsection