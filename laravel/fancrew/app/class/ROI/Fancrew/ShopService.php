<?php

use GuzzleHttp\Client;
use \App\Search\FancrewCondition;

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
		//$this->logger = new ROI_Logger($this);
                $this->logger = logger();
	}

	/**
	 * 店舗ID から店舗情報を取得する。
	 *
	 * @param long $shopId 店舗ID
	 */
	public function getShop($shopId) {
            /*
		// 設定を取得
		$config = \ROI_Fancrew_Config::get();
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
            */
            
            // HTTP取得実行
            $xml = $this->http('shops', ['shop_ids' => $shopId]);

            /*
            ob_start();
        ?>
<?xml version="1.0" encoding="utf-8"?>
<Result>
    <Header>
        <Status code="0" trackingCode="1303711317410.0c2c.1">エラーメッセージ</Status>
    </Header>
    <Data>
      <Shops>
        <Shop id="15932"
          viewMode="2"
          categoryTypeId="2"
          name="血液サラサラ＆ダイエットにも効果抜群！スーパーDHA-EPA 500"
          nameKana="けつえきさらさらあんどだいえっとにもこうかばつぐんすーぱーでぃーえいちえーいーぴーえーごひゃく"
          catchPhrase="ダイエットをしたい方・肉食中心の食生活の方・月経でお悩みの方"
          description="返品・交換ＯＫ！
  商品の開封後でもＯＫ！
  
  ●こんな方にオススメです
  * ダイエットをしたい！健康的に痩せたい方。
  * コレストロール値が気になる方。
  *肉食中心の食生活の方。
  *月経でお悩みの方。
  腸で溶ける特殊なソフトカプセルを使用した高性能な「DHA-EPA」。
  医療向けサプリメント！全米NO1の信頼と安全をお約束。
  胃で溶けずに腸で溶ける特殊なエンテリックコーティングを採用。ある研究ではDHA-EPAの吸収が約3倍にもなるという報告があります。また含有量は極めて高くEPA=330mgDHA=170mg。
  たったの1日1粒で厚生労働省が提示している1日摂取量をカバーしております。
  バイタルケアーズのスーパーDHA-EPA 500は、新鮮な北海イワシを、化学溶剤などを一切使用しない、※超臨界抽出法により成分抽出しているので、安心・安全です。"
          averageBudget="2,625円"
          releaseTimestamp="2011-04-06 11:48:58"
          pcUrl="http://vital-shop.jp/site/lp/dha/"
          mobileUrl=""
          logoImageUrl="http://classic.fancrew.jp/api/3.0/image/shop/0001/5309/31163.jpg"
          topImageUrl="http://classic.fancrew.jp/api/3.0/image/shop/0001/5309/31164.jpg"
          topImageUrlGray="http://classic.fancrew.jp/api/3.0/image/shop/0001/5309/31164.gray.jpg"
          numOfReviews="5"
          brandId=""
          shippingFee="700円※離島、一部地域に関しましては追加料金がかかる事が御座います。">
          <Genre1 id="140" name="健康/サプリメント"></Genre1>
          <Genre2 id="149" name="健康系サプリ"></Genre2>
          <Monitor id="258986"
                  description="覆面調査（ミステリーショッパー）という事が、お店の方に知られないように飲食し、料理やサービスについて調査するモニターです。"
                  deliverable="アンケート提出・購入確認期限"
                  enqueteSubmitExpires="20"
                  numOfDispatch="50"
                  receiptType="通販入力"
                  gray="0"
                  quickAppFlg="1"
                  win100="0"
                  try="0">
　　　　　　<Rate type="割合" value="30" limit="12000"></Rate>
　　　　　　<RewardInfo rewardPhrase1="謝礼" rewardPhrase2="購入金額の" rewardPhrase3="30％" rewardPhrase4="上限 12,000円">謝礼：購入金額の30％（上限 12,000円）</RewardInfo>
　　　　　　<OriginalFeeRate type="割合" value="10" limit="12000"></OriginalFeeRate>
            <Course title="品質の違いを実感してください。">
              <CourseDetail
                  name="含有量Ｎｏ．１の高濃度ＤＨＡ-ＥＰＡ"
                  title="超臨界抽出法により天然成分１００％を実現！"
                  content="あなたは週に魚と肉、どちらを食べる事が多いですか？
  
  現在日本では約８割以上の方が肉食中心の食生活と言われており、厚生労働省が定めた一日辺りのDHA-EPA摂取量の1000mg以上を多くの人が摂取出来ていません。
  そこでバイタルケアーズでは足りない栄養素を補う為に天然成分100%の北海イワシを贅沢に使った、『SUPER DHA-EPA』を強く推奨致します！"
                  imageUrl="http://classic.fancrew.jp/api/3.0/image/course/0000/0367/31707.135.jpg"></CourseDetail>
                  ...
            </Course>
            <Notices>
              <Notice>謝礼は現金に交換可能なRポイントでお支払いしています。</Notice>
              <Notice>現金等に交換時に手数料がかかりますので予めご了承ください。</Notice>
              <Notice>※応募前に購入確認方法(提出物)をご確認ください。</Notice>
              <Notice>※応募には会員登録（無料）が必要になります。</Notice>
            </Notices>
          </Monitor>
          <Reviews size="5" offset="0" num="10">
            <Review id="164554" createTimestamp="2011-08-10 11:51:39.739"
                content="デザインの良いボトルに入っていて効果が期待できそうです。サプリメントも程よい大きさで飲みやすいです。" gender="男性" age="36"></Review>
            ...
          </Reviews>
        </Shop>
      </Shops>
    </Data>
</Result>
        <?php
            $result = ob_get_contents();
            ob_end_clean();
            $xml = simplexml_load_string($result);
            */
		return $xml->Data->Shops->Shop ?? null;
	}

	/**
	 * モニターID から店舗情報を取得する。
	 *
	 * @param long $monitorId モニターID
	 */
	public function getShopByMonitorId($monitorId) {
            /*
		// 設定を取得
		$config = \ROI_Fancrew_Config::get();
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
            */
            
            // HTTP取得実行
            $xml = $this->http('shops', ['monitor_ids' => $monitorId]);
        
		return $xml->Data->Shops->Shop ?? null;
	}

    private function http(string $path, array $params) {
        // 設定を取得
        $config = \ROI_Fancrew_Config::get();
        $apiBaseURL = $config->apiBaseURL;
        $apiKey = $config->apiKey;
        
        $pParam = array_merge(['key' => $apiKey], $params);
        
        $client = new Client();
        
        $query = http_build_query($pParam);
        
        $options = [
            'http_errors' => false,
            'timeout' => 10,
            'query' => $query];
        // プロキシ
        if (!$config->useProxy) {
            $options['proxy'] = '';
        }
        // SSL証明書回避
        if (!$config->sslVerify) {
            $options['verify'] = false;
        }
        
        try {
            // リクエスト実行
            $response = $client->request('GET', $apiBaseURL.$path, $options);
        
            // HTTPステータス確認
            $status = $response->getStatusCode();
            if ($status != 200) {
                $this->logger->error('HTTPエラー:'.$status);
                return null;
            }
            $body = $response->getBody();
            if (!isset($body) || $body == '') {
                $this->logger->error('取得データ不正エラー');
                return null;
            }
            
            $xml = simplexml_load_string($body);

            // XMLパース失敗
            if (!is_object($xml)) {
                $this->logger->error('XMLパースエラー');
                return null;
            }
            
            $xmlStatus = $xml->Header->Status;
            if ($xmlStatus['code'] != 0) {
                $this->logger->error('エラー:'.$xmlStatus->asXML());
                return null;
            }
            
            return $xml;
        } catch (\Exception $e) {
            $this->logger->error('Exception:'.$e->getMessage());
            return null;
        }
    }    

    public function searchShop(int $device, $user_id, FancrewCondition $condition) {
        $limit = $condition->getParam('limit');
        $offset = ($condition->getParam('page') - 1) * $limit;
        $sort = $condition->getParam('sort');
        
        $params = ['categorytype_id' => 1, 'device' => $device,
            'hidesoldout' => 1, 'num' => $limit, 'offset' => $offset, 'getshop' => 1,
            'sort' => $sort];
        $freeword = $condition->getParam('freeword');
        if (isset($freeword)) {
            $params['freeword'] = $freeword;
        }
        $prefecture_id = $condition->getParam('prefecture_id');
        if (isset($prefecture_id) && $prefecture_id > 0) {
            $params['prefecture_id'] = $prefecture_id;
        }
        $area_id = $condition->getParam('area_id');
        if (isset($area_id) && $area_id > 0) {
            $params['area_id'] = $area_id;
        }
        $category_id = $condition->getParam('category_id');
        if (isset($category_id) && $category_id > 0) {
            $params['category_id'] = $category_id;
        }
        $genre_id = $condition->getParam('genre_id');
        if (isset($genre_id) && $genre_id > 0) {
            $params['genre_id'] = $genre_id;
        }
        if (isset($user_id)) {
            $params['user_id'] = $user_id;
        }
        
        // HTTP取得実行
        $xml = $this->http('search', $params);

        /*
        ob_start();
        ?>
<?xml version="1.0" encoding="UTF-8"?>
<Result>
  <Header>
    <Status code="0" trackingCode="1319188771358.010d.1"></Status>
  </Header>
  <Data>
    <Shops size="2761" offset="0" num="10">
      <Shop id="14619"
	viewMode="2"
	categoryType="リアル"
	name="松坂牛･特選和牛焼肉　やまと　船橋本店"
	nameKana="まつざかぎゅうとくせんわぎょうやきにくやまとふなばしてん"
	catchPhrase="ワンランク上の上質なお肉を取り揃えた老舗・やまと船橋店！"
	description="焼肉の老舗「焼肉やまと」が新装開店！

個室が10部屋に！

船橋で35年。最高級A5等級の松阪牛、特選牛だけにこだわり続け、目利きの職人

が厳選したお肉を熟成させ旨みを引き出してご提 供します。

どうぞ大切なお食事にやまとこだわりの松阪牛＆特選牛を心ゆくまでご堪能下さい。"
	averageBudget="4,000円(通常平均)5,000円(宴会平均)1,600円(ランチ平均)"
	releaseTimestamp="2010-12-29 22:23:39"
	pcUrl="http://r.gnavi.co.jp/g108400/"
	mobileUrl=""
	logoImageUrl="http://classic.fancrew.jp/api/3.0/image/shop/0001/4619/30613.jpg"
	topImageUrl="http://classic.fancrew.jp/api/3.0/image/shop/0001/4619/30622.jpg"
	topImageUrlGray="http://classic.fancrew.jp/api/3.0/image/shop/0001/4619/30622.gray.jpg"
	postalCode="273-0865"
	address="千葉県船橋市夏見1-8-28 市場通り"
	phoneNumber="047-422-4129"
	access="ＪＲ船橋駅 北口 徒歩10分、東武野田線船橋駅 徒歩10分、東葉高速線東海神駅 徒歩10分"
	businessHours="11:00～23:00"
	fixedHoliday="年中無休（元旦を除く）"
	latitude="35.706763"
	longitude="139.987608">

        <Category id="1" name="グルメ"></Category>
        <Genre id="2" name="焼肉・各国料理"></Genre>
        <Prefecture id="12" name="千葉県"></Prefecture>
        <Area id="55" name="市川・本八幡・船橋・津田沼"></Area>
        <Monitor
		id="258567"
		deliverable="アンケート・レシート提出期限" 
		enqueteSubmitExpires="7"
		numOfDispatch="30"
		receiptType="必要"
		gray="0"
		quickAppFlg="0"
		win100="0">
          <Rate type="割合" value="50" limit="5000"/>
          <OriginalFeeRate type="割合" value="30" limit="2000"/>
          <Notices>
            <Notice>謝礼は現金に交換可能なRポイントでお支払いしています。</Notice>
            <Notice>現金等に交換時に手数料がかかりますので予めご了承ください。</Notice>
            <Notice>※応募前にレシート(その他証明書)画像作成方法をご確認ください。</Notice>
            <Notice>※応募には会員登録（無料）が必要になります。</Notice>
          </Notices>
        </Monitor>
      </Shop>
      ...
  </Shops>
  </Data>
</Result>
        <?php
        $result = ob_get_contents();
        ob_end_clean();
        $xml = simplexml_load_string($result);
        */
        
        return $xml->Data->Shops ?? null;
    }
    
    /**
     * 都道府県エリア情報を取得.
     */
    public function getPrefectures() {
        // HTTP取得実行
        $xml = $this->http('prefectures', ['getarea' => 1]);
        
        return $xml->Data->Prefectures ?? null;
    }
    
    /**
     * カテゴリ,ジャンル情報を取得.
     */
    public function getGenresReal() {
        // HTTP取得実行
        $xml = $this->http('genres.real', []);
        
        return $xml->Data->Categories ?? null;
    }
    
    /**
     * APIユーザーIDを取得する.
     * @param array $params 引数
     * @return integer|NULL APIユーザーID
     */
    public function createApiUserId(array $params) {
        // HTTP取得実行
        $xml = $this->http('user.add', $params);
        
        /*
                ob_start();
        ?>
<?xml version="1.0" encoding="utf-8"?>
<Result>
  <Header>
    <Status code="0" trackingCode="1320646311575.0c2c.1"></Status>
  </Header>
  <Data>
    <User id="576886" gender="女性" birthday="1974-10-06" age="39"></User>
  </Data>
</Result>
        <?php
        $result = ob_get_contents();
        ob_end_clean();
        $xml = simplexml_load_string($result);
        */
        
        return $xml->Data->User['id'] ?? null;
    }
}
?>