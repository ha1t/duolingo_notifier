<?php

class PushbulletNotifier {
    private $apiKey;
    private $endpoint = 'https://api.pushbullet.com/v2/pushes';

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function sendNotification($title, $body, $device_iden = null) {
        $data = array(
            'type' => 'note',
            'title' => $title,
            'body' => $body,
        );
        if ($device_iden !== null) {
            $data['device_iden'] = $device_iden;
        }

        $jsonBody = json_encode($data);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Access-Token: {$this->apiKey}\r\nContent-Type: application/json",
                'content' => $jsonBody,
                'ignore_errors' => true,
            ],
        ]);
        $response = file_get_contents($this->endpoint, false, $context);

        if ($response === false) {
            return 'file_get_contentsエラー: HTTPリクエストに失敗しました。';
        } else {
            $result = json_decode($response, true);
            if (isset($result['error'])) {
                return 'Pushbullet APIエラー: ' . $result['error']['message'];
            } else {
                return 'プッシュ通知を送信しました。';
            }
        }
    }

    public function listDevices() {
        $end_point = "https://api.pushbullet.com/v2/devices";
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Access-Token: {$this->apiKey}\r\nContent-Type: application/json",
                'ignore_errors' => true,
            ],
        ]);
        $response = file_get_contents($end_point, false, $context);

        if ($response === false) {
            return 'file_get_contentsエラー: HTTPリクエストに失敗しました。';
        } else {
            $result = json_decode($response, true);
            if (isset($result['error'])) {
                return 'Pushbullet APIエラー: ' . $result['error']['message'];
            } else {
                return $result['devices'];
            }
        }
    }
}

// クラスの使用例
/*
$apiKey = 'YOUR_API_KEY';
$notifier = new PushbulletNotifier($apiKey);
$result = $notifier->sendNotification('通知タイトル', '通知本文');
echo $result;
*/

