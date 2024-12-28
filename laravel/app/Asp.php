<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\External\Estlier;
use App\External\SkyFlag;
use App\Device\Device;
use Illuminate\Encryption\Encrypter;

/**
 * ASP.
 */
class Asp extends Model
{
    // A8
    const A8_TYPE = 1;
    // Janet
    const JANET_TYPE = 2;
    // ValueCommerce
    const VC_TYPE = 3;
    // 楽天アフィリエイト
    const RAKUTEN_TYPE = 4;
    // TGアフィリエイト
    const TG_TYPE = 5;
    // LinkShare
    const LINK_SHARE_TYPE = 6;
    // アクセストレード
    const ACCESS_TRADE_TYPE = 7;
    // Ad Link
    const AD_LINK_TYPE = 9;
    // アフィタウン
    const AFFITOWN_TYPE = 10;
    // Smart-c
    const SMARTC_TYPE = 13;
    // アフィリエイトウォーカー
    const AFF_WALKER_TYPE = 15;
    // AFRo
    const AFRO_TYPE = 16;
    // アルテマアフィリエイト
    const UA_TYPE = 17;
    // アドファクトリー
    const AD_FACTORY_TYPE = 18;
    // セレス
    const CERES_TYPE = 19;
    // Sansan
    const SANSAN_TYPE = 20;
    // 手動成果
    const MANUAL_TYPE = 22;
    // エストリエ
    const ESTLIER_TYPE = 23;
    // TRUEアフィリエイト
    const TRUE_AFFILIATE = 27;
    // GREE Ads Reward
    const GREE_ADS_REWARD = 28;
    // AD TRACK
    const AD_TRACK = 29;
    // SKYFLAG
    const SKYFLAG = 30;
    // まいにちクイズボックス
    const EASY_GAME_BOX_QUIZ = 31;
    // かんたんゲームボックス
    const EASY_GAME_BOX_GAME = 32;
    // メダルモール
    const MEDAL_MALL_TYPE = 34;
    // 株式会社 Skyfall
    const SKYFLAG_OFFER = 35;
    // 運だめし　スロットボックス
    const EASY_GAME_BOX_SLOT = 39;
     // ふるふるサファリ
    const FRUFUL = 41;
    //GMO TECH
    const GMO_TECH = 42;
    // CA Reawrd
    const CA_REWARD = 45;
    // GREE Ads Reward オファーウォール
    const GREE_ADS_REWARD_OFFER = 46;
    // Circuit X
    const CIRCUIT_X = 47;
    // Gacha
    const GACHA_TYPE = 48;

    // Brain Exercise
    const BRAIN_EXERCIES = 50;

    // MY CHIPS
    const MY_CHIPS = 49;
    //アイブリッジアンケート
    const I_BRIDGE_ENQUETE = 52;
    
    // アフィタウン新システム
    const AFFY_TOWN_NEW = 53;

    // Farm life
    const FARM_LIFE = 51;

    // game box spotdiff
    const EASY_GAME_BOX_SPOT = 54;
    // AppDriver OW
    const APP_DRIVER_OW = 55;
    // AFB
    const AFB = 56;


    /** ColleeeユーザーID置換パラメーター */
    const COLLEEE_USERID_REPLACE = '@@@COLLEEE_USERID@@@';
    /** RID置換パラメーター */
    const COLLEEE_RID_REPLACE = '@@@COLLEEE_RID@@@';

    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'asps';

     /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * クリックURL取得.
     * @param User $user ユーザー情報
     * @param array|NULL $options オプション
     * base_url:URL,rid:RID
     * @return string|NULL クリックURL
     */
    public static function getClickUrl(int $asp_id, User $user, $options = null)
    {
        // ユーザー名
        $user_name = $user->name;

        // セレス
        if ($asp_id == self::CERES_TYPE) {
            $base_url = config('ceres.url');
            return $base_url . '?' . http_build_query(['user_id' => $user->old_id ?? $user_name]);
        }
        // Sansan
        if ($asp_id == self::SANSAN_TYPE) {
            $base_url = config('sansan.url');
            //$base_url = 'https://entry.eightbiz.net/login';
            $PROVIDER_SECRET = config('sansan.provider_secret');
            //$PROVIDER_SECRET = 'bhQLE4AZ0chNrGEPVdQalnIdJFzU2O7p';
            $PROVIDER_KEY = config('sansan.provider_key');
            //$PROVIDER_KEY = 'sample_partner';

            $nonce = config('sansan.nonce');
            //$nonce = 'samplenonce12345';
            $timestamp = sprintf('%0.7f', microtime(true));
            //$timestamp = '1383876534.0984730';

            $x_user_identifier = $user_name;
            //$x_user_identifier = 'sample_user1';

            // iframeのsrc先を生成、返却を行う
            $get_param =['method' => 'HMAC-SHA1', 'nonce' => $nonce,
                'provider_key' => $PROVIDER_KEY, 'timestamp' => $timestamp,
                'version' => '1.0', 'x_partner_identifier' => $PROVIDER_KEY, 'x_user_identifier' => $x_user_identifier];

            // HMAC-SHA1 でダイジェスト作成、それをbase64encode, さらにURLエンコード
            $signature_data = implode('&', [urlencode('GET'), urlencode($base_url),
                urlencode(http_build_query($get_param))]);
            $get_param['signature'] = base64_encode(hash_hmac('sha1', $signature_data, $PROVIDER_SECRET, true));
            return $base_url.'?' . http_build_query($get_param);
        }
        // エストリエ
        if ($asp_id == self::ESTLIER_TYPE) {
            // 拡張パラメーターが足りない場合
            if (!isset($options['ganre']) || !isset($options['enq_date'])) {
                return null;
            }
            return Estlier::getEnqueteUrl($user_name, $options['ganre'], $options['enq_date']);
        }
        // SKYFLAG
        if ($asp_id == self::SKYFLAG) {
            $user_name = bin2hex(openssl_encrypt($user_name, 'AES-256-ECB', SkyFlag::getConfig('PASSPHRASE')));
        }

        // まいにちクイズボックス
        if ($asp_id == self::EASY_GAME_BOX_QUIZ) {
            $base_url  = config('easy_game_box_quiz.URL');
            $MEDIA_ID  = config('easy_game_box_quiz.media_id');
            $MD5_KEY   = config('easy_game_box_quiz.md5_key');
            $timestamp = date('YmdHis');

            $key = strtoupper(md5(strtoupper(md5($MD5_KEY . $user::getNameById($user->id))) . $timestamp));
            return $base_url . '/quiz?user_id=' . $user::getNameById($user->id) . '&media_id=' . $MEDIA_ID . '&time=' . $timestamp . '&key=' . $key;
        }
        // かんたんゲームボックス
        if ($asp_id == self::EASY_GAME_BOX_GAME) {
            $base_url  = config('easy_game_box_game.URL');
            $MEDIA_ID  = config('easy_game_box_game.media_id');
            $MD5_KEY   = config('easy_game_box_game.md5_key');
            $timestamp = date('YmdHis');

            $key = strtoupper(md5(strtoupper(md5($MD5_KEY . $user::getNameById($user->id))) . $timestamp));
            return $base_url . '/easygame?user_id=' . $user::getNameById($user->id) . '&media_id=' . $MEDIA_ID . '&time=' . $timestamp . '&key=' . $key;
        }
        // 運だめし　スロットボックス
        if ($asp_id == self::EASY_GAME_BOX_SLOT) {
            $base_url  = config('easy_game_box_slot.URL');
            $MEDIA_ID  = config('easy_game_box_slot.media_id');
            $MD5_KEY   = config('easy_game_box_slot.md5_key');
            $timestamp = date('YmdHis');

            $key = strtoupper(md5(strtoupper(md5($MD5_KEY . $user::getNameById($user->id))) . $timestamp));
            return $base_url . '/slotbox?user_id=' . $user::getNameById($user->id) . '&media_id=' . $MEDIA_ID . '&time=' . $timestamp . '&key=' . $key;
        }

        // game box spotdiff
        if ($asp_id == self::EASY_GAME_BOX_SPOT) {
            $base_url  = config('easy_game_box_spot.URL');
            $MEDIA_ID  = config('easy_game_box_spot.media_id');
            $MD5_KEY   = config('easy_game_box_spot.md5_key');
            $timestamp = date('YmdHis');

            $key = strtoupper(md5(strtoupper(md5($MD5_KEY . $user::getNameById($user->id))) . $timestamp));
            return $base_url . '/spotdiff?user_id=' . $user::getNameById($user->id) . '&media_id=' . $MEDIA_ID . '&time=' . $timestamp . '&key=' . $key;
        }

        // FARM LIFE
        if ($asp_id == self::FARM_LIFE) {
            $base_url  = config('farm_life.URL');
            $media_id = config('farm_life.media_id');
            $syid = config('farm_life.syid');
            return $base_url . '?media_id='.$media_id.'&syid='.$syid.'&uid=' . $user::getNameById($user->id);
        }

        // ふるふるサファリ
        if ($asp_id == self::FRUFUL) {
            // デバイス判定
            $isMobile = Device::isMobile();

            if ($isMobile) {
                $base_url = config('fruful.sp_url');
                $site_id   = config('fruful.sp_site_id');
            } else {
                $base_url = config('fruful.pc_url');
                $site_id   = config('fruful.pc_site_id');
            }

            $sid  = $user::getNameById($user->id);

            return $base_url . '?sid=' . $sid . '&site_id=' . $site_id;
        }
        // メダルモール
        if ($asp_id == self::MEDAL_MALL_TYPE) {
            $base_url  = config('medal_mall.URL');
            return $base_url . '?muid=' . $user::getNameById($user->id);
        }

        // SKYFLAGオファーウォール
        if ($asp_id == self::SKYFLAG_OFFER) {
            $base_url  = config('skyflag_offer.URL');
            $system = '';

            $device_id = Device::getDeviceId();
            if ($device_id == 2) {
                $system = config('skyflag_offer.key_ios');
            } else if($device_id == 3) {
                $system = config('skyflag_offer.key_android');
            }
            $key = bin2hex(openssl_encrypt($user::getNameById($user->id), 'AES-256-ECB', SkyFlag::getConfig('PASSPHRASE')));
            return $base_url .'?_owp=' .$system. '&suid=' . $key;
        }

        // Gmo tech
        if ($asp_id == self::GMO_TECH) {
            $base_url  = config('gmo_tech.URL');
            return $base_url . '?u=' . $user::getNameById($user->id);
        }

        // GREE Ads Reward オファーウォール
        if ($asp_id == self::GREE_ADS_REWARD_OFFER) {
            $base_url  = config('gree_ads_rewards_offer.URL');
            $site_id = config('gree_ads_rewards_offer.site_id');
            $media_id = config('gree_ads_rewards_offer.media_id');
            $site_key = config('gree_ads_rewards_offer.site_key');
            $user_id = $user::getNameById($user->id);
            $digest = hash('sha256', "$user_id;$media_id;$site_key");
            return "$base_url/3.0.{$site_id}i?identifier={$user_id}&media_id={$media_id}&digest={$digest}";
        }


        // Gacha
        if ($asp_id == self::GACHA_TYPE) {
            $base_url  = config('gacha.URL');
            return $base_url . '&suid=' . $user::getNameById($user->id);
        }

        // Brain Exercise
        if ($asp_id == self::BRAIN_EXERCIES) {
            $base_url  = config('brain_exercise.URL');
            $media_id = config('brain_exercise.media_id');
            $syid = config('brain_exercise.syid');
            return $base_url . '?uid=' . $user::getNameById($user->id). '&media_id=' . $media_id . '&syid=' . $syid;
        }
        
        if ($asp_id == self::MY_CHIPS) {
            $cid =  config('mychips.cid');
            $pid =  config('mychips.pid');
            $gender = $user->sex == 1 ? 'm' : 'w';
            $birthday = strtotime($user->birthday);
            $age = date('Y') - date('Y', $birthday);
            $base_url  = config('mychips.URL').'?cid='.$cid.'&pid='.$pid.'&user_id='.$user->getNameById($user->id).'&gender='.$gender.'&age='.$age;
            // $base_url  = config('mychips.URL');
            return $base_url;

        }
        // APPDRIVER OW
        if ($asp_id == self::APP_DRIVER_OW) {
            $siteid =  config('app_driver_ow.siteid');
            $mediaid =  config('app_driver_ow.mediaid');
            $user_id = $user::getNameById($user->id);
            $site_key =  config('app_driver_ow.sitekey');
            $appfrom =  config('app_driver_ow.appfrom');
            $digest = hash('sha256', "$appfrom;$user_id;$mediaid;$site_key");
            $base_url  = config('app_driver_ow.URL'). $siteid .'?identifier='.$user_id.'&media_id='.$mediaid.'&appfrom='.$appfrom.'&digest='.$digest;
            return $base_url;
        }

        $base_url = $options['base_url'] ?? null;
        $rid = $options['rid'] ?? '';

        $colleee_userid_pattern = "/".preg_quote(self::COLLEEE_USERID_REPLACE)."/i";
        $colleee_rid_pattern = "/".preg_quote(self::COLLEEE_RID_REPLACE)."/i";

        // ColleeeユーザーIDパラメーターが存在しない場合
        if (!preg_match($colleee_userid_pattern, $base_url)) {
            return null;
        }

        // ColleeeユーザーIDを置換
        $url = preg_replace($colleee_userid_pattern, $user_name, $base_url);
        // RIDを置換
        $url = preg_match($colleee_rid_pattern, $url) ? preg_replace($colleee_rid_pattern, $rid, $url) : $url;

        // CA Reward
        if ($asp_id == self::CA_REWARD) {
            $mId = config('ca_reward.M_ID');
            $apiKey = config('ca_reward.API_KEY');
            $hash = hash('sha512', $apiKey. $user_name);
            $url = $url . '&m_id=' . $mId. '&enc_user_id=' . $hash;
        }

        return $url;
    }

    public function scopeEnable($query)
    {
        return $query->whereNull($this->table.'.deleted_at');
    }
}
