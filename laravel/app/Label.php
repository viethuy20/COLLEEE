<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'labels';

    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Add extra attribute.
     */
    protected $appends = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('label_list', function (Builder $builder) {
            $builder->orderBy('priority', 'asc')
                ->orderBy('id', 'asc')
                ->where('status', '=', 0);
        });
    }
}
