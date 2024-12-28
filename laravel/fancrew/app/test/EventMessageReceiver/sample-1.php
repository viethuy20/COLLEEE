<?php
require_once realpath(dirname( __FILE__) . '/../../class/require.app.php');

// サーバーアドレス設定
$_SERVER['SERVER_ADDR'] = '192.168.12.255';

// リモートアドレス設定
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

// リクエストメソッドの設定
$_SERVER['REQUEST_METHOD'] = 'POST';

// 正常 Result の Status code = 0 となるテスト。
{
	// EventMessage xml の設定
	$filename = BASE_PATH . "test-data/EventMessageReceiver/EventMessages-size1.xml";
	$xmlString = file_get_contents($filename);
	$_POST['xml'] = $xmlString;

	// index.php の実行
	include APP_BASE_PATH . 'public/eventMessage.receive.php';
}

?>