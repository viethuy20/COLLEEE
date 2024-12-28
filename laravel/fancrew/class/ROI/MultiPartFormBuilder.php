<?php

/**
 * ファイルを POST するためのビルダー
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_MultiPartFormBuilder {
	private $boundary;

	private $datas;

	public function __construct() {
		$this->boundary = substr(md5(rand(0,32000)), 0, 10);
		$this->datas = array();
	}

	/**
	 * パラメータを追加する。
	 *
	 * @param string $name	パラメータ名
	 * @param string $value	パラメータ値
	 */
	public function addParameter($name, $value) {
		$boundary = $this->boundary;

		$data = "--$boundary\r\n";
		$data .= "Content-Disposition: form-data; name=\"" . $name . "\"\r\n\r\n" . $value . "\r\n";

		$this->datas[] = $data;
	}

	/**
	 * バイナリ・データを追加する。
	 *
	 * @param string $name			パラメータ名
	 * @param string $binary		バイナリ・ダータ
	 * @param string $contentType	データの Content-Type
	 */
	public function addData($name, $binary, $contentType) {
		$boundary = $this->boundary;

		$data = "--$boundary\r\n";
		$data .= "Content-Disposition: form-data; name=\"" . $name . "\"\r\n";
		$data .= "Content-Type: " . $contentType . "\r\n";
		// $data .= "Content-Transfer-Encoding: binary\r\n\r\n";
		$data .= "\r\n";

		$data .= $binary . "\r\n";
		$this->datas[] = $data;
	}

	protected function toBody() {
		$boundary = $this->boundary;

		$a = $this->datas;
		$a[] = "--$boundary--\r\n";

		$data = implode('', $a);

		return $data;
	}

	/**
	 * stream_context_create() を呼び出し、 ストリームコンテキストを作成する。
	 *
	 * @return resource ストリームコンテキスト
	 */
	public function toStreamContext() {
		$body = $this->toBody();

		$options = array('http' => array(
		        'method' => 'POST',
		    	'header' => 'Content-Type: multipart/form-data; boundary="' . $this->boundary . '"',
		        'content' => $body,
		));

		file_put_contents("c:/1.txt",$body);

		return stream_context_create($options);
	}
}

?>