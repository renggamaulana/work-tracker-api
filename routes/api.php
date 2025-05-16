<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WorkLogController;
use App\Http\Controllers\WorkLogSummaryController;

Route::get('/work-logs', [WorkLogController::class, 'index']);
Route::get('/work-logs/summary', [WorkLogSummaryController::class, 'index']);
Route::get('/work-logs/{id}', [WorkLogController::class, 'show']);
Route::post('/work-logs', [WorkLogController::class, 'store']);
Route::put('/work-logs/{id}', [WorkLogController::class, 'update']);
Route::delete('/work-logs/{id}', [WorkLogController::class, 'destroy']);


