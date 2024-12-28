<?php
// PEAR パッケージ
require_once 'Mail.php';
require_once 'Mail/mime.php';

/**
 * 簡単なメールを送信する。
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_SimpleMailSender {
	/** メール送信パラメータ */
	private $smtpParams;

	/** 半角カナを強制的に全角カナに変換するか？ デフォルト: true */
	public $convertToZenkakuKana = true;

	public function __construct($smtpParams) {
		// メール送信パラメータ
		$this->smtpParams = $smtpParams;
	}

	/**
	 * メールを送信する。
	 *
	 * @param string $from
	 * @param string $to
	 * @param string $subject
	 * @param string $body
	 */
	public function send($from, $to, $subject, $body) {
		$jisEncoding = "iso-2022-jp";

		// 半角カナを強制的に全角カナに変換するか？
		if ($this->convertToZenkakuKana) {
			$subject = mb_convert_kana($subject, 'KV');
			$body    = mb_convert_kana($body, 'KV');
		}

		$mail = @Mail::factory("smtp", $this->smtpParams);

		$mime = new Mail_Mime("\n");
		$mime->setTxtBody(mb_convert_encoding($body, $jisEncoding, "UTF-8"));
		$mime->setParam("head_charset", 'iso-2022-jp');
		$mime->setParam("text_charset", 'iso-2022-jp');
		$mime->setParam("text_encoding", '7bit');

		$mailBody = $mime->get();

		$mailSubject = mb_encode_mimeheader($subject, "iso-2022-jp");

		$addHeaders = array(
		  "To" => $to,
		  "From" => $from,
		  "Subject" => $mailSubject,
		);

		$mailHeaders = $mime->headers($addHeaders);

		return $mail->send($to, $mailHeaders, $mailBody);
	}


	public function makeFrom($fromAddress, $fromName) {
		$jisEncoding = "iso-2022-jp";

		// 半角カナを強制的に全角カナに変換するか？
		if ($this->convertToZenkakuKana) {
			$fromName    = mb_convert_kana($fromName, 'KV');
		}

		$name = mb_encode_mimeheader($fromName, "iso-2022-jp");

		$from = $name . " <" . $fromAddress . ">";

		return $from;
	}

}

?>