<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * メンテナンス.
 */
class Mainte extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'maintes';

    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['start_at', 'stop_at',];

    protected $casts = [
        'start_at' => 'datetime',
        'stop_at' => 'datetime',
    ];


    /** Fancrew種類. */
    const FANCREW_TYPE = 1;
    /** エストリエ種類. */
    const ESTLIER_TYPE = 2;
    /** CERES種類. */
    const CERES_TYPE = 3;
    /** SANSAN種類. */
    const SANSAN_TYPE = 4;
    /** まいにちクイズボックス */
    const EASY_GAME_BOX_QUIZ = 5;
    /** かんたんゲームボックス */
    const EASY_GAME_BOX_GAME = 6;
    /** 運だめし　スロットボックス */
    const EASY_GAME_BOX_SLOT = 7;
    /** ふるふるサファリ*/
    const FRUFUL = 8;
    /** ガチャコンテンツ*/
    const GACHA_TYPE = 9;

    /** 頭の体操*/
    const BRAIN_EXERCIES = 10;

    /** FARM LIFE */
    const FARM_LIFE = 9;

    /** GAME BOX SPOT */
    const EASY_GAME_BOX_SPOT = 11;


    public function scopeOfType($query, int $type)
    {
        $now = Carbon::now();
        return $query->where('type', '=', $type)
            ->where('status', '=', 0)
            ->where('stop_at', '>=', $now)
            ->where('start_at', '<=', $now)
            ->orderBy('start_at', 'desc')
            ->orderBy('id', 'desc');
    }
}
