<?php
namespace App;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Device\Device;

/**
 * 特別プログラム.
 */
class SpProgram extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'sp_programs';
    
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


    /**
     * Add extra attribute.
     */
    protected $appends = ['join_status' => null];
    
    // @codingStandardsIgnoreStart
    public function user_points()
    {
        // @codingStandardsIgnoreEnd
        return $this->hasMany(UserPoint::class, 'parent_id', 'id')
            ->whereIn('type', [UserPoint::SP_PROGRAM_TYPE, UserPoint::SP_PROGRAM_WITH_REWARD_TYPE]);
    }
    
    public function scopeOfEnableDevice($query)
    {
        $device_id = Device::getDeviceId();
        return $query->ofEnable()
            ->whereRaw($this->table.'.devices & ? > 0', [1 << ($device_id - 1)]);
    }
    
    public function scopeOfEnable($query)
    {
        $now = Carbon::now();
        return $query->where($this->table.'.status', '=', 0)
            ->where($this->table.'.stop_at', '>=', $now)
            ->where($this->table.'.start_at', '<=', $now);
    }
    
    /**
     * 参加状態.
     * @return int 0:未参加,1:参加済み
     */
    public function getJoinStatusAttribute() : int
    {
        // 認証していない
        if (!Auth::check()) {
            return 0;
        }

        // 値を持っていた場合
        if (isset($this->appends['join_status'])) {
            return $this->appends['join_status'];
        }
        
        $user_id = Auth::user()->id;
        
        // ユーザーポイント取得
        $builder = $this->user_points()
            ->where('user_id', '=', $user_id);
        
        // クリックでゲットの場合、作成日時で絞る
        if ($this->category_id == 3) {
            $now = Carbon::now();
            $builder->whereBetween('created_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()]);
        }

        // 参加済みの場合
        if ($builder->exists()) {
            $this->appends['join_status'] = 1;
        } else {
            $this->appends['join_status'] = 0;
        }

        return $this->appends['join_status'];
    }
}
