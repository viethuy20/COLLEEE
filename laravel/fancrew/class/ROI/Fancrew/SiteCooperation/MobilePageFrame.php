<?php

/**
 * 携帯版サイト連携で、ROI から OEM 様ページを呼び出す時の _pf パラメータ一覧
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_Fancrew_SiteCooperation_MobilePageFrame {
	/** TOP ページ */
	public static $top;

	/** ログイン画面 */
	public static $login;

	/** 店舗画面 */
	public static $shop;

	/** 店舗地図画面 */
	public static $shop_map;

	/** マイページ画面 */
	public static $my;

	/**
	 * レシート提出画面<br />
	 *
	 * ■パラメータ:
	 * 		monitor_id - モニターID
	 *
	 * ■OEM 様側の処理:
	 *
	 * (1) OEM 様からユーザに送信する「レシート提出受付完了メール」の中に、OEM 様コントローラを呼び出すリンクを記述してください。
	 *
	 *   例:
	 *       レシート画像を受け付けました。
	 *       以下のリンクをクリックして、レシート提出処理を完了させてください。
	 *
	 *       http://example.com/fancrew/mobile.pages?_pf=receipt&_pf.monitor_id=2525
	 *
	 * (2) ユーザがこのリンクをクリックしたら、ROI 側コントローラにリダイレクトしてください。
	 *
	 *       http://classic.fancrew.jp/i/inline/pages?_p=receipt&monitor_id=2525
	 *
	 */
	public static $receipt;

// enum
	private static $values;

// プロパティ

	/** _pf 値 */
	public $paramValue;

	/**
	 * enum 初期化
	 *
	 * この php ファイルの最後で呼ばれる。
	 */
	public static function init() {
		// iframe 呼び出しなし
		self::$top        		= new self('top');
		self::$login        	= new self('login');
		self::$shop         	= new self('shop');
		self::$shop_map         = new self('shop_map');
		self::$receipt	        = new self('receipt');
		self::$my               = new self('my');
	}

	function __construct($paramValue) {
		$this->paramValue = $paramValue;

		self::$values[] = $this;
	}

	/**
	 * enum 一覧を返す。
	 *
	 * @return ROI_Fancrew_MobilePageFrame の array
	 */
	public static function values() {
		return self::$values;
	}

	/**
	 * paramValue に一致するオブジェクトを返す。
	 *
	 * @param string $paramValue _pf 値
	 * @return ROI_Fancrew_MobilePageFrame オブジェクト。一致するものがなければ null を返す。
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
ROI_Fancrew_SiteCooperation_MobilePageFrame::init();

?>