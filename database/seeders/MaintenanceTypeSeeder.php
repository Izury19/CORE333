<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceType;

class MaintenanceTypeSeeder extends Seeder
{
    public function run()
    {
        MaintenanceType::insert([
            ['name' => 'Visual Inspection', 'frequency' => 'Monthly'],
            ['name' => 'Lubrication', 'frequency' => 'Monthly'],
            ['name' => 'Wire Rope Inspection', 'frequency' => 'Monthly'],
            ['name' => 'Brake System Check', 'frequency' => 'Quarterly'],
            ['name' => 'Electrical System Check', 'frequency' => 'Quarterly'],
            ['name' => 'Hydraulic Oil Change', 'frequency' => 'Quarterly'],
            ['name' => 'Gearbox Oil Change', 'frequency' => 'Yearly'],
            ['name' => 'Load Testing', 'frequency' => 'Yearly'],
        ]);
    }
}
