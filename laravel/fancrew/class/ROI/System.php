<?php
/**
 * システム情報を提供するクラス。<br />
 */
class ROI_System {
	private static $instance = null;

	/** 追加設定 */
	private $config;

	/** トラッキング・コード。リクエストごとに生成される、固有な文字列。*/
	private $trackingCode;

	/**
	 * シングルトン・インスタンスを取得する。
	 *
	 * @return System
	 */
	public static function &get() {
		if (is_null(self::$instance)) {
			trigger_error("initInstance で初期化されていません。");
		}

		return self::$instance;
	}

	/**
	 * シングルトン・インスタンスを初期化する。
	 *
	 * @param string $localConfigFile 設定ファイル。稼働マシンに固有の設定がある場合、この設定ファイルに記述する。<br />
	 * 		この引数は最初に初期化時のみ必要。
	 * @return NULL
	 */
	public static function &initInstance($localConfigFile = null) {
		if (is_null(self::$instance)) {
			self::$instance = new self($localConfigFile);
		}

		return self::$instance;
	}

	/**
	 * コンストラクタ.
	 */
	private function __construct($localConfigFile = null) {
		// 文字コード初期化
		$this->init_mbstring();

		// デフォルト
		$config = array(
		// 本番モードか？
    		'isProduct' => false,
		);

		if ($localConfigFile != null && file_exists($localConfigFile)) {
			include_once $localConfigFile;
		}

		$this->config = $config;

		// エラー設定
		{
			$level = E_ALL;

			if ($this->isProduct()) {
				// 本番では警告は出力しない。
				$level &= ~E_NOTICE;
			}

			// PHP 5.3.0対応
			if (error_reporting() > 6143) {
				$level &= ~E_DEPRECATED;
			}

			error_reporting($level);
		}
	}

	private function init_mbstring() {
		$charset = 'UTF-8';
		$locale = 'ja_JP.UTF-8';

		ini_set("mbstring.http_input", $charset);
		ini_set("mbstring.http_output", $charset);
		ini_set("auto_detect_line_endings", 1);
		ini_set("default_charset", $charset);
		ini_set("mbstring.internal_encoding", $charset);
		ini_set("mbstring.detect_order", "auto");
		ini_set("mbstring.substitute_character", "none");

		setlocale(LC_ALL, $locale);
	}

	/**
	 * この動作環境は本番環境か？
	 */
	function isProduct() {
		return $this->config['isProduct'];
	}

	/**
	 * ホストを表すユニークな文字列を返す。<br />
	 *
	 * 複数ホストでサービスが実行される場合、どのホストで発生したエラーかを追跡する情報として利用。<br />
	 *
	 */
	private static function createHostTrackingCode($serverAddr = null) {
		if ($serverAddr == null) {
			// コマンドラインから実行しているときは、SERVER_ADDR はセットされていない。
			if (!isset($_SERVER['SERVER_ADDR'])) {
				return "";
			}

			$serverAddr = $_SERVER['SERVER_ADDR'];
		}

		// ホストの IP アドレスからホスト・トラッキングコードを作成する。
		// IP アドレスの下２つを16進 4 桁で表したもの。例: 192.168.12.6 => 0c06
		$address = explode(".", $serverAddr);

		$hexAddress1 = dechex($address[2]);
		$hexAddress2 = dechex($address[3]);

		// 16進数に変換した結果、1桁だった場合は 0 を付加する。
		if (strlen($hexAddress1) == 1) {
			$hexAddress1 = '0' . $hexAddress1;
		}
		if (strlen($hexAddress2) == 1) {
			$hexAddress2 = '0' . $hexAddress2;
		}

		return $hexAddress1 . $hexAddress2;
	}

	/**
	 * トラッキング・コードを取得する。
	 *
	 * @return string トラッキング・コード
	 */
	public function getTrackingCode($serverAddr = null) {
		if ($this->trackingCode == null) {
			// アクセス時刻[ミリ秒] + $hostTrackinCode で、trackingCode を生成する。
			$accessTimeMillis = ceil(microtime(true) * 1000);
			$this->trackingCode = $accessTimeMillis . '.' . self::createHostTrackingCode($serverAddr);
		}

		return $this->trackingCode;
	}
}

?>
