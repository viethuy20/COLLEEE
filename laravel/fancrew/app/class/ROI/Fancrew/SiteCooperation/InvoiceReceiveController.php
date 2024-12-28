<?php
require_once APP_CLASS_PATH . 'ROI/Fancrew/EventMessageReceiver/Controller.php';

require_once CLASS_PATH . 'ROI/MailDecoder.php';

// PEAR パッケージ
require_once 'HTTP/Request2.php';
require_once 'HTTP/Request2/MultipartBody.php';


/**
 * ファンくるAPI サイト連携の OEM 側納品書アップロード CGI 本体 (携帯用)
 *
 * 【エラーコード一覧】
 *
 * E-2000 - 画像がアップロードされていない。
 *
 * @author Nami Mashiko
 *
 */
class ROI_Fancrew_SiteCooperation_InvoiceReceiveController {
	/** exit code: 0 - 正常終了、またはメール破棄。メールサーバにメール送信完了を伝えます。 */
	const EX_OK = 0;

	/** exit code: 75 - 一時的受け取り拒否。メールサーバは後でメール再送を試みます。 */
	const EX_TEMP_FAIL = 75;

	public $logger;

	/** 作成した一時ファイル。このインスタンスが破棄されるときにファイルも削除される。 */
	private $tempFiles = array();

	/**
	 * インスタンスを生成する。
	 *
	 */
	public function __construct() {
		$this->logger = new ROI_Logger($this);

		// 終了時に一時ファイルを削除する。
		register_shutdown_function(array($this, "destroy"));
	}

	/**
	 * @param string $rawMail	受信したそのままメール文字列
	 * @return integer exit コード。
	 * 		0 - 正常終了。または、受け取ったメールを破棄する場合。
	 * 		75 - 一時的に受け取り拒否。メールサーバはメールを再送します。
	 */
	public function exec($rawMail) {
		$decoder =& new ROI_MailDecoder($rawMail);

		// from取得
		$from = $decoder->getFromAddr();

		// toを取得
 		$to = $decoder->getToAddr();

		if ($this->logger->isDebugEnabled()) {
			$this->logger->debug("ROI_Fancrew_InvoiceReceiveController 開始: from=$from, to = $to");
		}

		// パラメータチェック
		if (!isset($to) || !isset($from)) {
			// 必要なパラメータが足りない

			$this->logger->error("必要なパラメータが足りない: from=$from, to=$to");

			// このメールは破棄する。
			return self::EX_OK;
		}

		// 作成された EventMessage を送信してもらうか？0:送信してもらわない、1:送信してもらう
		$send = "0";

		// マルチパートのデータを取得する
		if (!$decoder->isMultipart()) {
			// 画像が添付されていない。

			$content = <<<EOF
画像が添付されていません。
下記メールアドレスに納品書画像を添付して送信ください。
$to
EOF;

			$this->sendErrorMail($from, "エラー:画像が添付されていません。", $content);
			return self::EX_OK;
		}

		$attachments = $decoder->getAttachments();

		// リクエストを送信し結果 xml を受け取り、ユーザにメールを送信する。
		$xmlString = $this->sendHttpRequest($attachments, $to, $send);

		if ($xmlString == null) {
			// API から xml 受取に失敗した。メソッド内でログ出力は既に行なっている。
			// メールサーバに後でメールを再送してもらう。
			return self::EX_TEMP_FAIL;
		}

		$xml = simplexml_load_string($xmlString);

		$code = (int) $xml->Header->Status['code'];

		if ($code != 0) {
			$xmlStatus = $xml->Header->Status;

			$trackingCode = $xmlStatus['trackingCode'];

			$statusMessage = (String) $xmlStatus;

			// メールヘッダ
			$sHeaders = "";

			foreach ($decoder->_decoder->headers as $key => $value) {
				$sHeaders .= $key . ": " .  $value . "\n";
			}

			$message = <<<EOF
納品書アップロードAPI からのエラー応答: code=$code, statusMessage=$statusMessage
■メール
from = $from
to = $to

■メール・ヘッダ
$sHeaders

■API応答
xml = $xmlString

EOF;

			$this->logger->error($message);

			// メールサーバに後でメールを再送してもらう。
			return self::EX_TEMP_FAIL;
		}

		if ($this->logger->isDebugEnabled()) {
			$this->logger->debug("API からの応答 xml: " . $xml->asXML());
		}

		$xmlInvoiceUploadResult = $xml->Data->InvoiceUploadResult;

		// 納品書受付成功？
		$success = (int) $xmlInvoiceUploadResult['success'];

		// ユーザに送信するメール
		$xmlEventMessage = $xmlInvoiceUploadResult->EventMessage;

		// EventMessage に対応するメールをユーザに送信する。
		$controller = new ROI_Fancrew_EventMessageReceiver_Controller();
		$controller->send($xmlEventMessage, $from);

		// 成功
		return self::EX_OK;
	}

	public function sendHttpRequest($attachments, $to, $send) {

		// サイト連携設定
		$config = ROI_Fancrew_Config::get();

		// 納品書・アップロードAPI の URL
		$url = $config->apiBaseURL . 'invoice.upload';

		// HTTP_Request2は例外を投げてくれるのでtryを使う
		try {
			// インスタンスを作成
			$request = new HTTP_Request2();

			// リクエスト先URLをセット
			$request->setUrl($url);

			// 送信方法をPOSTに設定

			// ※HTTP_Request2ではクラス定数になっていることに注意
			$request->setMethod(HTTP_Request2::METHOD_POST);

			// POSTパラメータをセット
			$request->addPostParameter('key', $config->apiKey);
			$request->addPostParameter('to', $to);
			$request->addPostParameter('send', $send);

			$numOfAttach = count($attachments);

			for ($i = 0; $i < $numOfAttach; $i++) {
				$attachment = $attachments[$i];
				$filename = $attachment['file_name'];
				$binary   = $attachment['binary'];
				$mimeType = $attachment['mime_type'];

				$index = $i + 1;

				$name = 'image' . $index;

				// 一時ファイルを作成する。
				$tempFilename = tempnam($config->tempDirectory, "tod");

				file_put_contents($tempFilename, $binary);

				$this->tempFiles[] = $tempFilename;

				$request->addUpload($name, $tempFilename, $filename, $mimeType);
			}

			// リクエストを送信
			$response = $request->send();

			if ($response->getStatus() != 200) {
				$s = var_export($response, true);
				$status = $response->getStatus();

				$sRequestHeaders = var_export($request->getHeaders(), true);

				$message = <<<EOF
納品書アップロードAPI が 200 以外の応答を返した: $status
■Request URL: $url
■Request Headers
$sRequestHeaders

■Request Parameters
key = $config->apiKey
to = $to
send = $send
EOF;

				$this->logger->error($message);
				return null;
			}

			// レスポンスのボディ部を返却
			return $response->getBody();

		} catch (HTTP_Request2_Exception $e) {
			//
		} catch (Exception $e) {
		}

		$sRequestHeaders = var_export($request->getHeaders(), true);

		$message = <<<EOF
納品書アップロードAPI 呼び出し時にエラー発生
■Request URL: $url
■Request Headers
$sRequestHeaders

■Request Parameters
key = $config->apiKey
to = $to
send = $send
EOF;

		$this->logger->error($message, $e);
		return null;
	}

	/**
	 * 「納品書受付エラー」メールをユーザに送信する。
	 *
	 * @param string $to		メール送信先
	 * @param string $title		表題
	 * @param string $content	本文
	 */
	public function sendErrorMail($to, $title, $content) {
		// EventMessage のひな形を読み込む。
		$filename = APP_DATA_PATH . 'EventMessage.xml';
		$xmlEventMessage = simplexml_load_file($filename);

		$xmlEventMessage->EventMessageType['id'] = 23;

		$xmlProperties = $xmlEventMessage->Properties;

		$xmlProperty = $xmlProperties->addChild('Property');
		$xmlProperty['key'] = 'title';
		$xmlProperty['value'] = $title;

		$xmlProperty = $xmlProperties->addChild('Property');
		$xmlProperty['key'] = 'content';
		$xmlProperty['value'] = $content;

		// EventMessage に対応するメールをユーザに送信する。
		$controller = new ROI_Fancrew_EventMessageReceiver_Controller();
		$controller->send($xmlEventMessage, $to);
	}

	public function destroy() {
		// 作成した一時ファイルを削除。

		foreach ($this->tempFiles as $tempFilename) {
			$success = unlink($tempFilename);

			$this->logger->debug("作成した一時ファイルを削除: " . $tempFilename . ", success=" . ($success ? "true" : "false"));
		}
	}
}
