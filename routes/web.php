<?php

use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', [CarController::class, 'index'])->name('home');

Route::get('/schedule-run', function () {
    // Защита: только с правильным секретным ключом
    if (request()->get('key') !== env('CRON_SECRET')) {
        abort(403, 'Access denied');
    }

    // Запускаем Laravel Scheduler
    Artisan::call('schedule:run');

    // Возвращаем ответ для логов
    return response()->json([
        'status' => 'success',
        'time' => now(),
        'output' => Artisan::output(),
    ]);
})->name('schedule.run');