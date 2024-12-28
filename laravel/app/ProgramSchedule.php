<?php
namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

/**
 * プログラム予定.
 */
class ProgramSchedule extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'program_schedules';
    
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
    
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
    
    public function scopeOfEnable($query)
    {
        $now = Carbon::now();
        return $query->where($this->table.'.start_at', '<=', $now)
                ->where($this->table.'.stop_at', '>=', $now)
                ->where(function ($query) {
                    $query->whereNull($this->table.'.course_id')
                        ->orWhere(function ($query) {
                            $query->whereRaw("
                                ($this->table.program_id, $this->table.course_id) IN (
                                    SELECT 
                                        courses.program_id, 
                                        MIN(courses.id) AS course_id 
                                    FROM 
                                        courses 
                                    WHERE 
                                        courses.status = 0 
                                    GROUP BY 
                                        courses.program_id
                                )
                            ");
                        });
                });
    }
}
