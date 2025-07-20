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

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Access-Token: ' . $this->apiKey,
            'Content-Type: application/json',
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return 'cURLエラー: ' . curl_error($ch);
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
        $ch = curl_init($end_point);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Access-Token: ' . $this->apiKey,
            'Content-Type: application/json',
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return 'cURLエラー: ' . curl_error($ch);
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

