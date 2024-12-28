<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * コース.
 */
class Course extends Model
{
    protected $guarded = ['id'];

    protected $date = ['deleted_at'];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
    
    public function points()
    {
        return $this->hasMany(Point::class, 'course_id', 'id');
    }
    
    public function schedules()
    {   
        return $this->hasMany(ProgramSchedule::class, 'course_id', 'id');
    }

    /**
     * ポイント取得.
     * @return array ポイント
     */
    public function getPointAttribute()
    {
        // 値を持っていた場合
        if (isset($this->appends['point'])) {
            return $this->appends['point'];
        }
        // ポイントを取得
        $point = isset($this->id) ? $this->points()->ofEnable()->get() : collect();
        // 存在しなかった場合
        if ($point->isEmpty()) {
            $point = array($this->default_point);
        }
        $this->appends['point'] = $point;
        return $point;
    }

}
