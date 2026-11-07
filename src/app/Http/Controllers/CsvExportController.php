<?php

namespace App\Http\Controllers;

use App\Models\BenchmarkRecord;
use App\Services\Csv\BufferedCsvExporter;
use App\Services\Csv\StreamedCsvExporter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExportController extends Controller
{
    public function __construct(
        private readonly BufferedCsvExporter $bufferedExporter,
        private readonly StreamedCsvExporter $streamedExporter,
    ) {}

    public function index(): View
    {
        return view('csv.index', [
            'recordCount' => BenchmarkRecord::query()->count(),
            'memoryLimit' => ini_get('memory_limit'),
        ]);
    }

    public function export(Request $request): Response|StreamedResponse
    {
        $validated = $request->validate([
            'fetch' => ['required', 'in:get,cursor'],
            'output' => ['required', 'in:buffer,stream'],
            'rows' => ['required', 'integer', 'min:1', 'max:500000'],
        ]);

        $filename = sprintf(
            'benchmark-%s-%s-%d.csv',
            $validated['fetch'],
            $validated['output'],
            $validated['rows'],
        );

        if ($validated['output'] === 'stream') {
            return $this->streamedResponse(
                $validated['fetch'],
                $validated['rows'],
                $filename,
            );
        }

        return $this->bufferedResponse(
            $validated['fetch'],
            $validated['rows'],
            $filename,
        );
    }

    private function bufferedResponse(string $fetch, int $rows, string $filename): Response
    {
        $startedAt = hrtime(true);
        $csv = $this->bufferedExporter->export($fetch, $rows);

        return response($csv, 200, $this->downloadHeaders($filename) + [
            'X-PHP-Memory-Usage' => (string) memory_get_usage(true),
            'X-PHP-Memory-Peak' => (string) memory_get_peak_usage(true),
            'X-Elapsed-Milliseconds' => (string) round((hrtime(true) - $startedAt) / 1_000_000, 2),
        ]);
    }

    private function streamedResponse(string $fetch, int $rows, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($fetch, $rows): void {
            $handle = fopen('php://output', 'w');
            $this->streamedExporter->export($handle, $fetch, $rows);
            fclose($handle);
        }, $filename, $this->downloadHeaders($filename) + [
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /** @return array<string, string> */
    private function downloadHeaders(string $filename): array
    {
        return [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ];
    }
}
