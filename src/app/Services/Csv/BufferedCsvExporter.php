<?php

namespace App\Services\Csv;

use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class BufferedCsvExporter
{
    /**
     * CSV全体をPHPメモリ上で組み立てる。
     *
     * @return string 完成したCSV
     */
    public function export(string $fetch, int $rows): string
    {
        $handle = fopen('php://memory', 'w+');

        // Excelで開いたときに日本語が文字化けしないようにする。
        fwrite($handle, "\xEF\xBB\xBF");
        $this->putRow($handle, ['ID', '管理番号', '氏名', '会社名', 'メールアドレス', '電話番号', '備考', '登録日時']);

        foreach ($this->records($fetch, $rows) as $record) {
            $this->putRow($handle, [
                $record->id,
                $record->management_number,
                $record->name,
                $record->company,
                $record->email,
                $record->phone_number,
                $record->notes ?? '',
                CarbonImmutable::parse($record->created_at)
                    ->setTimezone('Asia/Tokyo')
                    ->format('Y/m/d H:i:s'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    private function records(string $fetch, int $rows): iterable
    {
        $query = $this->query($rows);

        return $fetch === 'get' ? $query->get() : $query->cursor();
    }

    private function query(int $rows): Builder
    {
        return DB::table('benchmark_records')
            ->select(['id', 'management_number', 'name', 'email', 'company', 'phone_number', 'notes', 'created_at'])
            ->orderBy('management_number')
            ->orderBy('id')
            ->limit($rows);
    }

    /** @param resource $handle */
    private function putRow($handle, array $row): void
    {
        fputcsv($handle, $row, ',', '"', '', "\r\n");
    }
}
