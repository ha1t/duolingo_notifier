<?php

/**
 * 現在の日付とファイルに書かれた日付を比較する
 *
 * @param string $filePath ファイルパス
 * @return bool 同じ日付であればtrue、異なればfalse
 */
function compareCurrentDateWithFileDate(string $filePath): bool
{
    if (!file_exists($filePath)) {
        throw new Exception("{$filePath} not found");
    }

    $fileDate = file_get_contents($filePath);
    $currentDate = date('Y-m-d');

    return $fileDate === $currentDate;
}

/**
 * 現在の日付をファイルに書き込む
 *
 * @param string $filePath ファイルパス
 * @throws Exception ファイル書き込みエラー
 */
function writeCurrentDateToFile(string $filePath): void
{
    $currentDate = date('Y-m-d');

    // ファイルが書き込み可能かチェック
    if (is_writable($filePath) || !file_exists($filePath)) {
        if (file_put_contents($filePath, $currentDate) === false) {
            throw new Exception("ファイルの書き込みに失敗しました。");
        }
    } else {
        throw new Exception("ファイルに書き込み権限がありません。");
    }
}

function pickMessage(): string
{
    $hour = date('H');

    $message = [
        '今日のDuoおねがいします', // 0
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします', // 10
        '今日のDuoおねがいします',
        '今日のDuoおねがいします', // 12
        '今日のDuoおねがいします', // 13
        '今日のDuoおねがいします',
        '15時のDuo', // 15
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします',
        '今日のDuoおねがいします', // 20
        'ヤバです', // 21
        'ヤバです', // 22
        'マジでギリです', // 23
    ];

    return $message[$hour];
}
