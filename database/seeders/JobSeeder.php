<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        Job::create([
            'title' => 'Crane Service - Warehouse A',
            'description' => 'Crane lifting service for construction materials.',
            'client_name' => 'Jan Ryan',
            'email' => 'janryanolandria19@gmail.com',
            'service_type' => 'trucking',
            'status' => 'Completed',
            'rate_per_hour' => 1500,
            'hours' => 8,
            'distance_km' => 10,
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDays(4),
        ]);

        Job::create([
            'title' => 'Trucking Service - Delivery Route 1',
            'description' => 'Transporting steel beams to Site B.',
            'client_name' => 'John Christian',
            'email' => 'johnchristianmagpili07@gmail.com',
            'service_type' => 'crane',
            'status' => 'Completed',
            'rate_per_hour' => 1200,
            'hours' => 8,
            'distance_km' => 50,
            'start_date' => now()->subDay(),
            'end_date' => null,
        ]);

        Job::create([
            'title' => 'Crane Service - Mall Project',
            'description' => 'Heavy lifting of AC units.',
            'client_name' => 'Raymon Loria',
            'email' => 'loriaraymon@gmail.com',
            'service_type' => 'trucking',
            'status' => 'Completed',
            'rate_per_hour' => 2000,
            'hours' => 8,
            'distance_km' => 5,
            'start_date' => null,
            'end_date' => null,
        ]);
    }
}
