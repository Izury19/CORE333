<?php

namespace App\Helpers;

use App\Models\MaintenanceSchedule;
use Carbon\Carbon;

class AiMaintenancePredictor
{
    /**
     * Calculate AI risk score based on equipment history
     */
    public static function calculateRiskScore($equipmentName)
    {
        // Count pending critical schedules
        $criticalCount = MaintenanceSchedule::where('equipment_name', $equipmentName)
            ->where('priority', 'critical')
            ->where('status', 'pending')
            ->count();

        // Count overdue schedules
        $overdueCount = MaintenanceSchedule::where('equipment_name', $equipmentName)
            ->where('status', 'pending')
            ->where('scheduled_date', '<', now())
            ->count();

        // Simple formula: (critical * 0.4) + (overdue * 0.3)
        $riskScore = min(($criticalCount * 0.4) + ($overdueCount * 0.3), 1.0);
        
        return round($riskScore, 2);
    }

    /**
     * Predict next maintenance date based on historical data
     */
    public static function predictNextMaintenanceDate($equipmentName)
    {
        // Get last 3 completed maintenance records
        $lastMaintenances = MaintenanceSchedule::where('equipment_name', $equipmentName)
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->limit(3)
            ->get();

        if ($lastMaintenances->count() < 2) {
            // Not enough data, default to 3 months
            return now()->addMonths(3);
        }

        // Calculate average interval between maintenances
        $totalDays = 0;
        $intervals = [];

        for ($i = 0; $i < $lastMaintenances->count() - 1; $i++) {
            $days = Carbon::parse($lastMaintenances[$i]->completed_at)
                ->diffInDays(Carbon::parse($lastMaintenances[$i + 1]->completed_at));
            $intervals[] = $days;
            $totalDays += $days;
        }

        $averageInterval = $totalDays / count($intervals);
        
        // Predict next date (add buffer for safety)
        $lastCompleted = Carbon::parse($lastMaintenances->first()->completed_at);
        return $lastCompleted->addDays($averageInterval * 1.2); // 20% buffer
    }

    /**
     * Generate AI recommendations based on risk score
     */
    public static function generateRecommendations($riskScore)
    {
        if ($riskScore >= 0.8) {
            return "High risk detected! Schedule immediate maintenance and review equipment usage patterns.";
        } elseif ($riskScore >= 0.6) {
            return "Medium risk detected. Consider scheduling maintenance within the next 30 days.";
        } elseif ($riskScore >= 0.4) {
            return "Low risk detected. Monitor equipment performance and maintain regular schedule.";
        } else {
            return "Equipment is in good condition. Continue with standard maintenance schedule.";
        }
    }
}