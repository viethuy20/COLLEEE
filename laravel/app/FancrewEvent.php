<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * ファンくるイベント.
 */
class FancrewEvent extends Model
{
    use DBTrait;
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'fancrew_events';
    
    /**
     * createメソッド実行時に、入力を許可するカラムの指定
     * @var array
     */
    protected $fillable = ['id', 'created_at'];
    
    /**
     * 更新日時更新停止.
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * ファンくるイベント情報初期値取得.
     * @param int $id ID
     * @param int $status 状態
     * @return \App\FancrewEvent ファンくるイベント
     */
    public static function getDefault(int $id, int $status = 0) : FancrewEvent
    {
        $fancrew_event = new self();
        $fancrew_event->id = $id;
        $fancrew_event->created_at = Carbon::now();
        $fancrew_event->status = $status;
        return $fancrew_event;
    }
}
