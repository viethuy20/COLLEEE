<?php
namespace App;

use Carbon\Carbon;
use \DB;
use Illuminate\Database\Eloquent\Model;

use \App\Device\Device;

/**
 * ログイン履歴.
 */
class UserLogin extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'user_logins';
    /**
     * createメソッド実行時に、入力を許可するカラムの指定
     * @var array
     */
    protected $fillable = ['created_at', 'user_id', 'ip', 'user_agent', 'device_id'];

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];


    /**
     * 更新日時更新停止.
     * @var bool
     */
    public $timestamps = false;

    /**
     * ユーザーエージェント登録.
     * @param string $ua ユーザーエージェント.
     */
    public function setUaAttribute($ua)
    {
        $ua = preg_replace_callback(
            '/[^\x20-\x7e]+/',
            function ($m) {
                return rawurlencode($m[0]);
            },
            $ua
        );
        $this->attributes['ua'] = substr($ua, 0, 255);
    }

    /**
     * 登録.
     * @param string $ip IPアドレス
     * @param string|null $ua UA
     * @param User $user ユーザー
     */
    public static function addLogin(string $ip, ?string $ua, User $user)
    {
        $now = Carbon::now();

        $user_login = new self();
        $user_login->created_at = $now;
        $user_login->user_id = $user->id;
        $user_login->ip = $ip;
        $user_login->ua = $ua;
        $user_login->device_id = isset($ua) ? Device::getDeviceIdFromUA($ua) : 1;
        $user->actioned_at = $now;

        $cur_user_login = self::where('user_id', '=', $user_login->user_id)
            ->where('created_at', '>=', $user_login->created_at->copy()->startOfDay())
            ->where('ip', '=', $user_login->ip)
            ->where('ua', '=', $user_login->ua)
            ->first();
        // 既に同一IP,UAで今日ログインしている場合、履歴を残さず終了
        if (isset($cur_user_login->id)) {
            return;
        }

        // トランザクション処理
        $res = DB::transaction(function () use ($user, $user_login) {
            // 保存実行
            $user->save();
            $user_login->save();
            return true;
        });
    }
}
