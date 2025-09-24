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
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-900 text-white text-xs uppercase tracking-wider">
                    <th class="p-3 w-12 text-center">ID</th>
                    <th class="p-3 w-32 text-left">Client</th>
                    <th class="p-3 w-48 text-left">Email</th>
                    <th class="p-3 w-28 text-center">Service</th>
                    <th class="p-3 w-20 text-center">Hours</th>
                    <th class="p-3 w-28 text-center">Rate/Hour</th>
                    <th class="p-3 w-28 text-center">Status</th>
                    <th class="p-3 w-40 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                    <tr class="border-b hover:bg-gray-100 transition text-sm">
                        <td class="p-3 text-center font-medium">{{ $job->id }}</td>
                        <td class="p-3 text-left">{{ $job->client_name }}</td>
                        <td class="p-3 text-left">
                            <a href="mailto:{{ $job->email }}" class="text-blue-600 hover:underline">
                                {{ $job->email }}
                            </a>
                        </td>
                        <td class="p-3 text-center">
                            @if($job->service_type === 'crane')
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-md text-xs font-medium">
                                    🏗️ Crane
                                </span>
                            @elseif($job->service_type === 'trucking')
                                <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded-md text-xs font-medium">
                                    🚚 Trucking
                                </span>
                            @else
                                <span class="inline-block bg-gray-200 text-gray-800 px-2 py-1 rounded-md text-xs font-medium">
                                    Other
                                </span>
                            @endif
                        </td>
                        <td class="p-3 text-center">{{ number_format($job->hours, 2) }}</td>
                        <td class="p-3 text-center">₱{{ number_format($job->rate_per_hour, 2) }}</td>
                        <td class="p-3 text-center">
                            @if($job->status === 'completed')
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-md text-xs font-medium">Completed</span>
                            @elseif($job->status === 'in_progress')
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-md text-xs font-medium">In Progress</span>
                            @else
                                <span class="inline-block bg-gray-200 text-gray-800 px-2 py-1 rounded-md text-xs font-medium">Pending</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <form action="{{ route('jobs.updateStatus', $job) }}" method="POST" class="flex items-center justify-center gap-2">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center border rounded-lg overflow-hidden">
                                    <select name="status"
                                        class="px-2 py-1 text-xs border-0 focus:ring-0 focus:outline-none">
                                        <option value="pending" @selected($job->status == 'pending')>Pending</option>
                                        <option value="in_progress" @selected($job->status == 'in_progress')>In Progress</option>
                                        <option value="completed" @selected($job->status == 'completed')>Completed</option>
                                    </select>
                                    <button type="submit"
                                        class="bg-blue-600 text-white px-3 py-1 text-xs hover:bg-blue-700 transition">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center p-6 text-gray-500">No jobs found 🚧</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>




    {{-- Pagination --}}
    <div class="mt-6">
        {{ $jobs->links() }}
    </div>
</div>
@endsection
