<?php

require_once 'PushbulletNotifier.php';

// Pushbullet
$apiKey = null; // pushbullet api token
$device_iden = null;

if (file_exists('settings.php')) {
    require_once 'settings.php';
}

$notifier = new PushbulletNotifier($apiKey);
$result = $notifier->listDevices();

var_dump($result);

