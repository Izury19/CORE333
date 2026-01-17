<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    public function index()
    {
        // Key Metrics (hardcoded for now to avoid database errors)
        $totalRevenue = 142500;
        $outstandingBalance = 28400;
        $activeEquipment = 5;
        $totalEquipment = 8;
        $maintenanceDue = 2;

        // AI Predictions (hardcoded test data)
        $cashFlow = [
            'predicted' => 185000,
            'confidence' => 'high',
            'trend' => 'upward',
            'message' => 'Expected revenue next month'
        ];
        
        $equipmentDemand = [
            'equipment' => 'Crane - Mobile',
            'recommendation' => 'High demand - consider acquiring additional units'
        ];
        
        $riskAlerts = [
            [
                'client' => 'ABC Construction',
                'risk_level' => 'High',
                'risk_score' => 75.5,
                'late_count' => 2,
                'total_invoices' => 3
            ]
        ];
        
        $maintenancePriority = [
            [
                'name' => 'Mobile Crane 01',
                'priority_score' => 65,
                'recommendation' => 'Schedule immediate maintenance'
            ]
        ];

        return view('Reporting and Analytics.financial-report', compact(
            'totalRevenue',
            'outstandingBalance',
            'activeEquipment',
            'totalEquipment',
            'maintenanceDue',
            'cashFlow',
            'equipmentDemand',
            'riskAlerts',
            'maintenancePriority'
        ));
    }
}