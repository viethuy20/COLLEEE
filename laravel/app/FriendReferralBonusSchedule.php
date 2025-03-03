<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
 * 友達紹介報酬スケジュール
 */
class FriendReferralBonusSchedule extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'friend_referral_bonus_schedule';

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

    protected $casts = [
        'start_at' => 'datetime',
        'stop_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    public function scopeEnable($query)
    {
        return $query->whereNull($this->table.'.deleted_at');
    }

    public function scopeGetDate($query, $date)
    {
        return $query->where($this->table.'.start_at', '<=', $date)
        ->where($this->table.'.stop_at', '>=', $date);
    }

    public function scopeOrderByDescId($query)
    {
        return $query->orderByDesc($this->table.'.id');
    }

}
