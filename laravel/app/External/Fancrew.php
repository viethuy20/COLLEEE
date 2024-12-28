<?php

namespace App\External;

use GuzzleHttp\Client;

use App\Search\FancrewCondition;


// プロジェクトの基本ディレクトリ
if (!defined('BASE_PATH')) {
    define('BASE_PATH', base_path('fancrew/'));
}
// アプリケーションの基本ディレクトリ
if (!defined('APP_BASE_PATH')) {
    define('APP_BASE_PATH', BASE_PATH.'app/');
}
if (!defined('APP_CLASS_PATH')) {
    define('APP_CLASS_PATH', APP_BASE_PATH.'class/');
}
if (!defined('CLASS_PATH')) {
    define('CLASS_PATH', BASE_PATH.'class/');
}
require_once APP_CLASS_PATH . 'ROI/Fancrew/Config.php';
//require_once base_path('fancrew/app/class/ROI/Fancrew/EventMessageReceiver/Config.php');
require_once APP_CLASS_PATH . 'ROI/Fancrew/SiteCooperation/SCodeEncoder.php';

/**
 * Description of Fancrew
 *
 * @author t_moriizumi
 */
class Fancrew {
    private static $config = null;
    private $path = null;
    private $params = null;
    private $httpStatus = null;
    private $body = null;
    private $response = null;
    
    /**
     * 設定値取得.
     */
    private static function getConfig() {
        if (isset(self::$config)) {
            return self::$config;
        }
        
        require_once base_path('fancrew/app/config/config.php');
        
        self::$config = $fancrewConfig;
        
        return self::$config;
    }
    
    public static function getRemoteControllerURL(bool $sp, int $shop_id, int $user_id) {
        // 設定を取得
        $config = self::getConfig();

        // ROI側コントローラのベースURLを取得する。
        $remoteControllerURL = $sp ? $config->remoteSmartphoneControllerBaseURL : $config->remotePcControllerBaseURL;

        $params = ['_pf' => 'shopF', '_pf.shop_id' => $shop_id, '_p' => 'shopEntry', 'id' => $shop_id];
        
        $secretKey = $config->secretKey;
        $apiId = $config->apiId;
        $apiKey = $config->apiKey;
        $cryptoType = $config->cryptoType;

        $scodeEncoder = new \ROI_Fancrew_SiteCooperation_SCodeEncoder($secretKey, $apiId, $apiKey, $cryptoType);

        // sCode を取得する。
        $sCode = $scodeEncoder->createSCode($user_id);
        
        $params['sCode'] = $sCode;

        return $remoteControllerURL.'?'. http_build_query($params);
    }
    
    /**
     * 実行.
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function execute() : bool {
        return true;
        
        $client = new Client();
        
        // 設定を取得
        $config = self::getConfig();
        
        $params = ['key' => $config->apiKey];
        if (isset($this->params)) {
            $params = array_merge($params, $this->params);
        }
        
        $query = http_build_query($params);
        
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
        
        // リクエスト実行
        $response = $client->request('GET', $config->apiBaseURL.$this->path, $options);
        
        try {
            // HTTPステータス確認
            $this->httpStatus = $response->getStatusCode();
            if ($this->httpStatus != 200) {
                return false;
            }
            $this->body = $response->getBody();
            if (isset($this->body) && $this->body != '') {
                $this->response = simplexml_load_string($this->body);
            }
            
            // XMLパース失敗
            if (!is_object($this->response)) {
                return false;
            }
            
            // ステータス確認
            if ($this->response->Header->Status != 0) {
                return false;
            }
        } catch (\Exception $e) {
            \Log::info('Fancrew:'.$e->getMessage());
            return false;
        }
        return true;
    }
    
    /**
     * 都道府県エリア情報を取得.
     * @return Fancrew Fancrewオブジェクト
     */
    public static function getPrefectureList() : Fancrew {
        $fancrew = new Fancrew();
        $fancrew->path = 'prefectures';
        $fancrew->params = ['getarea' => 1];
        return $fancrew;
    }
    
    /**
     * ジャンル情報を取得.
     * @return Fancrew Fancrewオブジェクト
     */
    public static function getGenreList() : Fancrew {
        $fancrew = new Fancrew();
        $fancrew->path = 'genres.real';
        return $fancrew;
    }
    
    /**
     * 店舗情報を検索.
     * @param int $device 端末
     * @param int|NULL $user_id ユーザーID
     * @param FancrewCondition $condition 検索条件
     * @return Fancrew Fancrewオブジェクト
     */
    public static function searchShop(int $device, $user_id, FancrewCondition $condition) : Fancrew {
        $fancrew = new Fancrew();
        $fancrew->path = 'search';
        
        $limit = $condition->getParam('limit');
        $offset = ($condition->getParam('page') - 1) * $limit;
        $sort = $condition->getParam('sort');
        $fancrew->params = ['categorytype_id' => 1, 'device' => $device, 'hidesoldout' => 1,
            'num' => $limit, 'offset' => $offset, 'getshop' => 1, 'sort' => $sort];
        if (isset($user_id)) {
            $fancrew->params['user_id'] = $user_id;
        }
        $freeword = $condition->getParam('freeword');
        if (isset($freeword)) {
            $fancrew->params['freeword'] = $freeword;
        }
        $prefecture_id = $condition->getParam('prefecture_id');
        if (isset($prefecture_id) && $prefecture_id > 0) {
            $fancrew->params['prefecture_id'] = $prefecture_id;
        }
        $area_id = $condition->getParam('area_id');
        if (isset($area_id) && $area_id > 0) {
            $fancrew->params['area_id'] = $prefecture_id;
        }
        
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
        $fancrew->httpStatus = 200;
        $fancrew->body = $result;
        $fancrew->response = simplexml_load_string($fancrew->body);
        
        return $fancrew;
    }
    
    /**
     * 店舗情報を取得.
     * @param string $shop_id 店舗ID
     * @param int|NULL $user_id ユーザーID
     * @return Fancrew Fancrewオブジェクト
     */
    public static function getShop(string $shop_id, $user_id) : Fancrew {
        $fancrew = new Fancrew();
        $fancrew->path = 'shops';
        
        $fancrew->params['shop_ids'] = $shop_id;
        
        if (isset($user_id)) {
            $fancrew->params['user_id'] = $user_id;
        }
        
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
        $fancrew->httpStatus = 200;
        $fancrew->body = $result;
        $fancrew->response = simplexml_load_string($fancrew->body);
        
        return $fancrew;
    }
    
    /**
     * HTTPステータス取得.
     * @return string 結果
     */
    public function getHttpStatus() {
        return $this->httpStatus;
    }
    
    /**
     * ステータス取得.
     * @return string 結果
     */
    public function getStatus() {
        return $this->response->Header->Status;
    }
    
    /**
     * レスポンス取得.
     */
    public function getResponse() {
        return $this->response;
    }
}
