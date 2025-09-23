<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceSchedule;
use App\Models\Technician;
use App\Models\MaintenanceType;
use App\Mail\MaintenanceScheduleMail;
use Illuminate\Support\Facades\Mail;

class MaintenanceController extends Controller
{
    /**
     * Display paginated list of maintenance schedules.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $schedules = MaintenanceSchedule::with('maintenanceType')
            ->when($search, function ($query, $search) {
                $query->where('equipment_name', 'like', "%{$search}%")
                      ->orWhereHas('maintenanceType', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      })
                      ->orWhere('technician_name', 'like', "%{$search}%");
            })
            ->orderBy('scheduled_date', 'asc')
            ->paginate(10)
            ->withQueryString();

        $technicians = Technician::all();
        $maintenanceTypes = MaintenanceType::all(); 

        return view('SchedulePreventive.maintenance-sched', compact(
            'schedules',
            'technicians',
            'maintenanceTypes',
            'search'
        ));
    }

    /**
     * Show form to create a new maintenance schedule.
     */
    public function create()
    {
        $technicians = Technician::all();
        $maintenanceTypes = MaintenanceType::all();

        return view('SchedulePreventive.create', compact('technicians', 'maintenanceTypes'));
    }

    /**
     * Store a new maintenance schedule.
     */
    public function store(Request $request)
    {
        $request->validate([
            'equipment_name' => 'required|string|max:255',
            'maintenance_type_id' => 'required|exists:maintenance_types,maintenance_types_id',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'technician_name' => 'required|string|max:255',
        ]);

        MaintenanceSchedule::create([
            'equipment_name' => $request->equipment_name,
            'maintenance_type_id' => $request->maintenance_type_id,
            'scheduled_date' => $request->scheduled_date,
            'status' => $request->status,
            'technician_name' => $request->technician_name,
        ]);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance schedule created successfully.');
    }

    /**
     * Show the form for editing a specific schedule.
     */
    public function edit($id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $technicians = Technician::all();
        $maintenanceTypes = MaintenanceType::all();

        return view('SchedulePreventive.edit', compact('schedule', 'technicians', 'maintenanceTypes'));
    } 

    /**
     * Update an existing maintenance schedule.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'equipment_name' => 'required|string|max:255',
            'maintenance_type_id' => 'required|exists:maintenance_types,maintenance_types_id',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'technician_name' => 'required|string|max:255',
        ]);

        $schedule = MaintenanceSchedule::findOrFail($id);

        $schedule->update([
            'equipment_name' => $request->equipment_name,
            'maintenance_type_id' => $request->maintenance_type_id,
            'scheduled_date' => $request->scheduled_date,
            'status' => $request->status,
            'technician_name' => $request->technician_name,
        ]);

        return redirect()
            ->route('maintenance.edit', $schedule)
            ->with('success', 'Maintenance schedule updated successfully.');
    }

    /**
     * Delete a maintenance schedule.
     */
    public function destroy($id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance schedule deleted.');
    }

    /**
     * Provide maintenance schedule data for FullCalendar in JSON format.
     */
    public function calendarEvents()
    {
        $schedules = MaintenanceSchedule::with('maintenanceType')->get();

        $events = $schedules->map(function ($schedule) {
            $technician = Technician::where('name', $schedule->technician_name)->first();

            return [
                'id' => $schedule->maintenance_sched_id,
                'title' => $schedule->equipment_name . ' (' . optional($schedule->maintenanceType)->name . ')',
                'start' => $schedule->scheduled_date,
                'status' => $schedule->status,
                'technician' => $schedule->technician_name,
                'email' => $technician ? $technician->email : null,
                'proof_image' => $schedule->proof_image, // ✅ kasama proof image
                'completed_at' => $schedule->completed_at, // ✅ kasama completion time
            ];
        });

        return response()->json($events);
    }

    /**
     * Send email notification to technicians for schedules on a given date.
     */
    public function sendEmailNotification(Request $request)
    {
        $date = $request->input('date');

        $schedules = MaintenanceSchedule::whereDate('scheduled_date', $date)->get();

        if ($schedules->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No schedules found for this date.'
            ], 404);
        }

        foreach ($schedules as $schedule) {
            $technician = Technician::where('name', $schedule->technician_name)->first();

            if ($technician && $technician->email) {
                try {
                    Mail::to($technician->email)->send(new MaintenanceScheduleMail($schedule));
                } catch (\Exception $e) {
                    \Log::error('Mail error: ' . $e->getMessage());
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Emails sent successfully.'
        ]);
    }

    /**
     * Mark a maintenance schedule as completed with proof image.
     */
    public function markCompleted(Request $request, $id)
    {
        $request->validate([
            'proof_image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $schedule = MaintenanceSchedule::findOrFail($id);

        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('proofs', $filename, 'public');
            $schedule->proof_image = $path;
        }

        $schedule->status = 'completed';
        $schedule->completed_at = now();
        $schedule->save();

        return response()->json([
            'success' => true,
            'message' => 'Maintenance marked as completed!',
            'data' => $schedule
        ]);
    }
}
