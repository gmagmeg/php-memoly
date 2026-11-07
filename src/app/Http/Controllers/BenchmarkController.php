<?php

namespace App\Http\Controllers;

use App\Models\BenchmarkRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class BenchmarkController extends Controller
{
    public function index(): View
    {
        return view('benchmark.index', [
            'recordCount' => BenchmarkRecord::query()->count(),
            'memoryLimit' => ini_get('memory_limit'),
        ]);
    }

    public function ping(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'php_version' => PHP_VERSION,
            'memory_usage_bytes' => memory_get_usage(true),
            'memory_peak_bytes' => memory_get_peak_usage(true),
        ]);
    }
}
