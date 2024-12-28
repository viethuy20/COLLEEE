<?php

namespace App;

use Carbon\Carbon;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use WrapPhp;

class User extends Authenticatable
{
    use Notifiable, DBTrait;

    const USER_LOCK_KEY = 'user_update';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [
    //    'name', 'email', 'password',
    //];
    protected $guarded = [
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    

    /** 正常. */
    const COLLEEE_STATUS = 0;
    /** 自主退会. */
    const SELF_WITHDRAWAL_STATUS = 1;
    /** 強制退会. */
    const FORCE_WITHDRAWAL_STATUS = 2;
    /** システム. */
    const SYSTEM_STATUS = 4;
    /** 運用退会. */
    const OPERATION_WITHDRAWAL_STATUS = 5;
    /** 交換ロック. */
    const LOCK1_STATUS = 6;
    /** ユーザー全機能ロック. */
    const LOCK2_STATUS = 7;

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['birthday', 'ticketed_at', 'actioned_at', 'deleted_at',];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'test' => 'boolean',
        'birthday' => 'datetime',
        'ticketed_at' => 'datetime',
        'actioned_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
   
    /**
     * Add extra attribute.
     */
    protected $appends = [];

    public function ranks()
    {
        return $this->hasMany(UserRank::class, 'user_id', 'id');
    }

    public function points()
    {
        return $this->hasMany(UserPoint::class, 'user_id', 'id')
            ->orderBy('id', 'desc');
    }
    // @codingStandardsIgnoreStart
    public function exchange_requests()
    {
        // @codingStandardsIgnoreEnd
        return $this->hasMany(ExchangeRequest::class, 'user_id', 'id')
            ->orderBy('id', 'desc');
    }
    // @codingStandardsIgnoreStart
    public function fav_programs()
    {
        // @codingStandardsIgnoreEnd
        return $this->belongsToMany(Program::class, 'user_programs', 'user_id', 'program_id')
            ->wherePivot('status', '=', 0)
            ->withPivot('created_at')
            ->orderBy('user_programs.created_at', 'desc')
            ->ofEnable();
    }
    // @codingStandardsIgnoreStart
    public function user_recipes()
    {
        // @codingStandardsIgnoreEnd
        return $this->hasMany(UserRecipe::class, 'user_id', 'id')
            ->ofEnable();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'id');
    }

    // @codingStandardsIgnoreStart
    public function aff_accounts()
    {
        // @codingStandardsIgnoreEnd
        return $this->hasMany(AffAccount::class, 'user_id', 'id');
    }

    // @codingStandardsIgnoreStart
    public function aff_rewards()
    {
        // @codingStandardsIgnoreEnd
        return $this->hasMany(AffReward::class, 'user_id', 'id');
    }

    // @codingStandardsIgnoreStart
    public function edit_logs()
    {
        // @codingStandardsIgnoreEnd
        return $this->hasMany(UserEditLog::class, 'user_id', 'id');
    }

    public function line_account()
    {
        // @codingStandardsIgnoreEnd
        return $this->hasOne(LineAccount::class);
    }


    public function getBankAccountAttribute()
    {
        return BankAccount::where('user_id', '=', $this->id)
            ->where('status', '=', 0)
            ->first();
    }

    public function getFancrewAccountNumberAttribute() : ?int
    {
        $aff_acount = $this->aff_accounts()->ofType(\App\AffAccount::FANCREW_TYPE)->first();
        return isset($aff_acount->id) ? $aff_acount->number : null;
    }

    /**
     * ユーザーIDからユーザー名取得.
     * @param int $user_id ユーザーID
     * @return string ユーザー名
     */
    public static function getNameById(int $user_id) : string
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
        return $p.sprintf("%015d", $user_id);
    }

    /**
     * 名前取得.
     * @return string 名前
     */
    public function getNameAttribute() :string
    {
        return self::getNameById($this->id);
    }

    /**
     * 年齢取得.
     * @return int 年齢
     */
    public function getAgeAttribute() :int
    {
        return $this->birthday->age;
    }

    /**
     * マスク付き電話番号取得.
     */
    public function getMaskedTelAttribute()
    {
        if (!isset($this->tel)) {
            return '';
        }
        return str_repeat('*', strlen($this->tel) - 4). substr($this->tel, -4);
    }

    /**
     * 手数料チケット.
     * @return bool trueの場合は使用可能,falseの場合は使用不可能
     */
    public function getHasTicketAttribute() :bool
    {
        // ゴールドランク以外の場合
        if ($this->rank != 2) {
            return false;
        }

        if (!isset($this->ticketed_at)) {
            return true;
        }
        return $this->ticketed_at->lt(Carbon::now()->startOfMonth());
    }

    /**
     * 世代取得.
     * @return int 世代
     */
    public function getGenerationAttribute()
    {
        $age = $this->age;
        $generation_keys = array_keys(config('map.generation'));
        $generation = intval($age - ($age % 10));
        $generation = max($generation, min($generation_keys));
        $generation = min($generation, max($generation_keys));

        return $generation;
    }

    /**
     * ポイント取得.
     * @return int ポイント
     */
    public function getPointAttribute() : int
    {
        $last_user_point = $this->points()->first();
        return isset($last_user_point->id) ? $last_user_point->point : 0;
    }

    /**
     * 交換ポイント取得.
     * @return int 交換ポイント
     */
    public function getExchangedPointAttribute() : int
    {
        $last_user_point = $this->points()->first();
        if (isset($last_user_point->id) && ($last_user_point->exchanged_point - $this->getExchangingPointAttribute())>0) {
            return $last_user_point->exchanged_point - $this->getExchangingPointAttribute();
        }
        return 0;
    }

    /**
     * 交換中ポイント取得.
     * @return int ポイント
     */
    public function getExchangingPointAttribute() : int
    {
        // 交換中ポイントを取得
        return $this->exchange_requests()
            ->whereIn('status', [ExchangeRequest::WAITING_STATUS, ExchangeRequest::ERROR_STATUS, ExchangeRequest::PAYPAY_WAITING_STATUS, ExchangeRequest::PAYPAY_RETRY_STATUS])
            ->sum('point');
    }

    /**
     * ランク取得.
     * @return int ランク
     */
    public function getRankAttribute() : int
    {
        // 値を持っていた場合
        if (isset($this->appends['rank'])) {
            return $this->appends['rank'];
        }

        $now = Carbon::now();
        // ランク取得
        $user_rank = $this->ranks()
            ->ofTerm($now)
            ->orderBy('created_at', 'desc')->first();
        $this->appends['rank'] = $user_rank->rank ?? 0;
        return $this->appends['rank'];
    }

    /**
     * 交換可能ポイント最大値取得.
     * @return int ポイント
     */
    public function getMaxExchangePointAttribute() : int
    {
        $max = config('exchange.max');

        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $exchange_point = $this->exchange_requests()
            ->whereBetween('created_at', [$start, $end])
            ->whereIn(
                'status',
                [ExchangeRequest::SUCCESS_STATUS, ExchangeRequest::WAITING_STATUS,
                    ExchangeRequest::ERROR_STATUS, ExchangeRequest::STOP_STATUS, ExchangeRequest::PAYPAY_WAITING_STATUS, ExchangeRequest::PAYPAY_RETRY_STATUS]
            )
            ->sum('point');
        return min(max($max - $exchange_point, 0), $this->point);
    }

    /**
     * 獲得予定成果ポイント総数取得.
     * @return int ポイント数
     */
    public function getRewardPointTotalAttribute() : int
    {
        // 値を持っていた場合
        if (isset($this->appends['reward_point_total'])) {
            return $this->appends['reward_point_total'];
        }
        $this->appends['reward_point_total'] = $this->aff_rewards()
            ->ofWaiting()
            ->sum('point');
        return $this->appends['reward_point_total'];
    }

    public function scopeOfEmail($query, string $email)
    {
        // メールアドレスの書式を確認
        $validator = \Validator::make(
            ['email' => email_quote($email)],
            ['email' => ['required', 'email'],]
        );
        if ($validator->fails()) {
            return $query->whereRaw('1 <> 1');
        }

        $p_email = email_unquote($email);
        $parsed_email = explode('@', $p_email);
        $email_domain = array_pop($parsed_email);
        $email_user = implode('@', $parsed_email);

        // Gmailの場合は同一ユーザー別名メールアドレスも検索
        $gmail_domain = 'gmail.com';
        if ($email_domain == $gmail_domain) {
            $p_email_user = str_replace('.', '', $email_user);
            $p_email_user_list = explode('+', $p_email_user);
            $p_email_user = array_pop($p_email_user_list);
            if (!empty($p_email_user_list)) {
                $p_email_user = implode('+', $p_email_user_list);
            }
            return $query->where('email', 'like', '%@'.$gmail_domain)
                ->whereRaw(
                    "substring_index(replace(replace(email, ?, ''), '.', ''), '+', 1) = ?",
                    ['@'.$gmail_domain, $p_email_user]
                );
        }
        // Yahooメールの場合は同一ユーザー別名メールアドレスも検索
        $yahoo_domain = 'yahoo.co.jp';
        if ($email_domain == $yahoo_domain) {
            $p_email_user_list = explode('-', $email_user);
            $p_email_user = array_pop($p_email_user_list);
            if (!empty($p_email_user_list)) {
                $p_email_user = implode('-', $p_email_user_list);
            }
            return $query->where('email', 'like', '%@'.$yahoo_domain)
                ->whereRaw("substring_index(replace(email, ?, ''), '-', 1) = ?", ['@'.$yahoo_domain, $p_email_user]);
        }
        // MSNメールの場合は同一ユーザー別名メールアドレスも検索
        $msn_domain_list = ['hotmail.co.jp', 'live.jp', 'outlook.jp', 'outlook.com'];
        if (in_array($email_domain, $msn_domain_list, true)) {
            $p_email_user_list = explode('+', $email_user);
            $p_email_user = array_pop($p_email_user_list);
            if (!empty($p_email_user_list)) {
                $p_email_user = implode('+', $p_email_user_list);
            }
            return $query->where('email', 'like', '%@'.$email_domain)
                ->whereRaw("substring_index(replace(email, ?, ''), '+', 1) = ?", ['@'.$email_domain, $p_email_user]);
        }

        return $query->where('email', '=', $p_email);
    }

    public function scopeOfEnable($query)
    {
        return $query->whereIn('status', [self::COLLEEE_STATUS, self::LOCK1_STATUS, self::LOCK2_STATUS]);
    }

    /**
     * 重複検証.
     * @param array $params パラメーター
     * @param int|NULL $id ID
     * @return bool 重複する場合はfalseを返す
     */
    public static function checkUnique(array $params, $id = null) : bool
    {
        $builder = self::select('id');
        // IDが存在した場合、取り除く
        if (isset($id)) {
            $builder->where('id', '<>', $id);
        }
        // 重複条件登録
        foreach ($params as $key => $data) {
            if ($key == 'email') {
                $builder = $builder->ofEmail($data);
                continue;
            }
            $builder = $builder->where($key, '=', $data);
        }

        // 重複するデータを取得
        $user = $builder->orderBy('id','desc')->first();

        return !isset($user->id);
    }

    /**
     * 電話番号重複検証.
     * @param string $tel 電話番号
     * @param int|NULL $id ID
     * @return bool 電話番号の再利期間考慮し重複する場合はfalseを返す
     */
    public static function checkPhoneUnique(string $tel, $id = null) : bool
    {
        $withdrawalStatus = [
            self::SELF_WITHDRAWAL_STATUS,
        ];

        $builder = self::select('id', 'status', 'deleted_at');

        // IDが存在した場合、取り除く
        if (isset($id)) {
            $builder->where('id', '<>', $id);
        }

        $users = $builder->where('tel', '=', $tel)->orderBy('id','desc')->get();
        $user = $users->first();
        // 未登録はOK
        if (WrapPhp::count($users) == 0) {
            return true;
        }

        // 登録済み電話番号に関するチェック
        $now = new Carbon();
        $otherUsers = $users->filter(function () use ($user,$now, $withdrawalStatus) {

            if (!in_array($user->status, $withdrawalStatus)) {
                return false;
            }

            // 退会ステータスで1年以上前のデータはOK
            $deleted_at = new Carbon($user->deleted_at);
            $diff = $now->diffInDays($deleted_at);
            $diff = abs((int)$diff);
            if ($diff < 365) {
                return false;
            }

            return true;
        });

        return WrapPhp::count($otherUsers) > 0;
    }

    /**
     * ユーザー作成.
     * @param string $ip IPアドレス
     * @param string $ua ユーザーエージェント
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function createUser(string $ip, string $ua) : bool
    {
        $user = $this;
        return self::saveWithLock(
            self::USER_LOCK_KEY,
            function () use ($user, $ip, $ua) {
                // 保存実行
                $user->ip = $ip;
                $user->save();
                // 履歴追加
                $user->addEditLog(UserEditLog::INIT_TYPE, $ip, $ua);
                return true;
            },
            function () use ($user) {
                // メールアドレスまたは電話番号が重複する場合
                if (!User::checkUnique(['email' => $user->email])
                        || !User::checkPhoneUnique($user->tel)) {
                    return false;
                }

                for ($i = 0; $i < 3; ++$i) {
                    // 友達コード作成
                    $friend_code = md5(uniqid(rand(), 1));

                    // 友達コードが重複する場合
                    if (!User::checkUnique(['friend_code' => $friend_code])) {
                        continue;
                    }
                    $user->friend_code = $friend_code;
                    break;
                }
                // 友達コードが存在しなかった場合
                if (!isset($user->friend_code)) {
                    return false;
                }
                $user->status = 0;
                return true;
            }
        );
    }

    /**
     * パスワードを更新する.
     * @param string $password パスワード
     * @param string $ip IPアドレス
     * @param string $ua ユーザーエージェント
     * @param bool $reminder リマインダー
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function editPassword(string $password, string $ip, string $ua, bool $reminder = false) : bool
    {
        $this->password = app()->make('hash')->make($password);
        $res = $this->editUser($reminder ? UserEditLog::PASSWORD_REMIND_TYPE : UserEditLog::PASSWORD_TYPE, $ip, $ua);
        if (!$res) {
            return false;
        }

        // メール送信を実行
        $options = ['user' => $this];
        try {
            $mailable = new \App\Mail\Colleee($this->email, 'store_password', $options);
            \Mail::send($mailable);
        } catch (\Exception $e) {
        }

        return true;
    }

    /**
     * メールアドレスを更新する.
     * @param string $email メールアドレス
     * @param string $ip IPアドレス
     * @param string $ua ユーザーエージェント
     * @param bool $reminder リマインダー
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function editEmail(string $email, string $ip, string $ua, bool $reminder = false) : bool
    {
        // 同一の値のため、変更しない
        if ($this->email == $email) {
            return true;
        }
        $this->email = $email;
        $this->email_status = 0;
        return $this->editUser($reminder ? UserEditLog::EMAIL_REMIND_TYPE : UserEditLog::EMAIL_TYPE, $ip, $ua);
    }

    /**
     * 電話番号を更新する.
     * @param string $tel 電話番号
     * @param string $ip IPアドレス
     * @param string $ua ユーザーエージェント
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function editTel(string $tel, string $ip, string $ua) : bool
    {
        // 同一の値のため、変更しない
        if ($this->tel == $tel) {
            return true;
        }
        $this->tel = $tel;
        $res = $this->editUser(UserEditLog::TEL_TYPE, $ip, $ua);
        if (!$res) {
            return false;
        }

        // 乗っ取りの疑いがあるか確認
        if ($this->edit_logs()
            ->where('type', '=', UserEditLog::PASSWORD_REMIND_TYPE)
            ->where('created_at', '>', Carbon::today()->addDays(-7))
            ->exists() && $this->point >= 100) {
            // 交換ロックを行う
            $this->status = self::LOCK1_STATUS;
            $this->save();
        }

        // メール送信を実行
        $options = ['user' => $this];
        try {
            $mailable = new \App\Mail\Colleee($this->email, 'store_tel', $options);
            \Mail::send($mailable);
        } catch (\Exception $e) {
        }

        return true;
    }

    /**
     * ユーザー更新ログ作成.
     * @param int $type 種類
     * @param string $ip IPアドレス
     * @param string $ua ユーザーエージェント
     */
    private function addEditLog(int $type, string $ip, string $ua)
    {
        // 直前の履歴を取得
        $last_user_edit_log = $this->edit_logs()
            ->orderBy('id', 'desc')
            ->first();
        $user_edit_log = new UserEditLog();
        $user_edit_log->user_id = $this->id;
        $user_edit_log->type = $type;
        $user_edit_log->email = $this->email;
        $user_edit_log->tel = $this->tel;
        $user_edit_log->ip = $ip;
        $user_edit_log->ua = $ua;
        $user_edit_log->created_at = Carbon::now();
        $user_edit_log->save();
    }

    /**
     * ユーザー更新.
     * @param int $type 種類
     * @param string $ip IPアドレス
     * @param string $ua ユーザーエージェント
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    private function editUser(int $type, string $ip, string $ua) : bool
    {
        $user = $this;
        // 現在のユーザー情報を取得
        $first_user = User::find($this->id);
        return self::saveWithLock(
            self::USER_LOCK_KEY,
            function () use ($user, $first_user, $type, $ip, $ua) {
                // 直前の履歴を取得
                $last_user_edit_log = $user->edit_logs()
                    ->orderBy('id', 'desc')
                    ->first();
                // 直前の履歴が存在しなかった場合
                if (!isset($last_user_edit_log->id)) {
                    $first_user->addEditLog(UserEditLog::INIT_TYPE, $first_user->ip, '');
                }

                // 情報更新
                $user->save();

                // 履歴追加
                $user->addEditLog($type, $ip, $ua);
                return true;
            },
            function () use ($user, $first_user) {
                // メールアドレスが重複する場合
                if ($user->email != $first_user->email && !User::checkUnique(['email' => $user->email], $user->id)) {
                    return false;
                }
                // 電話番号が重複する場合
                if ($user->tel != $first_user->tel && !User::checkPhoneUnique($user->tel, $user->id)) {
                    return false;
                }
                return true;
            }
        );
    }

    /**
     * 自主退会実行.
     */
    public function selfWithdraw()
    {
        //
        $this->status = self::SELF_WITHDRAWAL_STATUS;
        // 退会日時を登録
        $this->deleted_at = Carbon::now();

        // 保存実行
        $user = $this;
        return DB::transaction(function () use ($user) {
            // 登録実行
            $user->save();
            return true;
        });
    }

    /**
     * 同じIPで連続した会員登録を確認する.
     * @param string $ip IPアドレス
     * @param Carbon $base_time 基準時間
     */
    public function scopeOfDuplicateIp($query, $ip, Carbon $basetime)
    {
        $sub_time = config('entry.sub_time') ?? 24;
        $beforetime = $basetime->copy()->subHours($sub_time);

        return $query
            ->where('ip', $ip)
            ->where('created_at', '>=', $beforetime);
    }

    /**
     * 同一IPアドレスの登録があるか確認する
     * @param string $ip IPアドレス
     * @param Carbon $base_time 基準時間
     * @return bool 同一IPアドレスの登録がある場合はtrueを、ない場合はfalseを返す
     */
    public static function checkDuplicateIp($ip, Carbon $basetime) : bool
    {
        $max_lock_count = config('entry.max_lock_count') ?? 3;
        return !(self::ofDuplicateIp($ip, $basetime)->count() >= $max_lock_count);
    }

    // 不正な登録のユーザーをロックする
    public static function lockUsers($ip, Carbon $basetime) {
        $lock_users = self::ofDuplicateIp($ip, $basetime)
                        ->where('status', '<>', self::LOCK2_STATUS)
                        ->get();

        $result = self::saveWithLock(
            self::USER_LOCK_KEY, 
            function () use ($lock_users) {
                foreach ($lock_users as $lock_user) {
                    $lock_user->status = self::LOCK2_STATUS;
                    $lock_user->save();
                }
                return true;
            },
         );

        if (!$result) return false;

        // メール通知
        $locked_id_list =
            $lock_users
                ->map(function ($user) {
                    return User::getNameById($user->id);
                });
        try {
            $options = ['datetime' => $basetime, 'ip' => $ip, 'locked_id_list' => $locked_id_list];
            $mailable = new \App\Mail\SystemAlert('illegal_registration', $options);
            \Mail::send($mailable);
        } catch (\Exception $e) {
        }

        return true;
    }
}
