<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

/**
 * アフィリエイト.
 */
class Affiriate extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'affiriates';

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

    /** 広告. */
    const PROGRAM_TYPE = 1;
    /** 特別広告. */
    const SPROGRAM_TYPE = 2;

    

    /**
     * 配列／JSONシリアライズのためデータを準備する
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function asp()
    {
        return $this->belongsTo(Asp::class, 'asp_id', 'id');
    }
    // @codingStandardsIgnoreStart
    public function aff_rewards()
    {
        // @codingStandardsIgnoreEnd
        return $this->hasMany(AffReward::class, 'affiriate_id', 'id');
    }

    public function getProgramAttribute() :?Program
    {
        // 広告ではない場合
        if ($this->parent_type != self::PROGRAM_TYPE) {
            return null;
        }
        return Program::find($this->parent_id);
    }

    public function scopeOfEnable($query)
    {
        $now = Carbon::now();
        return $query->where($this->table.'.status', '=', 0)
            ->where($this->table.'.start_at', '<=', $now)
            ->where($this->table.'.stop_at', '>=', $now);
    }
}
