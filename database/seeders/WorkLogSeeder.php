<?php

namespace Database\Seeders;

use App\Models\WorkLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
      public function run(): void
    {
        $workLogs = [
            [
                'task_description' => 'Build login page',
                'date' => '2025-05-10',
                'hourly_rate' => 50000,
                'additional_charges' => 20000,
                'contributors' => [
                    ['employee_name' => 'Adit', 'hours_spent' => 3],
                    ['employee_name' => 'Sari', 'hours_spent' => 2],
                ],
            ],
            [
                'task_description' => 'Fix bug dashboard',
                'date' => '2025-05-12',
                'hourly_rate' => 60000,
                'additional_charges' => 0,
                'contributors' => [
                    ['employee_name' => 'Budi', 'hours_spent' => 4],
                ],
            ],
        ];

        foreach ($workLogs as $logData) {
            $totalHours = collect($logData['contributors'])->sum('hours_spent');
            $totalRemuneration = ($totalHours * $logData['hourly_rate']) + $logData['additional_charges'];

            $workLog = WorkLog::create([
                'task_description' => $logData['task_description'],
                'date' => $logData['date'],
                'hourly_rate' => $logData['hourly_rate'],
                'additional_charges' => $logData['additional_charges'],
                'total_remuneration' => $totalRemuneration,
            ]);

            foreach ($logData['contributors'] as $contrib) {
                $workLog->contributors()->create($contrib);
            }
        }
    }
}
