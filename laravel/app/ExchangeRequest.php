<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use WrapPhp;

/**
 * 交換申し込み.
 */
class ExchangeRequest extends Model
{
    use DBTrait;

    /** 金融機関. */
    const BANK_TYPE = 1;
    /** Edyギフトコード. */
    const EDY_GIFT_TYPE = 9;
    /** アマゾンギフトコード. */
    const AMAZON_GIFT_TYPE = 10;
    /** iTunesギフトコード. */
    const ITUNES_GIFT_TYPE = 11;
    /** PEXポイントギフトコード. */
    const PEX_GIFT_TYPE = 5;
    /** NANACOギフトコード. */
    const NANACO_GIFT_TYPE = 12;
    /** .moneyポイント. */
    const DOT_MONEY_POINT_TYPE = 8;
    /** GooglePlayギフトコード. */
    const GOOGLE_PLAY_GIFT_TYPE = 13;
    /** WAONギフトコード. */
    const WAON_GIFT_TYPE = 14;
    /** dポイントギフトコード */
    const D_POINT_TYPE = 15;
    /** LINEPAYギフトコード */
    const LINE_PAY_TYPE = 16;
    /** Pontaポイントギフトコード. */
    const PONTA_GIFT_TYPE = 17;
    /** プレイステーション ストアチケット. */
    const PSSTICKET_GIFT_TYPE = 18;
    /** PayPay */
    const PAYPAY_TYPE = 19;




    /** Kdol */
    const KDOL_TYPE = 22;

    /** デジタルギフト PayPal*/
    const DIGITAL_GIFT_PAYPAL_TYPE = 20;

    /** デジタルギフト JalMile*/
    const DIGITAL_GIFT_JALMILE_TYPE = 21;


    /** 正常. */
    const SUCCESS_STATUS = 0;
    /** 組み戻し. */
    const ROLLBACK_STATUS = 1;
    /** 申込中. */
    const WAITING_STATUS = 2;
    /** エラー. */
    const ERROR_STATUS = 3;
    /** 停止. */
    const STOP_STATUS = 4;
    /** 交換申請中 */
    const EXCHANGE_WAITING_STATUS = 5;

    /** PayPay交換申請中 */
    const PAYPAY_WAITING_STATUS = 5;
    /** PayPayリトライ対象 */
    const PAYPAY_RETRY_STATUS = 6;

    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'exchange_requests';
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'use_ticket' => 'boolean',
        'scheduled_at' => 'datetime',
        'requested_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['scheduled_at', 'requested_at', 'confirmed_at'];



    private static $VOYAGE_GIFT_CODE_LIST = [
        self::PEX_GIFT_TYPE,
    ];

    private static $NTT_GIFT_CODE_LIST = [
        self::AMAZON_GIFT_TYPE,
        self::ITUNES_GIFT_TYPE,
        self::EDY_GIFT_TYPE,
        self::NANACO_GIFT_TYPE,
        self::GOOGLE_PLAY_GIFT_TYPE,
        self::WAON_GIFT_TYPE,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * ユーザーポイントを取得.
     * @return UserPoint ユーザーポイント
     */
    public function getUserPointAttribute() : UserPoint
    {
        $config = $this->getInnerConfig();
        return UserPoint::where('type', '=', $config['type'])
            ->where('parent_id', '=', $this->id)
            ->first();
    }

    /**
     * 銀行口座情報を取得.
     * @return BankAccount|NULL 銀行口座情報
     */
    public function getBankAccountAttribute()
    {
        // 金融機関振込申し込み以外の場合
        if ($this->type != self::BANK_TYPE) {
            return null;
        }
        return BankAccount::find($this->account_id);
    }

    private function getConfig()
    {
        return config('exchange.point.'.$this->type);
    }

    private function getInnerConfig()
    {
        $config = $this->getConfig();
        $key = $config['config'];
        if (!isset($key)) {
            return null;
        }
        return config($key);
    }

    /**
     * 申し込み番号からID取得.
     * @param string $number 申し込み番号
     * @return int ID
     */
    public static function getIdByNumber(string $number) : int
    {
        return intval(substr($number, 3));
    }

    /**
     * 申し込み番号取得.
     * @return string 申し込み番号
     */
    public function getNumberAttribute() : string
    {
        switch (config('app.env')) {
            case 'local':
                $p = 'L';
                break;
            case 'development':
                $p = 'D';
                break;
            default:
                $p = 'C';
                break;
        }
        $config = $this->getInnerConfig();
        return $p.$config['prefix'].sprintf("%017d", $this->id);
    }

    /**
     * ラベル取得.
     * @return string ラベル
     */
    public function getLabelAttribute() : string
    {
        $config = $this->getConfig();
        return $config['label'];
    }

    /**
     * ステータス取得.
     * @return int ステータス
     */
    public function getStatusAttribute() : int
    {
        $status = $this->attributes['status'];

        // ギフトコードの場合
        if ($this->is_gift_code && $status == self::SUCCESS_STATUS) {
            // ギフトコード本文が存在しない、または期限切れの場合
            $expire_at = Carbon::today()->addDays(-60);
            if (!isset($this->response) || $this->requested_at->lt($expire_at)) {
                return self::STOP_STATUS;
            }
        }

        return $status;
    }

    /**
     * ステータスメッセージ取得.
     * @return string メッセージ
     */
    public function getStatusMessageAttribute() : ?string
    {
        $config = $this->getInnerConfig();
        return $config['status'][$this->status];
    }

    /**
     * 応答メッセージ取得.
     * @return string 結果メッセージ
     */
    public function getResMessageAttribute()
    {
        // ステータスと応答コードを確認
        if ($this->status == self::SUCCESS_STATUS ||
            $this->status == self::STOP_STATUS ||
            $this->status == self::PAYPAY_WAITING_STATUS ||
            $this->status == self::PAYPAY_RETRY_STATUS ||
            !isset($this->response_code)) {
            return null;
        }

        $config = $this->getInnerConfig();

        $response_messages = [];
        if($this->type == self::PAYPAY_TYPE){
            $res = explode(':', $this->response_code);
            if(WrapPhp::count($res)>0 && isset($res[0])){
                $key = trim($res[0]);
                $response_messages[] = $config['response_code'][$key] ?? '未確認エラー';
            }

        }else{
            // PaymentGateWayの場合複数エラーケースに対応
            foreach (explode(',', $this->response_code) as $response_code) {
                $response_messages[] = $config['response_code'][$response_code] ?? '未確認エラー';
            }
        }

        return implode(",", $response_messages);
    }

    public function getExchangeInfoAttribute()
    {
        return ExchangeInfo::ofType($this->type)
            ->ofTerm($this->created_at)
            ->first();
    }

    /**
     * ギフトコード確認.
     * @return bool ギフトコードの場合はtrueを、そうでない場合はfalseを返す
     */
    public function getIsGiftCodeAttribute() : bool
    {
        return in_array($this->type, self::$VOYAGE_GIFT_CODE_LIST, true) ||
            in_array($this->type, self::$NTT_GIFT_CODE_LIST, true);
    }

    public function scopeOfEnable($query)
    {
        return $query->where($this->table.'.status', '=', 0);
    }

    public function scopeOfVoyageGiftCode($query)
    {
        return $query->whereIn('type', self::$VOYAGE_GIFT_CODE_LIST);
    }

    public function scopeOfKdol($query)
    {
        return $query->where('type', '=', self::KDOL_TYPE);
    }


    /**
     *
     */
    public static function createExchangeRequestList(Collection $exchange_request_list)
    {
        $res_exchange_request_list = collect();

        $except_id_list = [];
        foreach ($exchange_request_list as $exchange_request) {
            if (!$exchange_request->createExchangeRequest($except_id_list)) {
                // 交換申し込みに失敗した場合
                continue;
            }
            $except_id_list[] = $exchange_request->id;
            $res_exchange_request_list->push($exchange_request);
        }

        return $res_exchange_request_list;
    }

    /**
     * 交換申し込み作成.
     * @return boolean 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function createExchangeRequest($except_id_list = null)
    {
        // 交換種類を確認
        $key = config(sprintf("exchange.point.%d", $this->type));
        if (!isset($key)) {
            return false;
        }
        $this->scheduled_at = Carbon::now();
        $exchange_request = $this;
        $user_point = UserPoint::getDefault(
            $this->user_id,
            config($key['config'].'.type'),
            -$this->point,
            0,
            $key['label']
        );

        // トランザクション処理
        return $user_point->addPoint(function () use ($user_point, $exchange_request, $except_id_list) {
            // ロックビルダー
            $exist_builder = ExchangeRequest::where('user_id', '=', $exchange_request->user_id)
                    ->where('created_at', '>=', Carbon::now()->addSeconds(-3));
            if (!empty($except_id_list)) {
                $exist_builder = $exist_builder->whereNotIn('id', $except_id_list);
            }

            // 3秒以内に同一ユーザーが申し込みをしていた場合、申し込みを実行しない
            if ($exist_builder->exists()) {
                return false;
            }

            // チケットを使用
            if ($exchange_request->use_ticket) {
                // チケットを確認
                $has_ticket = User::where('id', '=', $exchange_request->user_id)
                    ->where(function ($query) {
                        $query->whereNull('ticketed_at')
                            ->orWhere('ticketed_at', '<', Carbon::now()->startOfMonth());
                    })
                    ->exists();
                // チケットがないのにチケットを使おうとした場合
                if (!$has_ticket) {
                    return false;
                }

                User::where('id', '=', $exchange_request->user_id)
                    ->update(['ticketed_at' => Carbon::now()]);
            }

            // 保存実行
            $exchange_request->save();
            $user_point->parent_id = $exchange_request->id;
            return true;
        });
    }

    /**
     * 交換申し込み情報初期値取得.
     * @param int $user_id ユーザーID
     * @param int $type 種類
     * @param int $yen 額面(円分)
     * @param int $point ポイント数
     * @param string $ip IPアドレス
     * @return ExchangeRequest 交換申し込み情報
     */
    public static function getDefault(int $user_id, int $type, int $yen, int $point, string $ip) : ExchangeRequest
    {
        return (new self())->fill([
            'status' => 2, 'request_level' => 0, 'user_id' => $user_id, 'account_id' => 0,
            'type' => $type, 'yen' => $yen, 'ip' => $ip, 'use_ticket' => false, 'charge'=> 0,
            'point' => $point
        ]);
    }

    /**
     * 承認.
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function approvalRequest() : bool
    {
        $this->status = self::SUCCESS_STATUS;
        $exchange_request = $this;

        return DB::transaction(function () use ($exchange_request) {
            $exchange_request->save();
            return true;
        });
    }

    /**
     * 組戻し.
     * @param int $admin_id 更新管理者ID
     */
    public function rollbackRequest(int $admin_id = 0) : bool
    {
        $this->status = self::ROLLBACK_STATUS;
        $exchange_request = $this;
        $user_point = $this->user_point;

        return $user_point->rollbackPoint(
            $admin_id,
            '組戻し',
            function () use ($exchange_request) {
                // チケットも戻す
                if ($exchange_request->use_ticket == 1) {
                    User::where('id', '=', $exchange_request->user_id)
                        ->update(['ticketed_at' =>
                            Carbon::now()->startOfMonth()->addSeconds(-1)]);
                }

                // 実行
                $exchange_request->save();
                return true;
            }
        );
    }



    public function scopeOfPayPay($query)
    {
        return $query->where('type', '=', self::PAYPAY_TYPE);
    }

    public function scopeOfWaiting($query)
    {
        return $query->where('status', '=', self::WAITING_STATUS);
    }

    public function scopeOfPaypayWaiting($query)
    {
        return $query->where('status', '=', self::PAYPAY_WAITING_STATUS);
    }

    public function scopeOfExchangeWaiting($query)//交換申請中
    {
        return $query->where('status', '=', self::EXCHANGE_WAITING_STATUS);
    }

    /**
     * 申請中にstatus変更
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function waitingStatusRequest() : bool
    {
        if($this->status != self::PAYPAY_WAITING_STATUS){
            return false;
        }
        $this->status = self::WAITING_STATUS;
        $exchange_request = $this;

        return DB::transaction(function () use ($exchange_request) {
            $exchange_request->save();
            return true;
        });
    }

}
