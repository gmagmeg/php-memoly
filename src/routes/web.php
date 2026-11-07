<?php

use App\Http\Controllers\BenchmarkController;
use App\Http\Controllers\CsvExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BenchmarkController::class, 'index'])->name('benchmark.index');
Route::get('/api/ping', [BenchmarkController::class, 'ping'])->name('benchmark.ping');

Route::get('/csv', [CsvExportController::class, 'index'])->name('csv.index');
Route::get('/csv/export', [CsvExportController::class, 'export'])->name('csv.export');
