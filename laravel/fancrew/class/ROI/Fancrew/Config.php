<?php

/**
 * ファンくるAPI 設定
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_Fancrew_Config {
	/** API のベース URL */
	public $apiBaseURL;

	/** ROI 側コントローラのベース URL (PC用) */
	public $remotePcControllerBaseURL;

	/** ROI 側コントローラのベース URL (携帯用) */
	public $remoteMobileControllerBaseURL;

	/** ROI 側コントローラのベース URL (スマートフォン用) */
	public $remoteSmartphoneControllerBaseURL;

	/** ROI から入手した情報: 暗号鍵 */
	public $secretKey;

	/** ROI から入手した情報: API ID */
	public $apiId;

	/** ROI から入手した情報: API Key */
	public $apiKey;

	/** 一時ファイルを作成するディレクトリ */
	public $tempDirectory;

	/** SCodeEncoder の暗号処理方法 */
	public $cryptoType;

	/** シングルトン用インスタンス */
	private static $instance = null;

	/**
	 * シングルトン・インスタンスを取得する。
	 *
	 * @return ROI_Fancrew_SiteCooperationSettings インスタンス
	 */
	public static function &get() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * このクラスは初期化済みか？
	 *
	 * @return boolean 初期化済みなら true を返す。
	 */
	public function inited() {
		// とりあえず secretKey だけ見て判断する。
		if (isset($this->secretKey)) {
			return true;
		}

		return false;
	}
}
?>