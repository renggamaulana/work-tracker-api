<?php

namespace App\Http\Controllers;

use App\Models\WorkContributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkLogSummaryController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkContributor::query()
            ->select('employee_name', DB::raw('SUM(hours_spent) as total_hours'));

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereHas('workLog', function ($q) use ($request) {
                $q->whereBetween('date', [$request->start_date, $request->end_date]);
            });
        }

        $contributors = $query->groupBy('employee_name')->get();

        return response()->json([
            'error' => false,
            'data' => [
                'contributors' => $contributors,
            ],
        ]);
    }
}
