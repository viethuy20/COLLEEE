<?php
/*
 * 開発環境用設定
 */

/* -------- ファンくるAPI -------- */

$fancrewConfig = ROI_Fancrew_Config::get();

// API ベース URL
//$fancrewConfig->apiBaseURL = 'https://beta.fancrew.jp/api/4.0/';
$fancrewConfig->apiBaseURL = 'https://classic.fancrew.jp/api/4.0/';

// ROI 側コントローラのベース URL (PC用)
//$fancrewConfig->remotePcControllerBaseURL = "https://beta.fancrew.jp/inline/pages";
$fancrewConfig->remotePcControllerBaseURL = "https://classic.fancrew.jp/inline/pages";

// ROI 側コントローラのベース URL (携帯用)
//$fancrewConfig->remoteMobileControllerBaseURL = "http://beta.fancrew.jp/i/inline/pages";
$fancrewConfig->remoteMobileControllerBaseURL = "https://classic.fancrew.jp/i/inline/pages";

// ROI 側コントローラのベース URL (スマートフォン用)
//$fancrewConfig->remoteSmartphoneControllerBaseURL = "https://beta.fancrew.jp/sp/inline/pages";
$fancrewConfig->remoteMobileControllerBaseURL = "https://classic.fancrew.jp/sp/inline/pages";

// ROI から入手した情報: 暗号鍵
//$fancrewConfig->secretKey = "secret";
$fancrewConfig->secretKey = "AOyOsDDv";

// ROI から入手した情報: API ID
//$fancrewConfig->apiId = 46;
$fancrewConfig->apiId = 22;

// ROI から入手した情報: API Key
//$fancrewConfig->apiKey = "sampleKey";
//$fancrewConfig->apiKey = "realus_test_20120514jw";
$fancrewConfig->apiKey = "n2aFk9fw3YfeM-A6VJr";

// 一時ファイルのディレクトリ。
$fancrewConfig->tempDirectory = "c:/temp";

// SCodeEncoder の暗号処理方法
$fancrewConfig->cryptoType = 1;

// 配布用マスタID
$fancrewConfig->affMasterId = "27364";

// クライアントID
$fancrewConfig->affClientId = "FANCREW_MONITOR";

// 代理店
$fancrewConfig->affCompany = "fncr";

// 配布用タイトル
$fancrewConfig->affTitle = "モニターでためる";

// Proxy
$fancrewConfig->useProxy = true;

// SSL_VERIFY
$fancrewConfig->sslVerify = false;

/* -------- ファンくるAPI EventMessageReceiver -------- */
/*
$eventMessageReceiverConfig = ROI_Fancrew_EventMessageReceiver_Config::get();

// アクセスを許可する IP
$eventMessageReceiverConfig->permitIPs = array(
	// 開発環境
	'127.0.0.1',

	// ROI 本番/テスト環境
	'113.43.105.136/29'
);

// メール送信先設定
$eventMessageReceiverConfig->smtpParams = array(
	'host'=>'mailserver',
	'port'=>'25',
	'auth' => false,
);

// メールテンプレート・ディレクトリのパス
$eventMessageReceiverConfig->mailTemplatePath = APP_BASE_PATH . 'mail/';
*/

/* -------- その他 -------- */

?>