<?php
namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * アフィリエイト成果.
 */
class AffReward extends Model
{
    use DBTrait;

    /** 書式. */
    const FORMAT_ERROR_CODE = 1;
    /** ユーザーが存在しない. */
    const USER_NOT_EXIST_CODE = 2;
    /** アフィリエイトが存在しない. */
    const AFFIRIATE_NOT_EXIST_CODE = 3;

    /** 配布済み状態. */
    const REWARDED_STATUS = 0;
    /** キャンセル状態. */
    const CANCELED_STATUS = 1;
    /** 配布待ち状態. */
    const WAITING_STATUS = 2;
    /** 異常状態. */
    const ERROR_STATUS = 3;
    /** 発生状態. */
    const ACTIONED_STATUS = 4;
    /** 自動キャンセル状態. */
    const AUTO_CANCELED_STATUS = 5;

    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'aff_rewards';
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['actioned_at', 'status_updated_at', 'confirmed_at'];

    protected $casts = [
        'actioned_at' => 'datetime',
        'status_updated_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    /**
     * モデルの「初期起動」メソッド
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderLatest', function (Builder $builder) {
            $builder->orderBy('actioned_at', 'desc')
                ->orderBy('created_at', 'desc');
        });
    }

    public function affiriate()
    {
        return $this->belongsTo(Affiriate::class, 'affiriate_id', 'id');
    }

    public function scopeOfWaiting($query)
    {
        return $query->whereIn('status', [self::WAITING_STATUS, self::ACTIONED_STATUS]);
    }

    public function getTitleAttribute() : string
    {
        if (!is_null($this->attributes['course_name']))
        {
            return $this->attributes['title']. ' ('. $this->attributes['course_name']. ')';
        }
        return $this->attributes['title'];
    }
}
