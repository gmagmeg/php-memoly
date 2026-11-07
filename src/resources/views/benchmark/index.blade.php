@extends('layouts.app', ['title' => 'Webリクエスト | PHPメモリ検証環境'])

@section('content')
    <h1>単純なWebリクエスト</h1>
    <p class="lead">PHP-FPMがリクエストを受け、LaravelでJSONを返す最小構成です。負荷試験ツールから <code>/api/ping</code> を繰り返し呼び出せます。</p>

    <div class="grid">
        <section class="card">
            <div class="muted">検証データ</div>
            <div class="metric">{{ number_format($recordCount) }} 件</div>
        </section>
        <section class="card">
            <div class="muted">PHP memory_limit</div>
            <div class="metric">{{ $memoryLimit }}</div>
        </section>
    </div>

    <div class="grid">
        <section class="card">
            <h2>JSONエンドポイント</h2>
            <p><code>GET /api/ping</code></p>
            <p class="muted">応答には現在とピークのPHPメモリ使用量を含みます。</p>
            <a class="button" href="{{ route('benchmark.ping') }}">リクエストを実行</a>
        </section>
        <section class="card">
            <h2>CSVの比較へ</h2>
            <p class="muted">DB取得方法とレスポンス出力方法を組み合わせて比較できます。</p>
            <a class="button secondary" href="{{ route('csv.index') }}">CSV画面を開く</a>
        </section>
    </div>
@endsection
