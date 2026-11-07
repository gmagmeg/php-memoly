<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $this->get('/csv/export?fetch=get&output=buffer&rows=10')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8')
            ->assertHeader('x-php-memory-peak');
    }

    public function test_streamed_csv_disables_nginx_buffering(): void
    {
        $this->get('/csv/export?fetch=cursor&output=stream&rows=10')
            ->assertOk()
            ->assertHeader('x-accel-buffering', 'no');
    }
}
