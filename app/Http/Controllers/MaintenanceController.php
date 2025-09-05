<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceSchedule;
use App\Models\Technician;

class MaintenanceController extends Controller
{
    // Display all schedules
public function index()
{
    $schedules = MaintenanceSchedule::orderBy('scheduled_date', 'asc')->paginate(10)->withQueryString();
    $technicians = Technician::all();

    return view('SchedulePreventive.maintenance-sched', compact('schedules', 'technicians'));
}


    // Show form to create new schedule
public function create()
{
    $technicians = Technician::all();
    return view('SchedulePreventive.create', compact('technicians'));
}

    // Store new schedule
    public function store(Request $request)
    {
        $request->validate([
            'equipment_name' => 'required|string|max:255',
            'type' => 'required|string',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'technician_name' => 'required|string|max:255',
        ]);

        MaintenanceSchedule::create($request->all());

        return redirect()->route('maintenance.index')->with('success', 'Maintenance schedule created successfully.');
    }

    // Show form to edit schedule
public function edit($id)
{
    $schedule = MaintenanceSchedule::findOrFail($id);
    $technicians = Technician::all();
    return view('SchedulePreventive.edit', compact('schedule', 'technicians'));
}   

    // Update schedule
    public function update(Request $request, $id)
    {
        $request->validate([
            'equipment_name' => 'required|string|max:255',
            'type' => 'required|string',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'technician_name' => 'required|string|exists:technicians,name',
        ]);

        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->route('maintenance.index')->with('success', 'Maintenance schedule updated successfully.');
    }

    // Delete schedule
    public function destroy($id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('maintenance.index')->with('success', 'Maintenance schedule deleted.');
    }
}
