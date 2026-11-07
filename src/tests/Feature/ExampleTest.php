<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_ping_endpoint_returns_memory_information(): void
    {
        $this->getJson('/api/ping')
            ->assertOk()
            ->assertJsonPath('status', 'ok')
            ->assertJsonStructure([
                'timestamp',
                'php_version',
                'memory_usage_bytes',
                'memory_peak_bytes',
            ]);
    }

    public function test_csv_page_is_available(): void
    {
        $this->get('/csv')
            ->assertOk()
            ->assertSee('CSVエクスポート');
    }

    public function test_buffered_csv_can_be_downloaded(): void
    {
        DB::table('benchmark_records')->insert([
            [
                'management_number' => 'M000002',
                'name' => '二番',
                'email' => 'second@example.test',
                'phone_number' => '090-0000-0002',
                'company' => '検証株式会社',
                'notes' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'management_number' => 'M000001',
                'name' => '一番',
                'email' => 'first@example.test',
                'phone_number' => '090-0000-0001',
                'company' => '検証株式会社',
                'notes' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this->get('/csv/export?fetch=get&output=buffer&rows=10')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8')
            ->assertHeader('x-php-memory-peak');

        $lines = preg_split('/\r\n|\n|\r/', trim((string) $response->getContent()));

        $this->assertSame("\xEF\xBB\xBFID,管理番号,氏名,会社名,メールアドレス,電話番号,備考,登録日時", $lines[0]);
        $this->assertSame('M000001', str_getcsv($lines[1], ',', '"', '')[1]);
        $this->assertSame('M000002', str_getcsv($lines[2], ',', '"', '')[1]);
    }

    public function test_streamed_csv_disables_nginx_buffering(): void
    {
        $this->get('/csv/export?fetch=cursor&output=stream&rows=10')
            ->assertOk()
            ->assertHeader('x-accel-buffering', 'no');
    }
}
