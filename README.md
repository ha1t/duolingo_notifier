# Duolingo Notifier

Duolingoの日次ストリークが未完了の場合にPushbulletでプッシュ通知を送るスクリプト。

## 機能

- Duolingo APIからストリーク状況を取得
- 今日のレッスンが未完了であればPushbulletで通知
- 時間帯に応じた通知メッセージの切り替え
- 完了済みの日はスキップ（date.txtで管理）

## 必要なもの

- PHP (`allow_url_fopen = On`、curl拡張は不要)
- Pushbullet APIキー
- Pushbulletの送信先デバイスID

## セットアップ

1. `settings.php` を作成して以下を設定:

```php
<?php
$apiKey = 'YOUR_PUSHBULLET_API_KEY';
$device_iden = 'YOUR_DEVICE_IDEN';
$username = 'YOUR_DUOLINGO_USERNAME';
```

2. cronなどで定期実行:

```
0 * * * * php /path/to/duolingo_notifier/main.php
```

## ファイル構成

| ファイル | 説明 |
|---|---|
| `main.php` | エントリポイント |
| `Duolingo.php` | Duolingo API クライアント |
| `PushbulletNotifier.php` | Pushbullet通知送信 |
| `settings.php` | 設定ファイル（.gitignore対象） |
| `list_devices.php` | Pushbulletデバイス一覧取得用 |
| `date.txt` | 最終完了日の記録（.gitignore対象） |

## ライセンス

MIT
