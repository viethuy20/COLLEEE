<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * ユーザー更新履歴.
 */
class UserEditLog extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'user_edit_logs';
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

    /** 初期値. */
    const INIT_TYPE = 1;
    /** メールアドレス変更. */
    const EMAIL_TYPE = 2;
    /** 電話番号変更. */
    const TEL_TYPE = 3;
    /** パスワード変更. */
    const PASSWORD_TYPE = 4;
    /** メールアドレスリマインダー変更. */
    const EMAIL_REMIND_TYPE = 5;
    /** パスワードリマインダー変更. */
    const PASSWORD_REMIND_TYPE = 6;

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
}
