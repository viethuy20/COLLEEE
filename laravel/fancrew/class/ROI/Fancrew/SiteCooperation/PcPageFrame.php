<?php

/**
 * PC 版サイト連携で、ROI から OEM 様ページを呼び出す時の _pf パラメータ一覧の enum
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_Fancrew_SiteCooperation_PcPageFrame {
// iframe 呼び出しなし

	/** TOP ページ */
	public static $top;

	/** ログイン画面 */
	public static $login;

	/**
	 * 検索結果画面<br />
	 *
	 * パラメータ:
	 *		shop_id - 落選・抽選待ちになった店舗のID
	 *		action - 落選 or 抽選待ちを表す。 action に入る値は {@link ActionParamValue} を参照してください。
	 */
	public static $search;

	/**
	 * 店舗画面<br />
	 *
	 * パラメータ:
	 * 		shop_id - 店舗ID
	 */
	public static $shop;

	/** ポイント確認画面 */
	public static $points;

// iframe 呼び出しあり

	/**
	 * 店舗画面(iframe あり)<br />
	 *
	 * パラメータ:
	 * 		shop_id - 店舗ID
	 */
	public static $shopF;

	/**
	 * 進捗画面<br />
	 *
	 * パラメータ:
	 * 		monitor_id - モニターID
	 */
	public static $flow;

	/** マイページ */
	public static $my;

// enum
	private static $values;

// プロパティ

	/** _pf 値 */
	public $paramValue;

	/** iframe 表示を必要とするフレームか？ */
	public $hasIframe;

	/**
	 * enum 初期化
	 *
	 * この php ファイルの最後で呼ばれる。
	 */
	public static function init() {
		// iframe 呼び出しなし
		self::$top		= new self('top'		, false);
		self::$login	= new self('login'		, false);
		self::$search	= new self('search'	, false);
		self::$shop 	= new self('shop'		, false);
		self::$points	= new self('points'	, false);

		// iframe 呼び出しあり
		self::$shopF	= new self('shopF'		, true);
		self::$flow		= new self('flow'		, true);
		self::$my		= new self('my'		, true);
	}

	function __construct($paramValue, $hasIframe) {
		$this->paramValue = $paramValue;
		$this->hasIframe = $hasIframe;

		self::$values[] = $this;
	}

	/**
	 * enum 一覧を返す。
	 *
	 * @return ROI_Fancrew_PcPageFrame の array
	 */
	public static function values() {
		return self::$values;
	}

	/**
	 * paramValue に一致するオブジェクトを返す。
	 *
	 * @param string $paramValue _pf 値
	 * @return ROI_Fancrew_PcPageFrame オブジェクト。一致するものがなければ null を返す。
	 */
	public static function valueOfParamValue($paramValue) {

		foreach (self::values() as $obj) {
			if ($obj->paramValue == $paramValue) {
				return $obj;
			}
		}
		return null;
	}

	public function __toString() {
		return $this->paramValue;
	}
}

// enum 初期化
ROI_Fancrew_SiteCooperation_PcPageFrame::init();
?>