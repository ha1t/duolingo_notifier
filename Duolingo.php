<?php

class Duolingo {
    private $duolingoName;
    private $duolingoJwt;
    private $latestNumber;
    private $language;
    private $duolingoData;

    /**
     * コンストラクタ
     *
     * @param string $duolingoName ユーザー名
     * @param string $duolingoJwt JWT認証トークン
     * @param int $latestNumber 取得する最新アイテム数
     */
    public function __construct($duolingoName, $duolingoJwt, $latestNumber = 50) {
        $this->duolingoName = $duolingoName;
        $this->duolingoJwt = $duolingoJwt;
        $this->latestNumber = $latestNumber;
        $this->language = null;
        $this->duolingoData = null;
    }

    /**
     * Duolingoの設定を取得して初期化する
     *
     * @return bool 成功した場合はtrue
     * @throws Exception 処理中にエラーが発生した場合
     */
    public function makeDuolingoSetting() {
        try {
            // 基本リクエストヘッダー
            $headers = [
                'User-Agent: Mozilla/5.0',
                'Accept: application/json',
                'Content-Type: application/json'
            ];

            // ユーザー情報を取得
            $userHeaders = $headers;
            $userHeaders[] = 'Authorization: Bearer ' . $this->duolingoJwt;

            $ch = curl_init("https://www.duolingo.com/api/1/users/show?username={$this->duolingoName}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $userHeaders);
            $response = curl_exec($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode !== 200) {
                throw new Exception("ユーザープロファイルの取得に失敗しました。ステータスコード: " . $httpCode);
            }

            $this->duolingoData = json_decode($response, true);
            curl_close($ch);

            // 学習言語を設定
            $this->language = $this->duolingoData['learning_language'] ?? null;

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * ユーザーデータを取得する
     *
     * @return array|null ユーザーデータ
     */
    public function getUserData() {
        return $this->duolingoData;
    }

    /**
     * 現在の学習言語を取得する
     *
     * @return string|null 学習言語
     */
    public function getLearningLanguage() {
        return $this->language;
    }

}

/*
$username = "YOUR_USERNAME";
$jwt = 'JWT_TOKEN';
$duo = new Duolingo($username, $jwt);
$duo->makeDuolingoSetting();
$data = $duo->getUserData();
var_dump($data['streak_extended_today']);
 */

