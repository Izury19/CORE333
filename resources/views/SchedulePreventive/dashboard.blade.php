@extends('layouts.app')

@section('content')
<style>
.dashboard-container {
    display: flex;
    gap: 1.5rem;
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* Left Stats Panel */
.stats-panel {
    width: 280px;
    flex-shrink: 0;
}
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-left: 4px solid #3b82f6;
}
.stat-card.high-risk { border-left-color: #ef4444; }
.stat-card.medium-risk { border-left-color: #f59e0b; }
.stat-card.low-risk { border-left-color: #10b981; }
.stat-card.total { border-left-color: #8b5cf6; }
.stat-card.completed { border-left-color: #06b6d4; }
.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0.5rem 0;
}
.stat-label {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}
.stat-subtitle {
    color: #94a3b8;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

/* Main Content Columns */
.main-content {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.content-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.content-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.content-title svg {
    width: 1rem;
    height: 1rem;
    color: #3b82f6;
}
.content-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
    color: #64748b;
    font-size: 0.875rem;
}
.no-data {
    color: #94a3b8;
    font-size: 0.875rem;
}
.notification-item {
    text-align: left;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f5f9;
}
.notification-item:last-child {
    border-bottom: none;
}
.notification-type {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    margin-bottom: 0.5rem;
}
.type-upcoming { background: #dcfce7; color: #166534; }
.type-overdue { background: #fee2e2; color: #dc2626; }
.type-completed { background: #dbeafe; color: #1d4ed8; }
.notification-message {
    font-weight: 500;
    margin: 0.25rem 0;
}
.notification-date {
    color: #94a3b8;
    font-size: 0.75rem;
}
</style>

<div class="dashboard-container">
    <!-- Left Stats Panel -->
    <div class="stats-panel">
        <div class="stat-card high-risk">
            <div class="stat-label">High Risk Equipment</div>
            <div class="stat-number">{{ $highRiskCount }}</div>
            <div class="stat-subtitle">AI Risk Score ≥ 80%</div>
        </div>
        
        <div class="stat-card medium-risk">
            <div class="stat-label">Medium Risk Equipment</div>
            <div class="stat-number">{{ $mediumRiskCount }}</div>
            <div class="stat-subtitle">AI Risk Score 60-79%</div>
        </div>
        
        <div class="stat-card low-risk">
            <div class="stat-label">Low Risk Equipment</div>
            <div class="stat-number">{{ $lowRiskCount }}</div>
            <div class="stat-subtitle">AI Risk Score < 60%</div>
        </div>
        
        <div class="stat-card total">
            <div class="stat-label">Total Schedules</div>
            <div class="stat-number">{{ $totalSchedules }}</div>
            <div class="stat-subtitle">All maintenance schedules</div>
        </div>
        
        <div class="stat-card completed">
            <div class="stat-label">Completed This Month</div>
            <div class="stat-number">{{ $completedThisMonth }}</div>
            <div class="stat-subtitle">Monthly completion rate</div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Upcoming Maintenance -->
        <div class="content-card">
            <h3 class="content-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Upcoming Maintenance (Next 7 Days)
            </h3>
            <div class="content-body">
                @if($upcomingMaintenance->count() > 0)
                    @foreach($upcomingMaintenance as $schedule)
                    <div style="text-align: left; margin-bottom: 1rem;">
                        <div><strong>{{ $schedule->equipment_name }}</strong></div>
                        <div>{{ $schedule->maintenanceType->name ?? 'N/A' }} • {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('M d') }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="no-data">No upcoming maintenance scheduled.</div>
                @endif
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="content-card">
            <h3 class="content-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Recent Notifications
            </h3>
            <div class="content-body">
                @if($recentNotifications->count() > 0)
                    @foreach($recentNotifications as $notification)
                    <div class="notification-item">
                        <div class="notification-type type-{{ $notification->notification_type }}">
                            {{ ucfirst($notification->notification_type) }}
                        </div>
                        <div class="notification-message">{{ $notification->message }}</div>
                        <div class="notification-date">{{ $notification->created_at->format('M d H:i') }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="no-data">No recent notifications.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection