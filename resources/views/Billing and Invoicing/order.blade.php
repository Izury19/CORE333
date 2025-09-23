@extends('layouts.maintenance')

@section('content')
<div class="container mx-auto mt-10 px-4">
    <h2 class="text-4xl font-bold mb-8 text-gray-800">📋 Job Management Dashboard</h2>

    {{-- Flash Message --}}
    @if(session('success'))
        <div id="flash-message" class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg mb-6 shadow">
            ✅ {{ session('success') }}
        </div>
        <script>
          setTimeout(function() {
              const flash = document.getElementById('flash-message');
              if(flash){
                  flash.style.transition = "opacity 0.5s";
                  flash.style.opacity = 0;
                  setTimeout(() => flash.remove(), 500);
              }
          }, 4000);
        </script>
    @endif

    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-blue-600 text-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold">Total Jobs</h3>
            <p class="text-3xl font-bold mt-2">{{ $jobs->total() }}</p>
        </div>
        <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold">In Progress</h3>
            <p class="text-3xl font-bold mt-2">{{ $jobs->where('status', 'in_progress')->count() }}</p>
        </div>
        <div class="bg-green-600 text-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold">Completed</h3>
            <p class="text-3xl font-bold mt-2">{{ $jobs->where('status', 'completed')->count() }}</p>
        </div>
        <div class="bg-purple-600 text-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold">Total Clients</h3>
            <p class="text-3xl font-bold mt-2">{{ $jobs->pluck('client_name')->unique()->count() }}</p>
        </div>
    </div>

    {{-- Jobs Table --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-900 text-white text-sm uppercase tracking-wider">
                    <th class="p-4">ID</th>
                    <th class="p-4">Client</th>
                    <th class="p-4">Service</th>
                    <th class="p-4">Hours</th>
                    <th class="p-4">Rate/Hour</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="p-4 font-medium">{{ $job->id }}</td>
                        <td class="p-4">{{ $job->client_name }}</td>
                        <td class="p-4">
                            @if($job->service_type === 'crane')
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    🏗️ Crane Service
                                </span>
                            @elseif($job->service_type === 'trucking')
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    🚚 Trucking Service
                                </span>
                            @else
                                <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    Other
                                </span>
                            @endif
                        </td>
                        <td class="p-4">{{ $job->hours }}</td>
                        <td class="p-4">₱{{ number_format($job->rate_per_hour, 2) }}</td>
                        <td class="p-4">
                            @if($job->status === 'completed')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Completed</span>
                            @elseif($job->status === 'in_progress')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">In Progress</span>
                            @else
                                <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <form action="{{ route('jobs.updateStatus', $job) }}" method="POST" class="flex flex-col sm:flex-row items-center gap-2 justify-center">
                                @csrf
                                @method('PUT')
                                <select name="status" class="border rounded-lg px-2 py-1 text-sm focus:ring focus:ring-blue-300 w-32">
                                    <option value="pending" @selected($job->status == 'pending')>Pending</option>
                                    <option value="in_progress" @selected($job->status == 'in_progress')>In Progress</option>
                                    <option value="completed" @selected($job->status == 'completed')>Completed</option>
                                </select>
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-700 transition w-full sm:w-auto">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-6 text-gray-500">No jobs found 🚧</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $jobs->links() }}
    </div>
</div>
@endsection
