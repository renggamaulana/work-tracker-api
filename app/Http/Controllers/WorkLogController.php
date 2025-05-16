<?php

namespace App\Http\Controllers;

use App\Models\WorkLog;
use App\Http\Requests\StoreWorkLogRequest;
use App\Http\Requests\UpdateWorkLogRequest;
use App\Http\Resources\WorkLogResource;

class WorkLogController extends Controller
{
    // GET /api/work-logs
    public function index()
    {
        $query = WorkLog::with('contributors');

        // Search by task_description or employee_name
        if (request()->has('search') && request('search') !== '') {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('task_description', 'like', "%{$search}%")
                ->orWhereHas('contributors', function ($cq) use ($search) {
                    $cq->where('employee_name', 'like', "%{$search}%");
                });
            });
        }

        // Sorting
        $sort = request('sort', 'date');
        $order = request('order', 'desc');
        $query->orderBy($sort, $order);

        $logs = $query->get();

        return WorkLogResource::collection($logs);
    }

    // GET /api/work-logs/{id}
    public function show($id)
    {
        $workLog = WorkLog::with('contributors')->findOrFail($id);
        return new WorkLogResource($workLog);
    }

    // POST /api/work-logs
    public function store(StoreWorkLogRequest $request)
    {
        $data = $request->validated();

        $totalHours = collect($data['contributors'])->sum('hours_spent');
        $baseRate = $data['hourly_rate'];
        $extra = $data['additional_charges'] ?? 0;
        $total = ($totalHours * $baseRate) + $extra;

        $workLog = WorkLog::create([
            'task_description' => $data['task_description'],
            'date' => $data['date'],
            'hourly_rate' => $baseRate,
            'additional_charges' => $extra,
            'total_remuneration' => $total,
        ]);

        foreach ($data['contributors'] as $contributor) {
            $workLog->contributors()->create($contributor);
        }

        return new WorkLogResource($workLog->load('contributors'));
    }

    // PUT /api/work-logs/{id}
    public function update(UpdateWorkLogRequest $request, $id)
    {
        $workLog = WorkLog::findOrFail($id);
        $data = $request->validated();

        $totalHours = collect($data['contributors'])->sum('hours_spent');
        $baseRate = $data['hourly_rate'];
        $extra = $data['additional_charges'] ?? 0;
        $total = ($totalHours * $baseRate) + $extra;

        $workLog->update([
            'task_description' => $data['task_description'],
            'date' => $data['date'],
            'hourly_rate' => $baseRate,
            'additional_charges' => $extra,
            'total_remuneration' => $total,
        ]);

        $workLog->contributors()->delete();
        foreach ($data['contributors'] as $contributor) {
            $workLog->contributors()->create($contributor);
        }

        return new \App\Http\Resources\WorkLogResource($workLog->load('contributors'));
    }


    // DELETE /api/work-logs/{id}
    public function destroy($id)
    {
        $workLog = WorkLog::findOrFail($id);
        $workLog->delete();
        return response()->json(['message' => 'Work log deleted successfully.']);
    }
}
