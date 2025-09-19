<?php

use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

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

Route::get('/logs', function () {
    // Защита: только с правильным ключом
    if (request()->get('key') !== env('LOGS_SECRET', 'your-secret-here')) {
        abort(403, 'Access denied');
    }

    $logPath = storage_path('logs/laravel.log');

    if (!File::exists($logPath)) {
        return response()->json([
            'error' => 'Log file not found',
        ], 404);
    }

    // Читаем файл и берем последние 50 строк
    $lines = File::lines($logPath)->toArray();
    $lastLines = array_slice($lines, -50);

    // Опционально: вернуть как текст с переносами
    return Response::make(implode("\n", $lastLines), 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
})->name('logs.view');