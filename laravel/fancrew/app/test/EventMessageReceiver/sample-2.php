<?php
require_once realpath(dirname( __FILE__) . '/../../class/require.app.php');

// サーバーアドレス設定
$_SERVER['SERVER_ADDR'] = '192.168.12.255';

// リモートアドレス設定
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

// リクエストメソッドの設定
$_SERVER['REQUEST_METHOD'] = 'POST';

// 異常 Result の Status code = 9000 のような 4桁の数字となるテスト。
{
    // EventMessage xml の設定
    $_POST['xml'] = null;

    // index.php の実行
    include APP_BASE_PATH . 'public/eventMessage.receive.php';
}
?>