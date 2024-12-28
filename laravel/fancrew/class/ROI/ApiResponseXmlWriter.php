<?php

/**
 * ROI の API 応答用 xml を出力する 。
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_ApiResponseXmlWriter {
	/** 追跡コード。呼び出しごとに生成される、固有な文字列。*/
	protected $trackingCode;

	public function __construct($trackingCode) {
		$this->trackingCode = $trackingCode;
	}

	/**
	 * 応答 xml を出力する。
	 *
	 * @param string $errorCode		エラーコード。エラーがなければ 0 または null を指定する。
	 * @param string $errorMessage	エラーメッセージ。エラーがなければ null を指定する。
	 * @param mixed $data			データ部作成メソッド createInnerDataElement() に渡すデータ。データがなければ null を指定する。
	 */
	public function outputXml($errorCode = null, $errorMessage = null, $data = null) {
		$dom = $this->createXml($errorCode, $errorMessage, $data);

		// HTTPヘッダを設定
		header("Content-type: text/xml; charset=UTF-8");

		// DomXMLをXML形式で出力
		echo $dom->saveXML();
	}

	public function createXml($errorCode = null, $errorMessage = null, $data = null) {
		// 結果出力
		$dom = new DomDocument('1.0');
		$dom->encoding = 'UTF-8';
		$dom->formatOutput = true; // 出力XMLを整形(改行,タブ)する

		$resultElement = $dom->appendChild($dom->createElement('Result'));

		// ヘッダ部
		$headerElement = $this->createHeaderElement($dom, $errorCode, $errorMessage);
		$resultElement->appendChild($headerElement);

		// データ部
		if ($data != null) {
			$dataElement = $dom->createElement('Data');
			$resultElement->appendChild($dataElement);

			$this->createInnerDataElement($dom, $dataElement, $data);
		}

		return $dom;
	}

	/**
	 * XML のヘッダ部を生成します。
	 *
	 * @param Object $dom DOM オブジェクト。xml エレメント生成用。
	 * @param string $errorCode エラーコード。成功の時は 0 または null を設定する。
	 * @package string $errorMessage エラーメッセージ。エラーがなければ null を設定する。
	 * @return Header タグ
	 */
	private function createHeaderElement($dom, $errorCode = null, $errorMessage = null) {
		$headerElement = $dom->createElement('Header');
		$headerStatusElement = $headerElement->appendChild($dom->createElement('Status'));

		if ($errorCode == null) {
			$errorCode = '0';
		}

		// エラーコード: 0 なら正常。
		$headerStatusElement->setAttribute('code', $errorCode);

		// トラッキング・コード
		$headerStatusElement->setAttribute('trackingCode', $this->trackingCode);

		// エラーメッセージ
		if ($errorMessage != null) {
			$headerStatusElement->appendChild($dom->createTextNode($errorMessage));
		}

		return $headerElement;
	}

	/**
	 * XML のデータ部の中のタグを生成します。
	 *
	 * @param Object $dom			DOM オブジェクト。xml エレメント生成用。
	 * @param Object $dataElement	Data タグのエレメント。必要に応じ、ここにエレメントを追加する。
	 * @param mixed $data			Data タグ内のタグ作成に必要なデータ。
	 */
	protected function createInnerDataElement($dom, $dataElement, $data) {
	}

	public static function asBoolean($v) {
		if ($v == true) {
			return "true";
		} else {
			return "false";
		}
	}

}
?>