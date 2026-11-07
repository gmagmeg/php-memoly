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

## よく使うコマンド

```bash
make up        # コンテナを起動
make down      # コンテナを停止
make logs      # ログを表示
make shell     # PHP コンテナに入る
make test      # Laravel のテストを実行
make versions  # 各ソフトウェアのバージョンを確認
```

ポートやデータベースの認証情報は、ルートの `.env` で変更できます。Laravel から MySQL へ接続するホスト名は `mysql` です。

データベースを含めて完全に破棄する場合だけ、`docker compose down -v` を実行してください。
