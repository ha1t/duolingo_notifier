<?php

use DuolingoNotifier\Duolingo;
use DuolingoNotifier\PushbulletNotifier;

require_once 'PushbulletNotifier.php';
require_once 'Duolingo.php';
require_once 'helpers.php';

// Pushbullet
$apiKey = null; // pushbullet api token
$device_iden = null;

// Duolingo
$username = "DUOLINGO_USERNAME";
$jwt = 'DUOLINGO_JWT';
$filePath = dirname(__FILE__) . '/date.txt';

if (file_exists('settings.php')) {
    require_once 'settings.php';
}

// 日付を比較
if (compareCurrentDateWithFileDate($filePath)) {
    // do nothing
} else {
    $notifier = new PushbulletNotifier($apiKey);

    try {
        $duo = new Duolingo($username, $jwt);
        $duo->makeDuolingoSetting();
        $data = $duo->getUserData();

        if (!isset($data['streak_extended_today'])) {
            throw new Exception('cannot get user data: streak_extended_today');
        }

        if ($data['streak_extended_today'] === false) {
            $result = $notifier->sendNotification('Duolingo Notifier', pickMessage(), $device_iden);
        } else {
            writeCurrentDateToFile($filePath);
        }
    } catch (Exception $e) {
        echo "エラー: " . $e->getMessage() . PHP_EOL;
        $result = $notifier->sendNotification('Duolingo Notifier', $e->getMessage(), $device_iden);
    }
}
