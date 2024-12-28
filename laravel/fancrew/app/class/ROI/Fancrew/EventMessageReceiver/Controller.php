<?php
require_once APP_CLASS_PATH . 'ROI/Fancrew/EventMessageReceiver/LocalSystemService.php';
require_once CLASS_PATH . 'ROI/SimpleMailSender.php';
require_once CLASS_PATH . 'ROI/Fancrew/MailReader.php';
require_once CLASS_PATH . 'ROI/Fancrew/EventMessageReceiver/ResponseWriter.php';

/**
 * EventMessage の push 送信を受け取る。
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_Fancrew_EventMessageReceiver_Controller {
	public $logger;

	// _EventMessageReceiver 設定
	protected $config;

	private $localSystemService;
	private $sender;
	private $mailReader;
	private $from;
	private $trackingCode;

	public function __construct() {
		$this->logger = new ROI_Logger($this);

		// 設定
		$this->config = ROI_Fancrew_EventMessageReceiver_Config::get();

		// ローカルシステムとの繋ぎこみ
		$this->localSystemService = new ROI_Fancrew_EventMessageReceiver_LocalSystemService();

		// メール送信を行うプログラム
		$this->sender = new ROI_SimpleMailSender($this->config->smtpParams);

		// メールテンプレートファイル読込みプログラム
		$this->mailReader = new ROI_Fancrew_MailReader($this->config->mailTemplatePath);

		// メール送信元
		$fromAddress    = $this->localSystemService->getMailFromAddress();
		$fromName       = $this->localSystemService->getMailFromName();

		$this->from = $this->sender->makeFrom($fromAddress, $fromName);

		// トラッキング・コードを取得する。
		$system = ROI_System::get();
		$this->trackingCode = $system->getTrackingCode();
	}

	protected function outputError($responseCode, $errorMessage) {
		$responseWriter = new ROI_ApiResponseXmlWriter($this->trackingCode);
		$responseWriter->outputXml($responseCode, $errorMessage);
	}

	public function exec() {
		// アクセス元 IP アドレスチェック。
		$remoteIP = $_SERVER['REMOTE_ADDR'];

		$isPermitIP = chekPermitIP($this->config->permitIPs, $remoteIP);
		if (!$isPermitIP) {
			// 許可されていない IPアドレス
			header('HTTP1.1 403 Forbidden', true, 403);
			return;
		}

		// リクエストメソッドチェック。POST のみ許可する。
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			// 許可されていないメソッド
			header('HTTP1.1 403 Forbidden', true, 403);
			return;
		}

		// POSTデータを取得する。
		$xmlString = isset($_POST['xml']) ? $_POST['xml'] : null;

		if ($xmlString == null) {
			$this->outputError("9000", "必須パラメータ xml なし");
			return;
		}

		$xml = simplexml_load_string($xmlString);

		if (!isset($xml) || !isset($xml->EventMessage)) {
			$this->outputError("9000", "xml 解析失敗");
			return;
		}

		$this->execMain($xml);
	}

	protected function execMain($xml) {
		// 結果格納用
		$responses = array();

		// 受信したデータの処理開始
		foreach ($xml->EventMessage as $xmlEventMessage) {
			$response = $this->send($xmlEventMessage);

			$responses[] = $response;
		}

		// 結果出力
		$responseWriter = new ROI_Fancrew_EventMessageReceiver_ResponseWriter($this->trackingCode);
		$responseWriter->outputXml(null, null, $responses);
	}

	/**
	 * EventMessage に対応するメールを送信する。
	 *
	 * @param xml		$xmlEventMessage
	 * @param string	$to					送信先メールアドレス。null の時は EventMessage の User タグの id に対応するメールアドレスにメールを送信する。
	 *
	 * @return array この EventMessage に対する処理結果
	 */
	public function send($xmlEventMessage, $to = null) {
		// この EventMessage に対する処理結果
		$response = array();

		$response['id'] = (int) $xmlEventMessage['id'];

		// TODO 使用するメールテンプレートファイルのサブディレクトリを指定します。固定値にするか、あるいはメールアドレスから自動判別する処理を実装ください。
		// 1 : pc, 2 : mobile
		$userDeviceType = 2;

		// ユーザ情報を取得する。
		$user = $xmlEventMessage->Application->User;

		// ローカルユーザ情報を追加する。
		$this->localSystemService->setLocalUserInformation($user);

		// 送信するメールを生成する(タイトル、本文)
		try {
			$mail = $this->mailReader->getMail($xmlEventMessage, $user, $userDeviceType);
		} catch (Exception $e) {
			// 処理失敗
			$response['success'] = false;
			$response['error'] = 'E-0002: メールテンプレートで必要としているプロパティが渡されていない可能性があります。';

			// ROI 側にリトライを要求する。
			$response['retryRequested'] = false;

			$this->logger->error($response['error'], $e);

			return $response;
		}

		// 表題
		$subject = $mail['subject'];

		// 本文
		$body = $mail['body'];

		// 送信先
		if ($to == null) {
			$to = (string) $user['mailAddress'];
		}

		// メール送信処理実行
		$isSuccess = $this->sender->send($this->from, $to, $subject, $body);

		if ($isSuccess) {
			// 処理成功
			$response['success'] = true;
		} else {
			// 処理失敗
			$response['success'] = false;

			$response['error'] = 'E-0001';

			// ROI 側にリトライを要求する。
			$response['retryRequested'] = true;
		}

		return $response;
	}
}
?>