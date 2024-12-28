<?php

// PEAR パッケージ
require_once('Mail/mimeDecode.php');

/**
 * 受信メールのヘッダ、マルチパートの本文、ファイルを解析し、取得するクラス<br />
 * PEAR::Mail_mimeDecode クラスのラッパークラス。<br />
 */
class ROI_MailDecoder {

	/**
	 * 本文<br />
	 *
	 * @var array array('text'=>{text},
	 *                  'html'=>{html})
	 */
	var $body = array('text'=> null , 'html'=> null);

	/**
	 * 添付ファイル<br />
	 *
	 * @var array array[] = array('mime_type'=>{mime_type},
	 *                            'file_name'=>{file_name},
	 *                            'binary'=>{binary})
	 */
	var $attachments = array();


	/**
	 * Mail_mimeDecode オブジェクト<br />
	 *
	 * @var object
	 */
	var $_decoder;


	/**
	 * コンストラクタ<br />
	 *
	 * @param string $raw_mail 受信したそのままメール文字列
	 */
	function __construct(&$raw_mail)
	{
		if (!is_null($raw_mail)) {
			$this->_decode($raw_mail);
		}
	}

	/**
	 * このクラスを使用する際の初期化<br />
	 */
	public function init() {
	}

	/**
	 * 生メールをデコードしてプロパティに代入する<br />
	 *
	 * @param  string $raw_mail 受信したそのままのメール文字列
	 */
	public function _decode (&$raw_mail) {
		if (is_null($raw_mail)) {
			return false;
		}

		$params = array();
		$params['include_bodies'] = true;
		$params['decode_bodies']  = true;
		$params['decode_heders']  = true;

		/*
		 * PEAR Mail_mime::Decode をつかって分解解析する
		 * マルチパートの場合は、本文と添付ファイルに分解する。
		 */
		$this->_decoder =& new Mail_mimeDecode($raw_mail."\n");
		$this->_decoder = $this->_decoder->decode($params);

		$this->_decodeMultiPart($this->_decoder);
	}

	/**
	 * 指定ヘッダを返却する。<br />
	 *
	 * @param  string  $header_name
	 * @return string 指定されたヘッダ
	 */
	public function getRawHeader ($header_name) {
		return isset($this->_decoder->headers["$header_name"]) ? $this->_decoder->headers["$header_name"] : null;
	}

	/**
	 * ヘッダがmimeエンコードされている場合はデコードして返却する。<br />
	 * (携帯絵文字には対応していない。)
	 *
	 * @param  string $header_name
	 * @return string デコードされたヘッダ
	 */
	public function getDecodedHeader($header_name) {
		return mb_decode_mimeheader($this->getRawHeader($header_name));
	}


	/**
	 * 指定ヘッダ内のE-mailアドレスだけを抜き出して返却する。<br />
	 *
	 * @param  string $header_name
	 * @return string
	 * @see extractionEmails()
	 */
	public function getHeaderAddresses ($header_name) {
		return $this->extractionEmails($this->getRawHeader($header_name));
	}

	/**
	 * STATIC
	 * 文字列の中からemailアドレスっぽいものだけを抽出して返却する。<br />
	 *
	 * @param  string $raw_string
	 * @return string $mail_addresses メールアドレスっぽいものを複数あれば,(カンマ)区切りで
	 */
	public function extractionEmails($raw_string) {
		/*
		 * emailアドレスっぽいものの正規表現
		 * see. http://red.ribbon.to/~php/memo_003.php
		 */
		$email_regex_pattern = '/(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|"[^\\\\\x80-\xff\n\015"]*(?:\\\\[^\x80-\xff][^\\\\\x80-\xff\n\015"]*)*")(?:\.(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|"[^\\\\\x80-\xff\n\015"]*(?:\\\\[^\x80-\xff][^\\\\\x80-\xff\n\015"]*)*"))*@(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|\[(?:[^\\\\\x80-\xff\n\015\[\]]|\\\\[^\x80-\xff])*\])(?:\.(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|\[(?:[^\\\\\x80-\xff\n\015\[\]]|\\\\[^\x80-\xff])*\]))*/';

		if (preg_match_all($email_regex_pattern, $raw_string, $matches, PREG_PATTERN_ORDER)) {
			if (isset($matches[0])) {
				return implode(",", $matches[0]);
			}
		}

		return null;
	}

	/**
	 * ■デコードした本文の取得<br />
	 * $this->body['text']; // テキスト形式の本文<br />
	 * $this->body['html']; // html形式の本文<br />
	 *
	 * ■添付ファイルの取得<br />
	 * $this->attachments[$i]['mime_type']; // MimeType<br />
	 * $this->attachments[$i]['file_name']; // ファイル名<br />
	 * $this->attachments[$i]['binary'];    // ファイル本体<br />
	 *
	 * ■メール本文部分の処理<br />
	 * マルチパートの場合も処理する。<br />
	 */
	private function _decodeMultiPart(&$decoder) {

		// マルチパートの場合 それぞれがparts配列内に再配置されているので
		// 再帰的に処理をする。
		if (!empty($decoder->parts)) {
			foreach ($decoder->parts as $part) {
				$this->_decodeMultiPart($part);
			}
		} else {
			if (!empty($decoder->body)) {
				// 本文 (text or html)
				if ('text' === strToLower($decoder->ctype_primary)) {
					if ('plain' === strToLower($decoder->ctype_secondary)) {
						$this->body['text'] =& $decoder->body;

					} else if ('html' === strToLower($decoder->ctype_secondary)) {
						$this->body['html'] =& $decoder->body;

					// その他のtext系マルチパート
					} else {
						$this->attachments[] = array('mime_type'=>$decoder->ctype_primary.'/'.$decoder->ctype_secondary,
						      'file_name'=>$decoder->ctype_parameters['name'],
						      'binary'=>&$decoder->body
						);
					}

				// その他
				} else {
					$this->attachments[] = array('mime_type'=>$decoder->ctype_primary.'/'.$decoder->ctype_secondary,
						  'file_name'=>$decoder->ctype_parameters['name'],
						  'binary'=>&$decoder->body
					);
				}
			}
		}
	}

	/**
	* Toヘッダからアドレスのみを返却する。<br />
	*
	* @return string toアドレス 複数あればカンマ区切りで返す
	* @see getHeaderAddresses(), extractionEmails()
	*/
	public function getToAddr() {
	return $this->getHeaderAddresses('to');
	}

		/**
	* Fromヘッダからアドレスのみを返却する。<br />
	*
	* @return string Fromアドレス 複数あればカンマ区切りで返す
	* @see getHeaderAddresses(), extractionEmails()
	*/
	public function getFromAddr () {
	return $this->getHeaderAddresses('from');
	}

	/**
	 * メールが添付ファイルつきか？<br />
	 *
	 * @return bool 添付付きなら true 無ければ false を返す
	 */
	public function isMultiPart() {
		return (count($this->attachments) > 0) ? true : false;
	}

	/**
	 * 添付ファイルの数を返却する。<br />
	 *
	 * @return int 添付ファイルの数
	 */
	public function getNumOfAttach() {
		return count($this->attachments);
	}

	/**
	* 添付ファイルを返却する。<br />
	*
	* @return array array[] = array('mime_type'=>{mime_type},
	*                            'file_name'=>{file_name},
	*                            'binary'=>{binary})
	*/
	public function getAttachments() {
		return $this->attachments;
	}

	/**
	 * 添付ファイル本体を返却する。<br />
	 *
	 * @param int $index
	 * @return string 添付ファイル本体のバイナリデータ
	 */
	public function getBinary($index) {
		return $this->attachments[$index]['binary'];
	}


	/**
	 * 添付ファイルを保存し、結果を返却する。<br />
	 *
	 * @param  int $index
	 * @param  string $str_path
	 * @return bool  成功なら true 失敗なら false を返す
	 */
	public function saveAttachFile ($index, $str_path) {

		if (!file_exists($str_path)) {
			if (!is_writable(dirname($str_path))) {
				return false;
			}
		}
		else {
			if (!is_writable($str_path)) {
				return false;
			}
		}

		if (!isset($this->attachments[$index])) {
			return false;
		}

		if (is_writable($str_path)) {
			if ($handle = fopen($str_path, "wb")) {
				$binary = $this->attachments[$index]['binary'];
				if (fwrite($handle, $binary) === FALSE) {
					echo "Cannot write to file ($str_path)";
					exit;
				}
				fclose($handle);
				return true;
			}
		} else {
			echo "The file $str_path is not writable";
			return false;
		}

		return false;
	}
	function destroy($filename){
		// 一時ファイルの削除
		unlink($filename);
	}
}