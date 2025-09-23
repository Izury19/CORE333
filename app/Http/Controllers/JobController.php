<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the jobs.
     */
    public function index()
    {
        $jobs = Job::latest()->paginate(10);

        // ayusin ang view path
        return view('Billing and Invoicing.order', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        return view('jobs.create'); // optional kung gagawa ka ng form
    }

    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_name'   => 'required|string|max:255',
            'service_type'  => 'required|in:crane,trucking',
            'rate_per_hour' => 'required|numeric|min:0',
            'hours'         => 'required|numeric|min:0',
            'distance_km'   => 'nullable|numeric|min:0',
        ]);

        $job = Job::create($request->all());

        // Auto-create invoice amount
        $amount = $job->rate_per_hour * $job->hours;
        if ($job->service_type === 'trucking' && $job->distance_km) {
            $amount += $job->distance_km * 50; // halimbawa 50 per km
        }

        $job->invoice()->create([
            'amount' => $amount,
            'status' => 'unpaid'
        ]);

        return redirect()->route('jobs.index')
                         ->with('success', 'Job created successfully with invoice!');
    }

    /**
     * Update the status of a job.
     */
    public function updateStatus(Request $request, Job $job)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $job->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Job status updated!');
    }

    /**
     * Remove the specified job from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('jobs.index')
                         ->with('success', 'Job deleted successfully!');
    }
}
