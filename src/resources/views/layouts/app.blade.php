<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'PHPメモリ検証環境' }}</title>
    <style>
        :root { color-scheme: light; font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; color: #18212f; background: #f4f6f9; }
        * { box-sizing: border-box; }
        body { margin: 0; }
        header { background: #171923; color: white; }
        nav { max-width: 1040px; margin: auto; padding: 18px 24px; display: flex; gap: 24px; align-items: center; }
        nav strong { margin-right: auto; }
        nav a { color: #dfe7f3; text-decoration: none; }
        main { max-width: 1040px; margin: 48px auto; padding: 0 24px 64px; }
        h1 { font-size: clamp(2rem, 5vw, 3.5rem); line-height: 1.1; margin: 0 0 16px; }
        h2 { margin-top: 0; }
        .lead { color: #526070; font-size: 1.1rem; max-width: 760px; line-height: 1.8; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-top: 32px; }
        .card { background: white; border: 1px solid #dfe4ea; border-radius: 14px; padding: 24px; box-shadow: 0 5px 18px rgba(20, 31, 48, .05); }
        .metric { font-size: 2rem; font-weight: 750; margin: 8px 0 0; }
        .muted { color: #667386; }
        .button { display: inline-block; border: 0; border-radius: 9px; padding: 12px 18px; background: #4f46e5; color: white; font-weight: 700; text-decoration: none; cursor: pointer; }
        .button.secondary { background: #e8eafe; color: #3730a3; }
        code { background: #edf0f5; padding: 3px 7px; border-radius: 5px; }
        label { display: block; font-weight: 700; margin-bottom: 7px; }
        select, input { width: 100%; padding: 11px; border: 1px solid #bdc6d2; border-radius: 8px; background: white; font: inherit; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 18px; align-items: end; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #e0e5ec; text-align: left; }
        th { color: #536175; font-size: .9rem; }
    </style>
</head>
<body>
<header>
    <nav>
        <strong>PHPメモリ検証環境</strong>
        <a href="{{ route('benchmark.index') }}">Webリクエスト</a>
        <a href="{{ route('csv.index') }}">CSVエクスポート</a>
    </nav>
</header>
<main>
    @yield('content')
</main>
</body>
</html>
