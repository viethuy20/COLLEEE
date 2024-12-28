<?php
require_once CLASS_PATH . 'ROI/Fancrew/SiteDeviceType.php';
//require_once CLASS_PATH . 'ROI/Fancrew/SiteCooperation/SCodeEncoder.php';
require_once APP_CLASS_PATH . 'ROI/Fancrew/SiteCooperation/SCodeEncoder.php';

// 子クラスで利用するクラス
//require_once CLASS_PATH . 'ROI/Fancrew/ShopService.php';
require_once APP_CLASS_PATH . 'ROI/Fancrew/ShopService.php';

/**
 * ファンくるAPI サイト連携の OEM 側コントローラ (PC, 携帯共通部分)
 *
 * @author Kenkichi Mahara
 *
 */
abstract class ROI_Fancrew_SiteCooperation_AbstractController
{
	/**
	 * サイトの端末種別 (ROI_Fancrew_SiteDeviceType)
	 *
	 * 1 - ROI_Fancrew_SiteDeviceType::PC
	 * 2 - ROI_Fancrew_SiteDeviceType::mobile
	 * 3 - ROI_Fancrew_SiteDeviceType::smartphone
	 */
	public $siteDeviceType;

	/** ROI 側コントローラのベース URL */
	private $remoteControllerBaseURL;

	/** view ディレクトリのパス */
	private $viewPath;
        
        /** 出力結果 */
        protected $response = null;

	/**
	 * インスタンスを生成する。
	 *
	 * @param integer $siteDeviceType サイトの端末種別 (ROI_Fancrew_SiteDeviceType)
	 */
	public function __construct($siteDeviceType) {
		$this->siteDeviceType = $siteDeviceType;

		// サイト連携設定
		$config = ROI_Fancrew_Config::get();

		// サイト種別に応じた初期化処理を行う。
		switch ($this->siteDeviceType) {
			case ROI_Fancrew_SiteDeviceType::pc:
				// ROI 側コントローラのベース URL
				$this->remoteControllerBaseURL = $config->remotePcControllerBaseURL;

				//  view ディレクトリのパス
				$this->viewPath = APP_VIEW_PATH . 'pc/';
				break;

			case ROI_Fancrew_SiteDeviceType::mobile:
				// ROI 側コントローラのベース URL
				$this->remoteControllerBaseURL = $config->remoteMobileControllerBaseURL;

				//  view ディレクトリのパス
				$this->viewPath = APP_VIEW_PATH . 'mobile/';
				break;

			case ROI_Fancrew_SiteDeviceType::smartphone:
				// ROI 側コントローラのベース URL
				$this->remoteControllerBaseURL = $config->remoteSmartphoneControllerBaseURL;

				//  view ディレクトリのパス
				$this->viewPath = APP_VIEW_PATH . 'smartphone/';
				break;

			default:
				throw new Exception("未対応の siteDeviceType: " . $this->siteDeviceType);
		}
	}

	/** このコントローラを実行する。 */
	public abstract function exec();

        public function getResponse() {
            if (isset($this->response)) {
                return $this->response;
            }
            // レスポンスがなかった場合
            abort(404, 'Not Found.');
        }
        
	/**
	 * view ファイルを include する。
	 *
	 * @param string $filename ファイル名
	 */
	protected function includeView($filename, $viewContext) {
            $name = substr($filename, -4) == '.php' ? substr($filename, 0, -4) : $filename;
            $this->response = view('fancrew.'.$name, $viewContext)->render();

                //include $this->viewPath . $filename;
	}

	/**
	 * システムエラー画面を表示する。
	 *
	 * _pf に不正な値が入らなかった、などプログラム上、または連携上のミスの可能性が高い。
	 *
	 * @param unknown_type $errorCode
	 */
	protected function includeErrorView($errorCode) {
		$viewContext = array(
			'errorMessage' => "エラーが発生しました(" . $errorCode . ")。",
		);

		$this->includeView('error.php', $viewContext);
		return;
	}

	/**
	 * エラー画面を表示する。
	 *
	 * 指定された ID の店舗が表示できない (非表示になった、今月のモニター応募がない) など、
	 * ユーザ操作やアクセス時刻などに起因するエラー画面。
	 *
	 * @param unknown_type $errorCode
	 */
	protected function includeUserErrorView($errorMessage) {
		$viewContext = array(
			'errorMessage' => $errorMessage,
		);

		$this->includeView('error.php', $viewContext);
		return;
	}

	/**
	 * ROI から渡されたパラメータをもとに、ROI 側コントローラを呼び出す URL を作成する。
	 *
	 * @param array $params URL に付加するパラメータ。null なら $_GET を利用する。
	 */
	protected function createRemoteControllerURL($params = null) {
		if ($params == null) {
			//$params = $_GET;
                        $params = request()->all();
		}
                
		// サイト連携設定
		$config = ROI_Fancrew_Config::get();

		// ROI側コントローラのベースURLを取得する。
		$remoteControllerURL = $this->remoteControllerBaseURL;

                /*
		// このコントローラの URL に渡されたパラメータから、ROI に渡すパラメータを構築する。
		$isFirst = true;
		foreach ($params as $key => $val) {
			if ($isFirst) {
				$remoteControllerURL .= "?";
				$isFirst = false;
			} else {
				$remoteControllerURL .= "&";
			}

			$remoteControllerURL .= $key;
			$remoteControllerURL .= '=';
			$remoteControllerURL .= urlencode(mb_convert_encoding($val, "UTF-8"));
		}
                */

		$secretKey = $config->secretKey;
		$apiId = $config->apiId;
		$apiKey = $config->apiKey;
		$cryptoType = $config->cryptoType;
                                
		// 現在サービスにログイン中ユーザの APIユーザID を取得する。
		$apiUserId = $this->getApiUserId();

		if (!isset($apiUserId)) {
			return null;
		}
                                
		$scodeEncoder = new ROI_Fancrew_SiteCooperation_SCodeEncoder($secretKey, $apiId, $apiKey, $cryptoType);

		// sCode を取得する。
		$sCode = $scodeEncoder->createSCode($apiUserId);

                //$params['sCode'] = $sCode;
		//$remoteControllerURL .= "&sCode=";
		//$remoteControllerURL .= $sCode;
                
                //$remoteControllerURL .= "?". http_build_query($params);
                $remoteControllerURL .= "?". http_build_query($params).'&sCode='.$sCode;

		return $remoteControllerURL;
	}
        
	/**
	 * 現在 OEM 様サイトにログイン中ユーザの APIユーザID を取得する。
	 *
	 * @return long APIユーザID。ログインしていなければ null を返す。
	 */
	protected function getApiUserId() {
		// TODO 現在ログイン中のユーザの APIユーザID を取得ください。
		//return 575301;
            // ログインしていない場合
            if (!\Auth::check()) {
                return null;
			}
			
			$user = \Auth::user();

            // アカウント取得
            return $user->fancrew_account_number;
	}

	/**
	 * _GET から _pf.shop_id (店舗ID) を取得する。
	 *
	 * @return long 店舗ID。取得できないときは null を返す。
	 */
	protected function getShopId() {
		// 店舗IDを取得する。$_GET に格納されるパラメータ名は . が _ に置換される点に注意。
		//$shopId = isset($_GET['_pf_shop_id']) ? intval($_GET['_pf_shop_id']) : null;

            $shopId = request()->has('_pf_shop_id') ? intval(request()->input('_pf_shop_id')) : null;
            
		if ($shopId == 0) {
			$shopId = null;
		}

		return $shopId;
	}


	/**
	 * _GET から _pf.monitor_id (店舗ID) を取得する。
	 *
	 * @return long モニターID。取得できないときは null を返す。
	 */
	protected function getMonitorId() {
		// 店舗IDを取得する。$_GET に格納されるパラメータ名は . が _ に置換される点に注意。
		//$monitorId = isset($_GET['_pf_monitor_id']) ? intval($_GET['_pf_monitor_id']) : null;

            $monitorId = request()->has('_pf_monitor_id') ? intval(request()->input('_pf_monitor_id')) : null;
            
		if ($monitorId == 0) {
			$monitorId = null;
		}

		return $monitorId;
	}
    
    protected function actionSearchShop() {
        
        $condition = new \App\Search\FancrewCondition();

        // ソート
        if (request()->has('sort')) {
            // ソート条件登録追加
            $condition->setParams(['sort' => request()->input('sort')]);
        }
        // ページ
        if (request()->has('page')) {
            // ページ条件登録追加
            $condition->setParams(['page' => request()->input('page')]);
        }
        
        // フリーワード検索
        if (request()->has('freeword')) {
            // フリーワード検索条件登録追加
            $condition->setParams(['freeword' => request()->input('freeword')]);
        }
        // カテゴリ検索
        if (request()->has('category_id')) {
            // カテゴリ検索条件登録追加
            $condition->setParams(['category_id' => request()->input('category_id')]);
        }
        // 都道府県検索
        if (request()->has('prefecture_id')) {
            // 都道府県検索条件登録追加
            $condition->setParams(['prefecture_id' => request()->input('prefecture_id')]);
        }
        // エリア検索
        if (request()->has('area_id')) {
            // エリア検索条件登録追加
            $condition->setParams(['area_id' => request()->input('area_id')]);
        }
        
        $device = \Agent::isPhone() ? 3 : 1;
        
        // ユーザーID取得
        $user_id = $this->getApiUserId();
        
        $fancrew_xml = ROI_Fancrew_ShopService::get()->searchShop($device, $user_id, $condition);
        
        $shop_list = collect();
        if ($fancrew_xml) {
            $total = $fancrew_xml->attributes()->size;
            $shop_list_xml = $fancrew_xml->Shop;
            foreach ($shop_list_xml as $shop_xml) {
                $shop_list->push($shop_xml);
            }
        } else {
            $total = 0;
        }
        // ページ
        $page = $condition->getParam('page');
        // 件数
        $limit = $condition->getParam('limit');

        // ページネーション作成
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($shop_list, $total, $limit, $page);
        
        // 
        $this->includeView('search', ['paginator' => $paginator, 'condition' => $condition]);
    }
}
?>