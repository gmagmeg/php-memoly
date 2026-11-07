@extends('layouts.app', ['title' => 'CSVエクスポート | PHPメモリ検証環境'])

@section('content')
    <h1>CSVエクスポート</h1>
    <p class="lead"><code>get()</code> / <code>cursor()</code> と、一括組み立て / 逐次出力を自由に組み合わせ、PHP・DB・ブラウザのどこでメモリを使うか比較できます。</p>

    <div class="grid">
        <section class="card">
            <div class="muted">利用可能なレコード</div>
            <div class="metric">{{ number_format($recordCount) }} 件</div>
        </section>
        <section class="card">
            <div class="muted">PHP memory_limit</div>
            <div class="metric">{{ $memoryLimit }}</div>
        </section>
    </div>

    <section class="card" style="margin-top: 20px">
        <form method="get" action="{{ route('csv.export') }}">
            <div class="form-grid">
                <div>
                    <label for="fetch">DB取得方法</label>
                    <select id="fetch" name="fetch">
                        <option value="get">get() — 全件をCollectionへ保持</option>
                        <option value="cursor">cursor() — 1件ずつ取得</option>
                    </select>
                </div>
                <div>
                    <label for="output">CSV出力方法</label>
                    <select id="output" name="output">
                        <option value="buffer">一括 — PHPメモリ上で組み立て</option>
                        <option value="stream">逐次 — ブラウザへストリーム</option>
                    </select>
                </div>
                <div>
                    <label for="rows">出力件数</label>
                    <input id="rows" name="rows" type="number" min="1" max="500000" value="{{ min(100000, $recordCount ?: 100000) }}" required>
                </div>
                <button class="button" type="submit">CSVをダウンロード</button>
            </div>
        </form>
    </section>

    <section class="card" style="margin-top: 20px">
        <h2>比較するポイント</h2>
        <table>
            <thead><tr><th>設定</th><th>データの保持場所</th><th>想定される傾向</th></tr></thead>
            <tbody>
                <tr><td><code>get()</code></td><td>PHPのCollection</td><td>件数に応じてPHPメモリが増加</td></tr>
                <tr><td><code>cursor()</code></td><td>現在処理中のレコード</td><td>PHPオブジェクトの保持量を抑制</td></tr>
                <tr><td>一括出力</td><td><code>php://memory</code></td><td>完成したCSV全体をPHPで保持</td></tr>
                <tr><td>逐次出力</td><td>ネットワーク・ブラウザ側へ移動</td><td>PHPのCSV保持量を抑制</td></tr>
            </tbody>
        </table>
    </section>

    <section class="card" style="margin-top: 20px">
        <h2>コードレビュー課題</h2>
        <p class="muted">一括版と逐次版は、出力方式だけが異なる同じ論理CSVとして扱う想定です。2つの実装から、利用者に見える仕様差分がないか確認してください。</p>
    </section>
@endsection
