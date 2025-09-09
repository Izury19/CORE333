<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\MaintenanceSchedule;
use App\Models\Technician;
use App\Models\MaintenanceType;

class MaintenanceController extends Controller
{
    /**
     * Display paginated list of maintenance schedules.
     */
    public function index()
    {
        $schedules = MaintenanceSchedule::with('maintenanceType')
                      ->orderBy('scheduled_date', 'asc')
                      ->paginate(10)
                      ->withQueryString();

        $technicians = Technician::all();
        $maintenanceTypes = MaintenanceType::all(); 

        return view('SchedulePreventive.maintenance-sched', compact('schedules', 'technicians', 'maintenanceTypes'));
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
            'maintenance_type_id' => 'required|exists:maintenance_types,id',
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

        return redirect()->route('maintenance.index')->with('success', 'Maintenance schedule created successfully.');
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
            'maintenance_type_id' => 'required|exists:maintenance_types,id',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'technician_name' => 'required|string|exists:technicians,name',
        ]);

        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->update([
            'equipment_name' => $request->equipment_name,
            'maintenance_type_id' => $request->maintenance_type_id,
            'scheduled_date' => $request->scheduled_date,
            'status' => $request->status,
            'technician_name' => $request->technician_name,
        ]);

        return redirect()->route('maintenance.index')->with('success', 'Maintenance schedule updated successfully.');
    }

    /**
     * Delete a maintenance schedule.
     */
    public function destroy($id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('maintenance.index')->with('success', 'Maintenance schedule deleted.');
    }

    /**
     * Provide maintenance schedule data for FullCalendar in JSON format.
     * This will be called by the frontend calendar to display events.
     */
    public function calendarEvents()
    {
        $schedules = MaintenanceSchedule::with('maintenanceType')->get();

        $events = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'title' => $schedule->equipment_name . ' (' . optional($schedule->maintenanceType)->name . ')',
                'start' => $schedule->scheduled_date,
                'status' => $schedule->status,
                'technician' => $schedule->technician_name,
            ];
        });

        return response()->json($events);
    }
}
