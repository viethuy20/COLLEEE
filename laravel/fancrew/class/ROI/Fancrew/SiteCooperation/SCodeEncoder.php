<?php

require_once CLASS_PATH . 'ROI/Crypt.php';
require_once CLASS_PATH . 'ROI/SimpleCrypt.php';

/**
 * SCode 生成クラス。
 *
 * @author Yoshitada Tanahara
 *
 */
class ROI_Fancrew_SiteCooperation_SCodeEncoder {
    /** バージョン */
    private $VERSION = 1;

    /** 暗号鍵 */
	private $secretKey;
	/** API_ID */
	private $apiId;
	/** APIキー */
	private $apiKey;
	/** 暗号化方法 */
	private $cryptoType;

	/**
	 * コンストラクタ
	 *
	 * @param $secretKey 暗号鍵
	 * @param $apiId     API_ID
	 * @param $apiKey    APIキー
	 */
	function __construct($secretKey, $apiId, $apiKey, $cryptoType = 1) {
		$this->secretKey = $secretKey;
		$this->apiId = $apiId;
		$this->apiKey = $apiKey;
		$this->cryptoType = $cryptoType;
	}

	/**
	 * SCode を生成します。
	 *
	 * @param $apiUserId APIユーザID
	 * @return string
	 */
	function createSCode($apiUserId, $datetime = null) {
		if ($apiUserId === null || $apiUserId === 0) {
			$apiUserId = "";
		}

	    // 現在日時
	    if ($datetime == null) {
		    $datetime = date("Y/m/d H:i:s");
	    }

	    $text = "" . $this->VERSION . "\t" . $this->apiKey . "\t" . $apiUserId . "\t" . $datetime;

	    if ($this->cryptoType == 2) {
	    	$encryptData = ROI_SimpleCrypt::encrypt($this->secretKey, $text, 4);
	    } else {
	    	// デフォルト
	    	$encryptData = ROI_Crypt::encrypt($this->secretKey, $text);
	    }

	    $base46UrlSafeData = $this->apiId . ":" . ROI_Crypt::base64_encode_urlsafe($encryptData);

	    return $base46UrlSafeData;

	}
}

?>