<?php

/**
 * 店舗情報取得 API を呼び出すサービス。
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_Fancrew_ShopService {
	/** シングルトン用インスタンス */
	private static $instance = null;

	public $logger;

	/**
	 * インスタンスを取得する。<br />
	 *
	 * シングルトン形式の呼び出しであるが、このサービスは１つのリクエスト内でのみ同一インスタンスである。
	 */
	public static function get() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		$this->logger = new ROI_Logger($this);
	}

	/**
	 * 店舗ID から店舗情報を取得する。
	 *
	 * @param long $shopId 店舗ID
	 */
	public function getShop($shopId) {
		// 設定を取得
		$config = ROI_Fancrew_Config::get();
		$apiBaseURL = $config->apiBaseURL;
		$apiKey = $config->apiKey;

		$url = $apiBaseURL . 'shops?key=' . $apiKey . '&shop_ids=' . intval($shopId);

		$xml = simplexml_load_file($url);

		// xml の応答がなかった？
		if (!is_object($xml)) {
			$this->logger->error("店舗情報取得時エラー: shopId=" . $shopId);
			return null;
		}

		$xmlStatus = $xml->Header->Status;
		if ($xmlStatus['code'] != 0) {
			$this->logger->error("店舗情報取得失敗: shopId=" . $shopId . ", status=" . $xmlStatus->asXML());
			return null;
		}

		return $xml->Data->Shops->Shop;
	}

	/**
	 * モニターID から店舗情報を取得する。
	 *
	 * @param long $monitorId モニターID
	 */
	public function getShopByMonitorId($monitorId) {
		// 設定を取得
		$config = ROI_Fancrew_Config::get();
		$apiBaseURL = $config->apiBaseURL;
		$apiKey = $config->apiKey;

		$url = $apiBaseURL . 'shops?key=' . $apiKey . '&monitor_ids=' . intval($monitorId);

		$xml = simplexml_load_file($url);

			// xml の応答がなかった？
		if (!is_object($xml)) {
			$this->logger->error("monitorId からの店舗情報取得時エラー: monitorId=" . $monitorId);
			return null;
		}

		$xmlStatus = $xml->Header->Status;
		if ($xmlStatus['code'] != 0) {
			$this->logger->error("monitorId からの店舗情報取得失敗: monitorId=" . $monitorId . ", status=" . $xmlStatus->asXML());
			return null;
		}

		return $xml->Data->Shops->Shop;
	}
}
?>