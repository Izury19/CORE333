<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceType;
use Carbon\Carbon;
use App\Helpers\AiMaintenancePredictor;
use App\Models\MaintenanceNotification;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $schedules = MaintenanceSchedule::with('maintenanceType')
            ->when($search, function ($query, $search) {
                return $query->where('equipment_name', 'like', "%{$search}%")
                    ->orWhereHas('maintenanceType', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('scheduled_date', 'asc')
            ->paginate(10)
            ->withQueryString();

        $maintenanceTypes = MaintenanceType::all();

        return view('SchedulePreventive.maintenance-sched', compact('schedules', 'maintenanceTypes', 'search'));
    }

    public function create()
    {
        $maintenanceTypes = MaintenanceType::all();
        return view('SchedulePreventive.create', compact('maintenanceTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipment_name' => 'required|string|max:255',
            'maintenance_type_id' => 'required|exists:maintenance_types,maintenance_types_id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high,critical',
            'is_recurring' => 'nullable|boolean',
            'recurrence_type' => 'nullable|required_if:is_recurring,1|in:daily,weekly,monthly,yearly',
            'recurrence_frequency' => 'nullable|required_if:is_recurring,1|integer|min:1|max:365',
            'recurrence_end_date' => 'nullable|date|after:scheduled_date',
        ]);

        // ✅ Generate equipment_id safely
        $equipmentId = strtoupper(str_replace(' ', '_', $request->equipment_name));
        if (empty($equipmentId)) {
            $equipmentId = 'EQUIPMENT_' . time();
        }

        $data = [
            'equipment_name' => $request->equipment_name,
            'equipment_id' => $equipmentId, // ✅ Guaranteed not null
            'maintenance_type_id' => $request->maintenance_type_id,
            'scheduled_date' => $request->scheduled_date,
            'priority' => $request->priority,
            'status' => 'pending',
            'is_recurring' => (bool) $request->input('is_recurring', 0),
        ];

        if ($request->input('is_recurring') == 1) {
            $data['recurrence_type'] = $request->recurrence_type;
            $data['recurrence_frequency'] = $request->recurrence_frequency;
            $data['recurrence_end_date'] = $request->recurrence_end_date;
        }

        $schedule = MaintenanceSchedule::create($data);

        // ✅ AI INTEGRATION
        $riskScore = AiMaintenancePredictor::calculateRiskScore($request->equipment_name);
        $predictedDate = AiMaintenancePredictor::predictNextMaintenanceDate($request->equipment_name);
        $recommendations = AiMaintenancePredictor::generateRecommendations($riskScore);

        $schedule->update([
            'ai_risk_score' => $riskScore,
            'ai_predicted_failure_date' => $predictedDate,
            'ai_recommendations' => $recommendations
        ]);

        // ✅ CREATE NOTIFICATION
        $this->createNotification($schedule, 'upcoming');

        return redirect()->route('maintenance.index')
            ->with('success', '✅ Maintenance schedule created successfully!');
    }

    public function edit($id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $maintenanceTypes = MaintenanceType::all();
        return view('SchedulePreventive.edit', compact('schedule', 'maintenanceTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'equipment_name' => 'required|string|max:255',
            'maintenance_type_id' => 'required|exists:maintenance_types,maintenance_types_id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high,critical',
            'is_recurring' => 'nullable|boolean',
            'recurrence_type' => 'nullable|required_if:is_recurring,1|in:daily,weekly,monthly,yearly',
            'recurrence_frequency' => 'nullable|required_if:is_recurring,1|integer|min:1|max:365',
            'recurrence_end_date' => 'nullable|date|after:scheduled_date',
        ]);

        $schedule = MaintenanceSchedule::findOrFail($id);

        // ✅ Ensure equipment_id is not null
        $equipmentId = $request->equipment_id;
        if (empty($equipmentId)) {
            $equipmentId = strtoupper(str_replace(' ', '_', $request->equipment_name));
            if (empty($equipmentId)) {
                $equipmentId = 'EQUIPMENT_' . time();
            }
        }

        $data = [
            'equipment_name' => $request->equipment_name,
            'equipment_id' => $equipmentId, // ✅ Guaranteed not null
            'maintenance_type_id' => $request->maintenance_type_id,
            'scheduled_date' => $request->scheduled_date,
            'priority' => $request->priority,
            'is_recurring' => (bool) $request->input('is_recurring', 0),
        ];

        if ($request->input('is_recurring') == 1) {
            $data['recurrence_type'] = $request->recurrence_type;
            $data['recurrence_frequency'] = $request->recurrence_frequency;
            $data['recurrence_end_date'] = $request->recurrence_end_date;
        } else {
            $data['recurrence_type'] = null;
            $data['recurrence_frequency'] = null;
            $data['recurrence_end_date'] = null;
        }

        $schedule->update($data);

        // ✅ AI INTEGRATION - Recalculate AI insights after update
        $riskScore = AiMaintenancePredictor::calculateRiskScore($request->equipment_name);
        $predictedDate = AiMaintenancePredictor::predictNextMaintenanceDate($request->equipment_name);
        $recommendations = AiMaintenancePredictor::generateRecommendations($riskScore);

        $schedule->update([
            'ai_risk_score' => $riskScore,
            'ai_predicted_failure_date' => $predictedDate,
            'ai_recommendations' => $recommendations
        ]);

        return redirect()->route('maintenance.index')
            ->with('success', '✅ Maintenance schedule updated successfully!');
    }

    public function destroy($id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('maintenance.index')
            ->with('success', '🗑️ Maintenance schedule deleted successfully.');
    }

    public function markCompleted(Request $request, $id)
    {
        $request->validate([
            'proof_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        $schedule = MaintenanceSchedule::findOrFail($id);

        if ($request->hasFile('proof_image')) {
            $filename = time() . '_' . $request->file('proof_image')->getClientOriginalName();
            $path = $request->file('proof_image')->storeAs('proofs', $filename, 'public');
            $schedule->proof_image = $path;
        }

        $schedule->status = 'completed';
        $schedule->completed_at = now();
        $schedule->save();

        // ✅ Create completion notification
        $this->createNotification($schedule, 'completed');

        if ($schedule->is_recurring && $schedule->recurrence_type) {
            $nextDate = $this->calculateNextRecurrenceDate(
                $schedule->scheduled_date,
                $schedule->recurrence_type,
                $schedule->recurrence_frequency
            );

            if (!$schedule->recurrence_end_date || $nextDate <= $schedule->recurrence_end_date) {
                // ✅ Ensure equipment_id is not null for recurring schedules
                $recurringEquipmentId = $schedule->equipment_id;
                if (empty($recurringEquipmentId)) {
                    $recurringEquipmentId = strtoupper(str_replace(' ', '_', $schedule->equipment_name));
                    if (empty($recurringEquipmentId)) {
                        $recurringEquipmentId = 'EQUIPMENT_' . time();
                    }
                }

                MaintenanceSchedule::create([
                    'equipment_name' => $schedule->equipment_name,
                    'equipment_id' => $recurringEquipmentId, // ✅ Not null
                    'maintenance_type_id' => $schedule->maintenance_type_id,
                    'scheduled_date' => $nextDate,
                    'priority' => $schedule->priority,
                    'status' => 'pending',
                    'is_recurring' => 1,
                    'recurrence_type' => $schedule->recurrence_type,
                    'recurrence_frequency' => $schedule->recurrence_frequency,
                    'recurrence_end_date' => $schedule->recurrence_end_date,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => '✅ Maintenance marked as completed!'
        ]);
    }

    public function resetPending($id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->update([
            'status' => 'pending',
            'completed_at' => null,
            'proof_image' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule reset to pending status.'
        ]);
    }

    private function calculateNextRecurrenceDate($currentDate, $type, $frequency)
    {
        $date = Carbon::parse($currentDate);
        switch ($type) {
            case 'daily': return $date->addDays($frequency);
            case 'weekly': return $date->addWeeks($frequency);
            case 'monthly': return $date->addMonths($frequency);
            case 'yearly': return $date->addYears($frequency);
            default: return $date->addDays($frequency);
        }
    }

    private function createNotification($schedule, $type)
    {
        $message = '';
        
        switch($type) {
            case 'upcoming':
                $daysLeft = now()->diffInDays($schedule->scheduled_date);
                $message = "Maintenance scheduled for {$schedule->equipment_name} in {$daysLeft} days";
                break;
            case 'overdue':
                $message = "Maintenance for {$schedule->equipment_name} is OVERDUE!";
                break;
            case 'completed':
                $message = "Maintenance for {$schedule->equipment_name} has been completed";
                break;
        }
        
        // ✅ Ensure equipment_id is not null
        $notificationEquipmentId = $schedule->equipment_id;
        if (empty($notificationEquipmentId)) {
            $notificationEquipmentId = strtoupper(str_replace(' ', '_', $schedule->equipment_name));
            if (empty($notificationEquipmentId)) {
                $notificationEquipmentId = 'EQUIPMENT_' . time();
            }
        }
        
        MaintenanceNotification::create([
            'equipment_name' => $schedule->equipment_name,
            'equipment_id' => $notificationEquipmentId, // ✅ Guaranteed not null
            'scheduled_date' => $schedule->scheduled_date,
            'notification_type' => $type,
            'message' => $message
        ]);
    }

    public function showHistoryLog(Request $request)
    {
        $search = $request->input('search');
        $historyLogs = MaintenanceSchedule::with('maintenanceType')
            ->when($search, function ($query, $search) {
                return $query->where('equipment_name', 'like', "%{$search}%")
                    ->orWhereHas('maintenanceType', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('scheduled_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('SchedulePreventive.maintenance-history', compact('historyLogs', 'search'));
    }

    public function dashboard()
    {
        // AI Risk Distribution
        $highRiskCount = MaintenanceSchedule::where('ai_risk_score', '>=', 0.8)
            ->where('status', 'pending')
            ->count();
        
        $mediumRiskCount = MaintenanceSchedule::whereBetween('ai_risk_score', [0.6, 0.79])
            ->where('status', 'pending')
            ->count();
        
        $lowRiskCount = MaintenanceSchedule::where('ai_risk_score', '<', 0.6)
            ->where('status', 'pending')
            ->count();

        // Maintenance Statistics
        $totalSchedules = MaintenanceSchedule::count();
        $completedThisMonth = MaintenanceSchedule::where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->count();
        
        $overdueCount = MaintenanceSchedule::where('status', 'pending')
            ->where('scheduled_date', '<', now())
            ->count();

        // Upcoming Maintenance (next 7 days)
        $upcomingMaintenance = MaintenanceSchedule::where('status', 'pending')
            ->whereBetween('scheduled_date', [now(), now()->addDays(7)])
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        // Recent Notifications
        $recentNotifications = \App\Models\MaintenanceNotification::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('SchedulePreventive.dashboard', compact(
            'highRiskCount',
            'mediumRiskCount', 
            'lowRiskCount',
            'totalSchedules',
            'completedThisMonth',
            'overdueCount',
            'upcomingMaintenance',
            'recentNotifications'
        ));
    }
}