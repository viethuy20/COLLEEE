<?php
require_once realpath(dirname( __FILE__) . '/../class/require.app.php');
require_once APP_CLASS_PATH . 'ROI/Fancrew/SiteCooperation/ReceiptReceiveController.php';

/**
 * レシート提出メール受信プログラム
 *
 * postfix や qmail などから呼ばれます。
 *
 * 標準入力からメールを受け取り、to, from, 添付された１つ以上の画像ファイルを受け取り、
 * ファンくるのレシート・アップロードAPI を呼び出して画像をアップロードします。
 *
 * その後、API から応答 xml の中に含まれる EventMessage を元に、ユーザにメールを返信します。
 */
$controller = new ROI_Fancrew_SiteCooperation_ReceiptReceiveController();

// 生メールのテキストファイルを読み込み
$raw_mail = @file_get_contents("php://stdin");

$exitCode = $controller->exec($raw_mail);

exit($exitCode);
