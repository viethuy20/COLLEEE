<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\External\Ostiaries;

/**
 * 電話トークン.
 */
class OstToken extends Model
{
    use DBTrait;
    /** 正常. */
    const SUCCESS_STATUS = 0;
    /** 無効. */
    const ERROR_STATUS = 1;
    /** 作成. */
    const CREATE_STATUS = 2;
    /** 正常. */
    const SUCCESS_CHECK_STATUS = 0;
    /** 無効. */
    const ERROR_CHECK_STATUS = 1;
    /** 作成. */
    const CREATE_CHECK_STATUS = 2;

    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'ost_tokens';
    /**
     * 文字列キー有効化.
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * createメソッド実行時に、入力を許可するカラムの指定
     * @var array
     */
    protected $fillable = ['id', 'expired_at', 'status', 'tel'];
    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['expired_at'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    /**
     * Add extra attribute.
     */
    protected $appends = [];

    public function getNewDataAttribute()
    {
        if (array_key_exists('new_data', $this->appends)) {
            return $this->appends['new_data'];
        }
        $this->appends['new_data'] = null;
        if (isset($this->new_body)) {
            $this->appends['new_data'] = json_decode($this->new_body);
        }
        return $this->appends['new_data'];
    }

    public function getStartDataAttribute()
    {
        if (array_key_exists('start_data', $this->appends)) {
            return $this->appends['start_data'];
        }
        $this->appends['start_data'] = null;
        if (isset($this->start_body)) {
            $this->appends['start_data'] = json_decode($this->start_body);
        }
        return $this->appends['start_data'];
    }

    public function getGetDataAttribute()
    {
        if (array_key_exists('get_data', $this->appends)) {
            return $this->appends['get_data'];
        }
        $this->appends['get_data'] = null;
        if (isset($this->get_body)) {
            $this->appends['get_data'] = json_decode($this->get_body);
        }
        return $this->appends['get_data'];
    }

    public function getAuthenticNumberAttribute()
    {
        return $this->new_data->result->authentic_number ?? null;
    }

    public function scopeOfEnable($query, string $ost_token_id)
    {
        return $query->where('status', '=', self::SUCCESS_STATUS)
            ->where('id', '=', $ost_token_id)
            ->where('expired_at', '>=', Carbon::now());
    }

    public function scopeOfEnableOrError($query,string $token)
    {
        return $query->where(function ($query) {
            $query->where('status', '=', self::SUCCESS_STATUS)
                ->orWhere('status', '=', self::CREATE_STATUS);
        })->where('id', '=', $token)->where('expired_at', '>=', Carbon::now());

    }

    /**
     * 電話トークン作成.
     * @param string $tel 電話番号
     * @return OstToken|null トークン
     */
    public static function createToken(string $tel)
    {
        // 初期化
        $ost_token = new self();
        $ost_token->expired_at = Carbon::now()->addDays(1);
        $ost_token->status = self::CREATE_STATUS;
        $ost_token->tel = $tel;
        $res = false;
        for ($i = 0; $i < 3; ++$i) {
            // トークンID発行
            $ost_token_id = (string) Str::uuid();
            // ロックキー作成
            $lock_key = sprintf("ost_token_create_%s", $ost_token_id);
            $ost_token->id = $ost_token_id;
            $res = self::saveWithLock(
                $lock_key,
                function () use ($ost_token) {
                    // 保存実行
                    $ost_token->save();
                    return true;
                },
                function () use ($ost_token_id) {
                    // トークンが重複した場合
                    return !OstToken::where('id', '=', $ost_token_id)->exists();
                }
            );
            // 保存に成功した場合
            if ($res) {
                break;
            }
        }
        // 作成に失敗
        if (!$res) {
            return null;
        }
        // APIを利用しない場合
        if (!Ostiaries::getConfig('USE')) {
            // 保存実行
            $ost_token->status = self::SUCCESS_STATUS;
            $ost_token->save();
            return $ost_token;
        }
        // トランザクション作成取得
        $ost_token->new_body = Ostiaries::getNewTransaction($ost_token->id, $ost_token->tel);
        // 実行失敗
        if (!isset($ost_token->new_body)) {
            return null;
        }
        // 保存実行
        $ost_token->save();
        //
        if ($ost_token->new_data->response != 'success' || !isset($ost_token->new_data->result->transaction_id)) {
            return null;
        }
        // 保存実行
        $ost_token->transaction_id = $ost_token->new_data->result->transaction_id;
        $ost_token->save();
        // トランザクション開始取得
        $ost_token->start_body = Ostiaries::getStartAuthentication($ost_token->transaction_id);
        // 実行失敗
        if (!isset($ost_token->start_body)) {
            return null;
        }
        // 保存実行
        $ost_token->save();
        //
        if ($ost_token->start_data->response != 'success') {
            return null;
        }
        return $ost_token;
    }

    public function checkStatus() : int
    {
        // すでに成功している場合
        if ($this->status == self::SUCCESS_STATUS) {
            return self::SUCCESS_CHECK_STATUS;
        }
        // 有効期限切れ、または無効化されていた場合
        if ($this->expired_at->lt(Carbon::now()) || $this->status == self::ERROR_STATUS) {
            return self::ERROR_CHECK_STATUS;
        }
        // トランザクション状態取得
        $this->get_body = Ostiaries::getGetTransactionStatus($this->transaction_id);
        // 実行失敗
        if (!isset($this->get_body)) {
            $this->status = self::ERROR_STATUS;
            $this->save();
            return self::ERROR_CHECK_STATUS;
        }
        if ($this->get_data->response == 'success') {
            // 未確認
            if ($this->get_data->result->status == 'started') {
                return self::CREATE_CHECK_STATUS;
            }
            // 成功
            if ($this->get_data->result->status == 'succeeded') {
                $this->status = self::SUCCESS_STATUS;
                $this->save();
                return self::SUCCESS_CHECK_STATUS;
            }
        }
        // エラーを保存
        $this->status = self::ERROR_STATUS;
        $this->save();
        // エラーの場合
        return self::ERROR_CHECK_STATUS;
    }

}
