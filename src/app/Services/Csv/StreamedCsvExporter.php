<?php

namespace App\Services\Csv;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class StreamedCsvExporter
{
    /**
     * レコードを読みながらCSVをレスポンスへ逐次出力する。
     *
     * @param  resource  $handle
     */
    public function export($handle, string $fetch, int $rows): void
    {
        $this->putRow($handle, ['id', 'name', 'email', 'company', 'notes', 'created_at']);

        $written = 0;

        foreach ($this->records($fetch, $rows) as $record) {
            $this->putRow($handle, [
                $record->id,
                $record->name,
                $record->email,
                $record->company,
                $record->notes ?: '-',
                $record->created_at,
            ]);

            $written++;

            if ($written % 1000 === 0) {
                fflush($handle);
            }
        }
    }

    private function records(string $fetch, int $rows): iterable
    {
        $query = $this->query($rows);

        return $fetch === 'get' ? $query->get() : $query->cursor();
    }

    private function query(int $rows): Builder
    {
        return DB::table('benchmark_records')
            ->select(['id', 'name', 'email', 'company', 'notes', 'created_at'])
            ->orderByDesc('created_at')
            ->orderBy('id')
            ->limit($rows);
    }

    /** @param resource $handle */
    private function putRow($handle, array $row): void
    {
        fputcsv($handle, $row, ',', '"', '', "\n");
    }
}
