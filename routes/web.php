<?php

use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

Route::get('/', [CarController::class, 'index'])->name('home');

Route::get('/schedule-run', function () {
    if (request()->get('key') !== 'kj8Fg3mPq9XzL2wR') {
        abort(403, 'Access denied');
    }

    $exitCode = Artisan::call('schedule:run');
    $output = Artisan::output();

    Log::info('Schedule run executed', compact('exitCode', 'output'));

    return response()->json([
        'status' => $exitCode === 0 ? 'success' : 'failure',
        'time' => now(),
        'output' => $output,
        'exit_code' => $exitCode,
    ]);
})->name('schedule.run');