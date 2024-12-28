<?php
require_once CLASS_PATH . 'ROI/Fancrew/Template.php';

/**
 * メールテンプレートを読み込む。
 *
 */
class ROI_Fancrew_MailReader {
	/** テンプレート・ディレクトリ */
	private $templateDirectory;

	public function __construct($templateDirectory) {
		$this->templateDirectory = $templateDirectory;
	}

	/**
	 * テンプレートファイルに連想配列の値をセットして、ヘッダとボディを生成します。
	 *
	 * @param array		$xmlEventMessage	ROI から受け取った EventMessage タグ
	 * @param array		$user             	ユーザ情報
	 * @param string	$templateDirectory	メールテンプレートディレクトリ
	 * @param string	$userDeviceType		ユーザ端末種別: 1 = pc, 2 = 携帯
	 * @return array	$mail				タイトルとボディを格納した連想配列
	 */
	public function getMail($xmlEventMessage, $user, $userDeviceType) {

		// テンプレートに連想配列の値をセット
		$template = new ROI_Fancrew_Template();
		$template->assign('application', $xmlEventMessage->Application);
		$template->assign('event', $xmlEventMessage->Application->Monitor);
		$template->assign('shop', $xmlEventMessage->Application->Monitor->Shop);
		$template->assign('user', $user);

		// 追加オプションの設定
		if (isset($xmlEventMessage->Properties) && isset($xmlEventMessage->Properties->Property)) {
			$properties = $xmlEventMessage->Properties;

			foreach ($properties->Property as $property) {
				$template->assign($property['key'], $property['value']);
			}
		}

		// 端末の種別に応じて、読み込むディレクトリを変更する: pc または mobile
		$templateSubDirectory = self::getTemplateSubDirectoryNameByUserDeviceType($userDeviceType);
		$template->setTemplateDirectory($this->templateDirectory . $templateSubDirectory . '/');

		// テンプレートファイル名の取得
		$templateFilename = self::getTemplatefilename($xmlEventMessage->EventMessageType['id']);

		// テンプレートファイルの解析処理実行
		try {
		    $content = $template->fetch($templateFilename);
		} catch (Exception $e) {
			$filename = $template->getTemplateDirectory() . $templateFilename;
			$sException = $e->__toString();

			$message = <<<EOF
テンプレートファイル解析失敗: filename=$filename
Exception: $sException
EOF;

		    throw new Exception($message);
		}

		// メールテンプレートのヘッダとボディを切り分ける。
		// メールテンプレートのヘッダとボディの境界は、改行コード2つとする。
		list($headers, $body) = preg_split("/(\r|\n|\r\n)(\r|\n|\r\n)/", $content, 2);

		// ヘッダーテンプレートかフッターテンプレートがある場合、ボディの前後に追加する。
		$headerTemplate = file_get_contents($this->templateDirectory . $templateSubDirectory . '/' . 'header.txt');
		// 存在しない、または空の場合、 null なる
		if ($headerTemplate != null) {
			$body = mb_convert_encoding($headerTemplate, "UTF-8", 'sjis-win') . $body;
		}

		$footerTemplate = file_get_contents($this->templateDirectory . $templateSubDirectory . '/' . 'footer.txt');
		// 存在しない、または空の場合、 null なる
		if ($footerTemplate != null) {
			$body .= mb_convert_encoding($footerTemplate, "UTF-8", 'sjis-win');
		}

		// ヘッダから件名を抜き取り、$this->subject に格納する。
		preg_match('/Subject: ([^\r\n]+)/', $headers, $matches);
		$subject = $matches[1];

		$result = array('subject' => $subject, 'body' => $body);

		$this->subject = NULL;

		return $result;
	}

	/**
	 * メールテンプレートのファイル名を取得します。
	 *
	 * @param $eventMessageType
	 */
	protected static function getTemplatefilename($eventMessageType) {

		$filename;

		// 1桁の場合は先頭に 0 を追加する。
		if (strlen($eventMessageType) == 1) {
			$filename = '0';
		} else {
			$filename = '';
		}

		$filename .= $eventMessageType .= '.txt';

		return $filename;
	}

	/**
	 * ユーザ端末種別により、メールテンプレートファイルのサブディレクト名を取得します。
	 *
	 * @param string $userDeviceType	ユーザ端末種別
	 */
	protected static function getTemplateSubDirectoryNameByUserDeviceType($userDeviceType) {

		$templateSubDirectory = NULL;

		if ($userDeviceType == 1) {
			$templateSubDirectory = 'pc';
		} else if ($userDeviceType == 2) {
			$templateSubDirectory = 'mobile';
		} else {
			throw new Exception("未対応の userDeviceType: " . $userDeviceType);
		}

		return $templateSubDirectory;
	}
}
?>