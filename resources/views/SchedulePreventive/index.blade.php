@extends('layouts.app')
@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Maintenance Schedule</h1>
    
    <!-- Add Button -->
    <a href="{{ route('maintenance.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
        Add New Schedule
    </a>
    
    <!-- Table -->
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th>Equipment</th>
                <th>Scheduled Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->equipment_id }}</td>
                <td>{{ $schedule->scheduled_date }}</td>
                <td>{{ $schedule->status }}</td>
                <td>
                    <a href="{{ route('maintenance.edit', $schedule->id) }}">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection