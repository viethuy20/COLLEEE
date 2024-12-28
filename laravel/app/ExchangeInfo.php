<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 交換情報.
 */
class ExchangeInfo extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'exchange_infos';
    
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['start_at', 'stop_at'];

    protected $casts = [
        'start_at' => 'datetime',
        'stop_at' => 'datetime',
    ];

    /** 正常. */
    const SUCCESS_STATUS = 0;
    /** 公開停止. */
    const STOP_STATUS = 1;

    public function scopeOfType($query, int $type)
    {
        return $query->where('type', '=', $type);
    }

    public function scopeOfTerm($query, Carbon $created_at)
    {
        return $query->where('stop_at', '>=', $created_at)
            ->where('start_at', '<=', $created_at);
    }

    public function scopeOfNow($query)
    {
        return $query->ofTerm(Carbon::now());
    }

    public function getMessageListAttribute()
    {
        // 空の場合
        if (!isset($this->messages)) {
            return collect([]);
        }
        
        $message_list = [];
        $p_message_list = json_decode($this->messages);
        foreach ($p_message_list as $p_message) {
            $message_list[] = (object) ['start_at' => Carbon::parse($p_message->start_at), 'body' => $p_message->body];
        }
        return collect($message_list);
    }

    public function getMessageBodyAttribute()
    {
        $now = Carbon::now();
        $message_list = $this->message_list;
        while (true) {
            $message = $message_list->pop();
            if (!isset($message)) {
                return null;
            }
            if ($message->start_at->lt($now)) {
                return $message->body;
            }
        }
    }

    private function getConfig()
    {
        return config('exchange.point.'.$this->type);
    }

    public function getYenListAttribute() : array
    {
        $config = $this->getConfig();
        // リストが存在する場合
        if (isset($config['yen']['list'])) {
            return $config['yen']['list'];
        }
        $yen_max = config('exchange.yen_max');
        // ステップからリストを作成
        $yen_list = [];
        $yen = $config['yen']['min'];
        $step = $config['yen']['step'];
        while ($yen <= $yen_max) {
            $yen_list[] = $yen;
            $yen = $yen + $step;
        }
        return $yen_list;
    }

    public function getYenLabelMap($max_yen = null) : array
    {
        $yen_list = $this->yen_list;
        $yen_map = [];
        foreach ($yen_list as $yen) {
            if (isset($max_yen) && $yen > $max_yen) {
                break;
            }
            $yen_map[$yen] = number_format($yen);
        }
        return $yen_map;
    }

    public function getYenPointLabelMap($max_yen = null) : array
    {
        $yen_list = $this->yen_list;
        $yen_map = [];
        foreach ($yen_list as $yen) {
            if (isset($max_yen) && $yen > $max_yen) {
                break;
            }
            $yen_map[$yen] = number_format($yen);
        }
        return $yen_map;
    }
        
    /**
     * 交換先名称取得.
     * @return string 交換先名称
     */
    public function getLabelAttribute(): string
    {
        return $this->getConfig()['label'];
    }

    /**
     * 単位取得.
     * @return string 単位
     */
    public function getUnitAttribute(): string
    {
        return $this->getConfig()['unit'];
    }

    /**
     * 交換日時目安取得.
     * @return string 交換日時目安
     */
    public function getExchangeAtAttribute(): string
    {
        return $this->getConfig()['exchange_at'];
    }
    
    /**
     * 最低交換金額取得.
     * @return int 最低交換金額
     */
    public function getMinYenAttribute(): int
    {
        return $this->getConfig()['yen']['min'];
    }

    /**
     * 円をColleeeポイントに変換.
     * @param int $yen 円
     * @return int Colleeeポイント
     */
    public static function yen2Point(int $yen) : int
    {
        return floor($yen * config('exchange.yen_rate') / 100);
    }

    /**
     * Colleeeポイントを円に変換.
     * @param int $point Colleeeポイント
     * @return int 円
     */
    public static function point2Yen(int $point) : int
    {
        return floor($point * 100 / config('exchange.yen_rate'));
    }

    /**
     * 消費Colleeeポイント取得.
     * @param int $yen 円
     * @return int Colleeeポイント
     */
    public function chargePoint(int $yen) : int
    {
        // 金額(円) * 円交換比率
        return floor($yen * $this->yen_to_point_rate);
    }

    /**
     * 消費円取得.
     * @param int $point Colleeeポイント
     * @return int 円
     */
    public function chargeYen(int $point) : int
    {
        // Colleeeポイント * ポイント交換比率
        return floor($point * $this->point_to_yen_rate);
    }

    /**
     * 交換額取得.
     * @param int $yen 円
     * @return int 額
     */
    public function exchangeAmount(int $yen) : int
    {
        // 交換金額(円) * 交換先交換比率(円 => 各交換先単位)
        return $yen * $this->getConfig()['yen']['rate'] / 100;
    }

    public function getPointToYenRateAttribute(): float
    {
        return 10000 / ($this->yen_rate * config('exchange.yen_rate'));
    }

    public function getYenToPointRateAttribute(): float
    {
        return ($this->yen_rate * config('exchange.yen_rate')) / 10000;
    }

    /**
     * 最低交換ポイント数取得.
     * @return int 最低交換ポイント数
     */
    public function getMinPointAttribute(): int
    {
        return $this->chargePoint($this->min_yen);
    }

    /**
     * 最低交換額取得.
     * @return int 最低交換額
     */
    public function getMinAmountAttribute(): int
    {
        return $this->exchangeAmount($this->min_yen);
    }

    /**
     * URL取得.
     * @return string URL
     */
    public function getUrlAttribute(): string
    {
        // 金融機関振り込みの場合
        if ($this->type == ExchangeRequest::BANK_TYPE) {
            return route('banks.index');
        }
        // ドットマネーの場合
        if ($this->type == ExchangeRequest::DOT_MONEY_POINT_TYPE) {
            return route('dot_money.index');
        }
        // Dポイントの場合
        if ($this->type == ExchangeRequest::D_POINT_TYPE) {
            return route('d_point.index');
        }
        // LINEペイの場合
        if ($this->type == ExchangeRequest::LINE_PAY_TYPE) {
            return route('line_pay.index');
        }



        // デジタルギフト PayPalの場合
        if ($this->type == ExchangeRequest::DIGITAL_GIFT_PAYPAL_TYPE) {
            return route('paypal.index');
        }

        // JALマイルの場合

        if ($this->type == ExchangeRequest::DIGITAL_GIFT_JALMILE_TYPE) {
            return route('jalmile.index');
        }


        // PayPayの場合
        if ($this->type == ExchangeRequest::PAYPAY_TYPE) {
            return route('paypay.index');
        }



        // KDOLの場合
        if ($this->type == ExchangeRequest::KDOL_TYPE) {
            return route('kdol.index');
        }

        // それ以外はギフトコード
        return route('gift_codes.index', ['type' => $this->type]);
    }

    /**
     * 交換申し込み情報初期値取得.
     * @param User $user ユーザー
     * @param int $yen 額面(円分)
     * @param string $ip IPアドレス
     * @return ExchangeRequest 交換申し込み情報
     */
    public function getRequest(User $user, int $yen, string $ip) : ExchangeRequest
    {
        return ExchangeRequest::getDefault($user->id, $this->type, $yen, $this->chargePoint($yen), $ip);
    }

    /**
     * 交換申し込み情報初期値取得.
     * @param User $user ユーザー
     * @param int $yen 額面(円分)
     * @param string $ip IPアドレス
     * @param BankAccount $bank_account 銀行口座
     * @return ExchangeRequest 交換申し込み情報
     */
    public function getBankRequest(User $user, int $yen, string $ip, BankAccount $bank_account) : ExchangeRequest
    {
        // 銀行取得
        $bank = $bank_account->bank;
        // 手数料取得
        $user_charge = $user->has_ticket ? 0 : $bank->getCharge($user);

        $exchange_request = ExchangeRequest::getDefault(
            $user->id,
            $this->type,
            $yen - $user_charge,
            $yen,
            $ip
        );
        // 手数料チケット
        $exchange_request->use_ticket = $user->has_ticket;
        // 銀行口座ID
        $exchange_request->account_id = $bank_account->id;
        // 割引手数料
        $exchange_request->charge = $bank->full_charge - $user_charge;
        return $exchange_request;
    }

    public static function getInfoMap()
    {
        return self::ofNow()
            ->get()
            ->keyBy('type')
            ->all();
    }
}
