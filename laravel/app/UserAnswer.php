<?php
namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * アンケート回答.
 */
class UserAnswer extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'user_answers';
    
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];
    
    public function scopeOfMessageList($query, int $question_id)
    {
        return $query->where('question_id', '=', $question_id)
            ->where('status', '=', 0)
            ->orderBy('updated_at', 'desc');
    }

    /**
     * 世代取得.
     * @return int 世代
     */
    public function getGenerationAttribute()
    {
        $generation = $this->attributes['generation'];
        $generation_keys = array_keys(config('map.generation'));
        $generation = max($generation, min($generation_keys));
        $generation = min($generation, max($generation_keys));

        return $generation;
    }
}
