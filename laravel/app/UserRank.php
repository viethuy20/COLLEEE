<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * ユーザーランク.
 */
class UserRank extends Model
{
    const GOLD = 3;
    const SILVER = 2;
    const NORMAL = 0;

    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'user_ranks';
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

    
    public function scopeOfTerm($query, Carbon $d)
    {
        return $query->where('stop_at', '>=', $d)
            ->where('start_at', '<=', $d);
    }
}
