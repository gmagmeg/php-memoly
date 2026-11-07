# Laravel / PHP / MySQL / nginx Docker environment

最新版の PHP、Laravel、MySQL と nginx を使うローカル開発環境です。

## 構成

- PHP 8.5 FPM（動作確認時: 8.5.12）
- Laravel 13.x（動作確認時: 13.40.0）
- MySQL latest（動作確認時: 26.10.0）
- nginx stable-alpine（動作確認時: 1.30.6）
- Composer 2

## 初回セットアップ

Docker Desktop を起動してから、次を実行します。Laravel の `.env` 作成、イメージのビルド、起動、アプリケーションキー生成、マイグレーションまで実行されます。

```bash
make init
```

セットアップ後、<http://localhost:8080> を開いてください。

## 検証ページ

- Webリクエスト: <http://localhost:8080/>
- JSONエンドポイント: <http://localhost:8080/api/ping>
- CSVエクスポート: <http://localhost:8080/csv>

CSVでは `get()` / `cursor()` と、一括組み立て / 逐次出力の4通りを比較できます。初期状態では10万件の検証データを投入します。件数を変更する場合はLaravel側の `.env` に `BENCHMARK_RECORDS=500000` などを設定し、`make seed` を実行してください。

### コードレビュー教材として使う場合

一括版と逐次版は「出力方式だけが異なる同じ論理CSV」という前提ですが、レビューで仕様差分を見つけられるよう、`BufferedCsvExporter` と `StreamedCsvExporter` に実装を分けています。両クラスを比較し、CSVの列、形式、順序など利用者に見える差がないかレビューしてください。

## よく使うコマンド

```bash
make up        # コンテナを起動
make down      # コンテナを停止
make logs      # ログを表示
make shell     # PHP コンテナに入る
make test      # Laravel のテストを実行
make seed      # CSV検証用データを再生成
make versions  # 各ソフトウェアのバージョンを確認
```

ポートやデータベースの認証情報は、ルートの `.env` で変更できます。Laravel から MySQL へ接続するホスト名は `mysql` です。

データベースを含めて完全に破棄する場合だけ、`docker compose down -v` を実行してください。
