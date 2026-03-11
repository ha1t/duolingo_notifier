<?php

class Duolingo {
    private $duolingoName;
    private $duolingoData;

    /**
     * コンストラクタ
     *
     * @param string $duolingoName ユーザー名
     * @param string $duolingoJwt JWT認証トークン（互換性のため残すが未使用）
     */
    public function __construct($duolingoName, $duolingoJwt = null) {
        $this->duolingoName = $duolingoName;
        $this->duolingoData = null;
    }

    /**
     * Duolingoの設定を取得して初期化する
     *
     * @return bool 成功した場合はtrue
     * @throws Exception 処理中にエラーが発生した場合
     */
    public function makeDuolingoSetting() {
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept: application/json',
        ];

        $fields = urlencode('users{id,username,streak,streakData{currentStreak{startDate,endDate,length}}}');
        $url = "https://www.duolingo.com/2017-06-30/users?username={$this->duolingoName}&fields={$fields}";

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $headers),
                'ignore_errors' => true,
            ],
        ]);
        $response = file_get_contents($url, false, $context);

        $httpCode = 0;
        if (isset($http_response_header[0])) {
            preg_match('/HTTP\/\S+\s+(\d+)/', $http_response_header[0], $matches);
            $httpCode = isset($matches[1]) ? (int)$matches[1] : 0;
        }

        if ($httpCode !== 200) {
            throw new Exception("ユーザープロファイルの取得に失敗しました。ステータスコード: " . $httpCode);
        }

        $data = json_decode($response, true);

        if (!isset($data['users'][0])) {
            throw new Exception("ユーザーが見つかりませんでした: " . $this->duolingoName);
        }

        $user = $data['users'][0];

        // 旧APIとの互換性のため streak_extended_today を算出
        $streakExtendedToday = false;
        if (isset($user['streakData']['currentStreak']['endDate'])) {
            $endDate = $user['streakData']['currentStreak']['endDate'];
            $today = date('Y-m-d');
            $streakExtendedToday = ($endDate === $today);
        }

        $this->duolingoData = [
            'streak_extended_today' => $streakExtendedToday,
            'streak' => $user['streak'] ?? 0,
            'username' => $user['username'] ?? $this->duolingoName,
        ];

        return true;
    }

    /**
     * ユーザーデータを取得する
     *
     * @return array|null ユーザーデータ
     */
    public function getUserData() {
        return $this->duolingoData;
    }
}
