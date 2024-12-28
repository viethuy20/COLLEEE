<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * クリック履歴.
 */
class ExternalLink extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'external_links';

    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

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
    public function setUaAttribute(string $ua)
    {
        $this->attributes['ua'] = preg_replace_callback(
            '/[^\x20-\x7e]+/',
            function ($m) {
                return rawurlencode($m[0]);
            },
            $ua
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
    public function asp()
    {
        return $this->belongsTo(Asp::class, 'asp_id', 'id');
    }

    /**
     * 外部遷移.
     * @param string $url URL
     * @param int $user_id ユーザーID
     * @param int $asp_id ASP
     * @param string $ua ユーザーエージェント
     * @param string $ip IP
     * @param array|NULL $options
     */
    public static function addExternalLink(
        string $url,
        int $user_id,
        int $asp_id,
        string $ua,
        string $ip,
        $options = null
    ) {
        $external_link = new self();
        $external_link->created_at = Carbon::now();
        $external_link->url = $url;
        $external_link->user_id = $user_id;
        $external_link->asp_id = $asp_id;
        $external_link->ua = $ua;
        $external_link->ip = $ip;
        $external_link->asp_affiliate_id = $options['asp_affiliate_id'] ?? null;
        $external_link->program_id = $options['program_id'] ?? 0;
        $external_link->rid = $options['rid'] ?? null;

        // 保存実行
        $external_link->save();
    }
}
