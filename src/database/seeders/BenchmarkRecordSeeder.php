<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BenchmarkRecordSeeder extends Seeder
{
    public function run(): void
    {
        $total = max(1, (int) env('BENCHMARK_RECORDS', 100000));
        $now = now();

        DB::table('benchmark_records')->truncate();

        for ($offset = 0; $offset < $total; $offset += 1000) {
            $records = [];
            $end = min($offset + 1000, $total);

            for ($index = $offset + 1; $index <= $end; $index++) {
                $records[] = [
                    'management_number' => sprintf('M%06d', $index),
                    'name' => sprintf('検証ユーザー %06d', $index),
                    'email' => sprintf('user%06d@example.test', $index),
                    'phone_number' => sprintf('090-%04d-%04d', intdiv($index, 10000) % 10000, $index % 10000),
                    'company' => sprintf('検証株式会社 %03d', $index % 500),
                    'notes' => str_repeat(sprintf('CSVメモ-%06d ', $index), 8),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('benchmark_records')->insert($records);
        }
    }
}
