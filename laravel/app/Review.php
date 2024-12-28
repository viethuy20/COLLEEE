<?php
namespace App;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const ACCEPTED = 0;
    const DENY = 1;
    const PENDING = 2;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
    
    public function helpfuls()
    {
        return $this->hasMany(HelpfulReview::class, 'review_id', 'id');
    }

    public function scopeOfEnable($query)
    {
        return $query->where($this->table.'.status', '=', 0);
    }

    /**
     * 口コミ一覧取得(プログラムID指定)
     * @param int $program_id プログラムID
     */
    public function scopeOfProgram($query, $program_id)
    {
        return $query->ofEnable()
            ->where('program_id', '=', $program_id);
    }
    
    /**
     * 口コミ一覧取得(ユーザーID指定)
     * @param int $user_id ユーザーID
     */
    public function scopeOfUser($query, $user_id)
    {
        return $query->ofEnable()
            ->where('user_id', '=', $user_id);
    }
    
    /**
     * 参考になった.
     */
    public function scopeOfHelpful($query)
    {
        return $query->where('helpful_total', '>', 0);
    }
    
    /**
     * ポイント配布チェック.
     * @param type $query
     * @param int $program_id プログラムID
     * @param int $user_id ユーザーID
     */
    public function scopeOfPostCheck($query, int $program_id, int $user_id)
    {
        return $query->ofProgram($program_id)
            ->ofUser($user_id)
            ->whereNotNull('pointed_at');
    }

    public function scopeOfSort($query, $type)
    {
        switch ($type) {
            case 1:
                // 参考になった数順
                $query = $query->orderBy('helpful_total', 'desc');
                break;
            case 2:
                // 評価順
                $query = $query->orderBy('assessment', 'desc');
                break;
            default:
                // 新着順
                break;
        }
        return $query->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }
    
    /**
     * 口コミ一覧取得.
     * @param array|NULL $label_id_list ラベルID一覧
     */
    public function scopeOfEnableLabel($query, $label_id_list = null)
    {
        return $query->ofEnable()
            ->whereIn('program_id', function ($query) use ($label_id_list) {
                $now = Carbon::now();
                
                $query->select('id')
                    ->from('programs')
                    ->where('status', '=', 0)
                    ->where('stop_at', '>=', $now)
                    ->where('start_at', '<=', $now);
                // ラベル検索が必要な場合
                if (!empty($label_id_list)) {
                    $query->whereIn('id', function ($query) use ($label_id_list) {
                        $query->select('program_id')
                            ->from('program_labels')
                            ->where('status', '=', 0)
                            ->whereIn('label_id', $label_id_list);
                    });
                }
            });
    }

    /**
     * 世代取得.
     * @return int 世代
     */
    public function getGenerationAttribute()
    {
        $generation = $this->attributes['generation'];
        $generation_keys = array_keys(config('map.generation'));
        $generation = max($generation, min($generation_keys));
        $generation = min($generation, max($generation_keys));

        return $generation;
    }
}
