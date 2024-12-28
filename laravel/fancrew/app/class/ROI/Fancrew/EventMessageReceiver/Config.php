<?php

/**
 * 設定
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_Fancrew_EventMessageReceiver_Config {
	/** アクセスを許可する IP 一覧 */
	public $permitIPs;

	/** メール送信用パラメータ */
	public $smtpParams;

	/** メールテンプレート・ディレクトリのパス */
	public $mailTemplatePath;

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
}
?>