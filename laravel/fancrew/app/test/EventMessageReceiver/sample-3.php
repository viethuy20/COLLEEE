<?php
require_once realpath(dirname( __FILE__) . '/../../class/require.app.php');

// サーバーアドレス設定
$_SERVER['SERVER_ADDR'] = '192.168.12.255';

// リモートアドレス設定
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

// リクエストメソッドの設定
$_SERVER['REQUEST_METHOD'] = 'POST';

// アクセスIPアドレスチェックエラーとなるテスト。
{
    // リモートアドレス設定
    $_SERVER['REMOTE_ADDR'] = '127.255.255.255';

    // index.php の実行
    include APP_BASE_PATH . 'public/eventMessage.receive.php';

    // バッチ環境では送信されたヘッダを取得できない。
    $headers = headers_list();

    var_dump($headers);
}

?>