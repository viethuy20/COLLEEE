<?php
require_once APP_CLASS_PATH . 'ROI/Fancrew/SiteCooperation/AbstractController.php';
//require_once CLASS_PATH . 'ROI/Fancrew/SiteCooperation/SmartphonePageFrame.php';
require_once APP_CLASS_PATH . 'ROI/Fancrew/SiteCooperation/SmartphonePageFrame.php';

require_once APP_CLASS_PATH . 'ROI/Fancrew/ShopService.php';

/**
 * ファンくるAPI サイト連携の OEM 側コントローラ (スマートフォン用)
 *
 * _pf 一覧は ROI_Fancrew_SiteCooperation_SmartphonePageFrame を参照してください。
 *
 * 【エラーコード一覧】
 *
 * E-0001 - _pf がない。
 * E-0002 - _pf 値に一致するページフレームが見つからなかった。
 * E-0003 - ページフレームで必要とされるパラメータが見つからなかった。
 *  *
 * @author Kenkichi Mahara
 *
 */
class ROI_Fancrew_SiteCooperation_SmartphoneController extends ROI_Fancrew_SiteCooperation_AbstractController {

	/**
	 * インスタンスを生成する。
	 *
	 */
	public function __construct() {
		parent::__construct(ROI_Fancrew_SiteDeviceType::smartphone);
	}

	public function exec() {
			// _pf 値を元に、呼び出すページを決定する。

		//$_pf = isset($_GET['_pf']) ? $_GET['_pf'] : null;
                $_pf = request()->has('_pf') ? request()->input('_pf') : 'top';

		// 必須パラメータ _pf がない？
		if ($_pf == null) {
                    $this->includeErrorView('E-0001');
                    return;
		}

		// _pf 値を元にページフレーム情報を取得する。
		$pageFrame = ROI_Fancrew_SiteCooperation_SmartphonePageFrame::valueOfParamValue($_pf);

		// _pf 値に一致するページフレームが見つからなかった？
		if ($pageFrame == null) {
			$this->includeErrorView('E-0002');
			return;
		}

		// 指定された画面に遷移する。

		switch ($pageFrame) {
			// OEM 側 TOP ページへの遷移
			case ROI_Fancrew_SiteCooperation_SmartphonePageFrame::$top:
				// TODO TOP 画面に遷移してください。
				//echo "top 画面にリダイレクト\n";
                                //　TOPページ表示
				$this->includeView('top', []);
				break;
			// OEM 側ログイン画面への遷移
			case ROI_Fancrew_SiteCooperation_SmartphonePageFrame::$login:
				// TODO ログイン画面に遷移してください。
				//echo "ログイン画面にリダイレクト\n";
                                $this->response = redirect(route('login'));
				break;
			// 検索結果画面
			case ROI_Fancrew_SiteCooperation_SmartphonePageFrame::$search:
                                // TODO 検索結果画面、または店舗画面に遷移してください。
                                //echo "search画面\n";
                                $this->actionSearchShop();
                                return;
			// OEM 側店舗画面への遷移
			case ROI_Fancrew_SiteCooperation_SmartphonePageFrame::$shop;
				// 店舗IDを取得する。
				$shopId = $this->getShopId();

				// shop_id が渡されるはず。
				if ($shopId == null) {
					$this->includeErrorView('E-0003');
					return;
				}

				$shop = ROI_Fancrew_ShopService::get()->getShop($shopId);

				if ($shop == null) {
					// 指定された店舗がみつからないか、非表示、またはモニター応募が閉じられた。
					$this->includeUserErrorView("指定された店舗が見つかりません。");
					return;
				}

				// モニター情報を取得。
				$monitor = $shop->Monitor;


				// TODO 店舗画面に遷移してください。

				// 応募画面の URL を作成する。
				$params = array(
					'_p' => 'shopEntry',
					'id' => $shopId,
				);

				$shopEntryURL = $this->createRemoteControllerURL($params);

				// サンプル店舗画面を表示
				$viewContext = array(
					'shop' => $shop,
					'monitor' => $monitor,
					'shopEntryURL' => $shopEntryURL,
				);

				$this->includeView("shop.php", $viewContext);
				return;

			// OEM 側店舗地図画面への遷移
			case ROI_Fancrew_SiteCooperation_SmartphonePageFrame::$shop_map;
				// 店舗IDを取得する。
				$shopId = $this->getShopId();

				// shop_id が渡されるはず。
				if ($shopId == null) {
					$this->includeErrorView('E-0003');
					return;
				}

				// 店舗情報を取得。
				$shop = ROI_Fancrew_ShopService::get()->getShop($shopId);

				if ($shop == null) {
					// 指定された店舗がみつからないか、非表示、またはモニター応募が閉じられた。
					$this->includeUserErrorView("指定された店舗が見つかりません。");
					return;
				}

				// モニター情報を取得。
				$monitor = $shop->Monitor;

				// TODO 店舗地図画面に遷移してください。
				//echo "店舗地図画面にリダイレクト: id=" . $shop['id'] . ", name=" . $shop['name'] . "\n";
                                
                                $shop_attributes = $shop->attributes();
                                $map_url = \App\External\Google::getMapUrl($shop_attributes->latitude ?? null, $shop_attributes->longitude ?? null, $shop_attributes->address ?? null);
                                $this->response = redirect($map_url);
                                return;

			// ROI 側レシート提出画面に遷移する
			case ROI_Fancrew_SiteCooperation_SmartphonePageFrame::$receipt:
				// モニターIDを取得する。
				$monitorId = $this->getMonitorId();

				// monitor_id が渡されるはず。
				if ($monitorId == null) {
					$this->includeErrorView('E-0003');
					return;
				}

				// モニターID から店舗情報を取得。
				$shop = ROI_Fancrew_ShopService::get()->getShopByMonitorId($monitorId);

				if ($shop == null) {
					// 指定された店舗がみつからないか、非表示、またはモニター応募が閉じられた。
					$this->includeUserErrorView("指定された店舗が見つかりません。");
					return;
				}

				// モニター情報を取得。
				$monitor = $shop->Monitor;

				// レシート提出画面の URL を作成する。
				$params = array(
									'_p' => 'receipt',
									'monitor_id' => $monitor['id'],
				);

				$url = $this->createRemoteControllerURL($params);

				// ROI から渡されたパラメータをもとに、ROI 側コントローラを呼び出す URL を作成する。
				$remoteControllerURL = $this->createRemoteControllerURL();

				// リダイレクト
				$this->response = redirect($url);
                                return;

			// OEM側 マイページ画面への遷移
			case ROI_Fancrew_SiteCooperation_SmartphonePageFrame::$my;
    			// TODO これは ROI のモニター進捗画面を呼び出すサンプルです。実際には ROI からこの _pf は呼ばれません。

				// ROI 側モニター進捗画面の URL を作成する。
				$params = array(
									'_p' => 'myTop',
				);

				$url = $this->createRemoteControllerURL($params);
                                $this->response = redirect($url);
                                /*
				$viewContext = array(
									'monitorProgressURL' => $url,
				);

				$this->includeView("my.php", $viewContext);
                                
                                */
                                return;

			default:
				;
				break;
		}
	}

	protected function createRemoteControllerURL($params = null) {
	    $url = parent::createRemoteControllerURL($params);

	    return $url;
	}
}
?>