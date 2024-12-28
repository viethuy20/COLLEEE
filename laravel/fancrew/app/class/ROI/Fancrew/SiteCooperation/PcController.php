<?php
require_once APP_CLASS_PATH . 'ROI/Fancrew/SiteCooperation/AbstractController.php';
require_once CLASS_PATH . 'ROI/Fancrew/SiteCooperation/PcPageFrame.php';

/**
 * ファンくるAPI サイト連携の OEM 側コントローラ (PC用)
 *
 * _pf 一覧は ROI_Fancrew_SiteCooperation_PcPageFrame を参照してください。
 *
 * 【エラーコード一覧】
 *
 * E-0001 - _pf がない。
 * E-0002 - _pf 値に一致するページフレームが見つからなかった。
 * E-0003 - ページフレームで必要とされるパラメータが見つからなかった。
 * E-0004 - 未対応のページフレームを検出した。
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_Fancrew_SiteCooperation_PcController extends ROI_Fancrew_SiteCooperation_AbstractController {

	/**
	 * インスタンスを生成する。
	 *
	 */
	public function __construct() {
		parent::__construct(ROI_Fancrew_SiteDeviceType::pc);
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
		$pageFrame = ROI_Fancrew_SiteCooperation_PcPageFrame::valueOfParamValue($_pf);

		// _pf 値に一致するページフレームが見つからなかった？
		if ($pageFrame == null) {
			$this->includeErrorView('E-0002');
			return;
		}

		// iframe を持つページフレームか？
		if ($pageFrame->hasIframe) {
			// ROI から渡されたパラメータをもとに、ROI 側コントローラを呼び出す URL を作成する。
			$remoteControllerURL = $this->createRemoteControllerURL();

			$shopId = null;
			$monitorId = null;

			switch ($pageFrame) {
				// 応募用 店舗フレーム
				case ROI_Fancrew_SiteCooperation_PcPageFrame::$shopF:
					// 店舗IDを取得する。
					$shopId = $this->getShopId();

					// shop_id が渡されるはず。
					if ($shopId == null) {
						$this->includeErrorView('E-0003');
						return;
					}
					break;

				// モニター進捗フレーム (アンケート提出画面等)
				case ROI_Fancrew_SiteCooperation_PcPageFrame::$flow:
					// モニターIDを取得する。
					$monitorId = $this->getMonitorId();

					// monitor_id が渡されるはず。
					if ($monitorId == null) {
						$this->includeErrorView('E-0003');
						return;
					}
					break;

				// マイページ・フレーム
				case ROI_Fancrew_SiteCooperation_PcPageFrame::$my:
					break;

				default:
					// 未対応のページフレームを検出した。
					$this->includeErrorView('E-0004');
				return;
			}

			// monitor_id や shop_id が指定されていれば、店舗情報などを取得する。
			$shop = null;
			$monitor = null;

			if ($monitorId != null) {
				$shop = ROI_Fancrew_ShopService::get()->getShopByMonitorId($monitorId);

				if ($shop == null) {
					// 指定された店舗がみつからないか、非表示、またはモニター応募が閉じられた。
					$this->includeUserErrorView("指定された店舗が見つかりません。");
					return;
				}

				$monitor = $shop->Monitor;
			} else if ($shopId != null) {
				$shop = ROI_Fancrew_ShopService::get()->getShop($shopId);

				if ($shop == null) {
					// 指定された店舗がみつからないか、非表示、またはモニター応募が閉じられた。
					$this->includeUserErrorView("指定された店舗が見つかりません。");
					return;
				}

				$monitor = $shop->Monitor;
			}

			$viewContext = array(
				'remoteControllerURL' => $remoteControllerURL,
				'shop' => $shop,
				'monitor' => $monitor,
			);

			// ページフレームを表示
			//$viewFilename = 'pageFrame/' . $pageFrame->paramValue . '.php';
                        $viewFilename = 'iframe';

			$this->includeView($viewFilename, $viewContext);
			return;
		}

		// iframe を持たないフレーム。OEM 様サイト内のいずれかのページに遷移する。

		switch ($pageFrame) {
			// TOP ページ
			case ROI_Fancrew_SiteCooperation_PcPageFrame::$top:
				// TODO TOP ページに遷移してください。
				//echo "top画面\n";

				$this->includeView('top', []);
                                return;
			// ログイン画面
			case ROI_Fancrew_SiteCooperation_PcPageFrame::$login:
				// TODO ログイン画面に遷移してください。
				//echo "login画面\n";
				$this->response = redirect(route('login'));
                                return;

			// 検索結果画面 (落選、抽選待ち)
			case ROI_Fancrew_SiteCooperation_PcPageFrame::$search:
				// 「落選」や「抽選待ち」の時に呼ばれる。

                                /*
				// 店舗IDを取得する。
				$shopId = $this->getShopId();

				// 店舗ID が指定されなかった？
				if ($shopId == null) {
					$this->includeErrorView('E-0003');
					return;
				}
                                */

				/* action。次のいずれかが入る。この値に応じて落選画面や抽選待ち画面を表示してください。
				 * また、ROI(ファンくる) では検索結果画面に落選表示を行なっていますが、
				* 店舗画面に落選画面を出す、といった実装でも ok です。
				*
				* loose - 落選
				* waitLot - 抽選待ち。
				*
				* $_GET に格納されるパラメータ名は . が _ になる点に注意。
				*/
				//$action = isset($_GET['_pf_action']) ? $_GET['_pf_action'] : null;
                                $action = request()->has('_pf_action') ? request()->input('_pf_action') : 'search';

				switch ($action) {
                                    case 'search':
                                        $this->actionSearchShop();
                                    return;
                                    case 'loose':
                                    case 'waitLot':
                                        // 店舗IDを取得する。
                                        $shopId = $this->getShopId();
                                        
                                        // 店舗ID が指定されなかった？
                                        if ($shopId == null) {
                                            $this->includeErrorView('E-0003');
                                            return;
                                        }
                                        
                                        $this->response = redirect(route('fancrew.pages', ['action' => 'pages']).'?'. http_build_query(['_pf' => 'shop', '_pf.shop_id' => $shopId]));
                                    return;
                                    /*
					case 'loose':
						// 落選
						break;

					case 'waitLot':
						// 抽選待ち
						break;
                                     */
					default:
						// 未対応の action が指定された。
						$this->includeErrorView('E-0003');
					return;
				}

				// TODO 検索結果画面、または店舗画面に遷移してください。
				echo "search画面\n";
                                return;

			// 店舗画面
			case ROI_Fancrew_SiteCooperation_PcPageFrame::$shop:
				// 店舗IDを取得する。
				$shopId = $this->getShopId();

				// 店舗ID が指定されなかった？
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

				// TODO 店舗画面に遷移してください。
				//echo "shop: id=" . $shop['id'] . ", name=" . $shop['name'] . "\n";

				// モニター情報を取得。
				$monitor = $shop->Monitor;
                                
                                $shop_attributes = $shop->attributes();
				if (!isset($shop_attributes)) {
					// 指定された店舗がみつからないか、非表示、またはモニター応募が閉じられた。
					$this->includeUserErrorView("指定された店舗が見つかりません。");
					return;
				}
                                
				$params = ['_pf' => 'shopF', '_pf.shop_id' => intval($shop_attributes->id, 10), '_p' => 'shopEntry', 'id' => intval($shop_attributes->id, 10)];
				$shopEntryURL = route('fancrew.pages', ['action' => 'pages']).'?'.http_build_query($params);
                                
				// サンプル店舗画面を表示
				$this->includeView("shop", ['shop' => $shop, 'monitor' => $monitor, 'shopEntryURL' => $shopEntryURL,]);
				return;

			// ポイント履歴画面
			case ROI_Fancrew_SiteCooperation_PcPageFrame::$points:
				// TODO ポイント確認画面に遷移してください。
				//echo "points画面\n";
                            	$this->response = redirect(route('users.point_list'));
				return;

			default:
				// 未対応のページフレームを検出した。
				$this->includeErrorView('E-0004');
		}
	}
}
?>