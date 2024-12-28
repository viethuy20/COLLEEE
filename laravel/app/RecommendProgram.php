<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * レコメンド.
 */
class RecommendProgram extends Model
{
/**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'recommend_programs';
    
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['start_at', 'stop_at', 'deleted_at'];


    public function program()
    {
        return $this->belongsTo('App\Program','program_id','id');
    }
    
}