<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * ポイント.
 */
class Point extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'points';

     /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['start_at', 'stop_at', 'sale_stop_at', 'deleted_at'];

    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'time_sale' => 'boolean',
        'start_at' => 'datetime',
        'stop_at' => 'datetime',
        'sale_stop_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function scopeOfTerm($query, $date)
    {
        return $query->where($this->table.'.start_at', '<=', $date)
            ->where($this->table.'.stop_at', '>=', $date);
    }

    public function scopeOfEnable($query)
    {
        $now = Carbon::now();
        return $query->ofTerm($now)
            ->where($this->table.'.status', '=', 0)
            ->where(function ($query) use ($now) {
                $query->whereNull($this->table.'.course_id')
                    ->orWhere(function ($query) use ($now) {
                        $query->whereExists(function ($query) use ($now) {
                            $query->select('id')
                                ->from(with(new Course)->getTable())
                                ->whereColumn(with(new Course)->getTable() . '.id', $this->table.'.course_id')
                                ->where('status', '=', 0);
                        });
                    });
            });
    }

    public function getRatePercentAttribute() : ?float
    {
        if ($this->fee_type != 2) {
            return null;
        }
        return $this->rate * 100;
    }
    public function getFeeLabelSAttribute() : ?string
    {
        if ($this->fee_type == 1) {
            return number_format($this->point);
        }
        if ($this->fee_type == 2) {
            return strval(floor($this->rate_percent)).'%';
        }
        return null;
    }
    public function getFeeLabelAttribute() : ?string
    {
        return (($this->fee_type == 2) ? '購入額の' : '') . $this->fee_label_s;
    }

    public function getPreviousPointAttribute()
    {
        return Point::where('program_id', '=', $this->program_id)
            ->where('id', '<', $this->id)
            ->where('status', '=', 0)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getStopAtAttribute() : Carbon
    {
        $stop_at = Carbon::parse($this->attributes['stop_at'], config('timezone'));
        //
        if (!isset($this->attributes['sale_stop_at'])) {
            return $stop_at;
        }
        $sale_stop_at = Carbon::parse($this->attributes['sale_stop_at'], config('timezone'));
        $timesale_end_at = Carbon::now()->copy()->addDays(1);
        return $sale_stop_at->lt($timesale_end_at) ? $stop_at: $sale_stop_at;
    }

    public function getCourseAttribute() : ?Course
    {
        return Course::find($this->course_id);
    }
}
