<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 特集サブカテゴリ.
 */
class FeatureSubCategory extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'feature_sub_categories';
    
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

    
    public function scopeOfCategory($query, int $feature_id)
    {
         return $query->where('feature_id', '=', $feature_id);
    }
}
