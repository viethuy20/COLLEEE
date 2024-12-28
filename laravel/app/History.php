<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 履歴.
 */
class History extends Model
{
    use DBTrait;

    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'histories';

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

    const DOT_MONEY_TYPE = 1;

    /**
     * 履歴保存.
     * @param int $type 種類
     * @param mixid $data
     */
    public static function addHistory(int $type, $data)
    {
        $history = new self();
        $history->created_at = Carbon::now();
        $history->type = $type;
        $history->data = json_encode($data);

        // 保存実行
        $history->save();
    }
}
