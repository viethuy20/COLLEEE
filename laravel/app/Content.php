<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Device\Device;

/**
 * 欄内容.
 */
class Content extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'contents';
    
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
    
    /**
     * Add extra attribute.
     */
    protected $appends = [];
    
    const SPOT_PC_TOP = 1;
    const SPOT_SP_TOP = 2;
    const SPOT_SEARCH_BOX = 3;
    const SPOT_SHOP_PC_TOP = 4;
    const SPOT_SHOP_SP_TOP = 5;
    const SPOT_SHOP_SEARCH_BOX = 6;
    const SPOT_SHOP_POPULAR = 7;
    const SPOT_SHOP_PUBLICITY = 8;
    const SPOT_SHOP_CAMPAIGN = 9;
    
    const SPOT_FEATURE = 12;
    const SPOT_FEATURE_CATEGORY = 13;
    
    const SPOT_FANCREW_S = 16;
    const SPOT_FANCREW_P = 17;
    const SPOT_SHOP_STANDARD = 18;
    const SPOT_CREDIT_CARD_RECOMMEND = 19;
    const SPOT_PROGRAM_PICKUP = 20;
    const SPOT_PROGRAM_RECOMMEND = 21;
    const SPOT_BANNER_ABOVE_HEADER_PC = 22;
    const SPOT_BANNER_ABOVE_HEADER_SP = 23;
    const SPOT_MINI_BANNER_PC = 24;
    const SPOT_MINI_BANNER_SP = 25;
    const SPOT_LOWER_RANKING_BANNER = 26;

    public function scopeOfSpot($query, int $spot_id)
    {
        $device_id = Device::getDeviceId();
        $device_mask = 1 << ($device_id - 1);
        $now = Carbon::now();
        return $query->where($this->table.'.status', '=', 0)
            ->where($this->table.'.spot_id', '=', $spot_id)
            ->where($this->table.'.stop_at', '>', $now)
            ->where($this->table.'.start_at', '<=', $now)
            ->whereRaw($this->table.'.devices & ? > 0', [$device_mask])
            ->orderBy($this->table.'.priority', 'asc');
    }
    
    public function getJsonDataAttribute()
    {
        if (array_key_exists('json_data', $this->appends)) {
            return $this->appends['json_data'];
        }
        
        $this->appends['json_data'] = null;
        if (isset($this->data)) {
            $this->appends['json_data'] = json_decode($this->data);
        }
        return $this->appends['json_data'];
    }
    
    public function getProgramAttribute()
    {
        if (array_key_exists('program', $this->appends)) {
            return $this->appends['program'];
        }
        
        $this->appends['program'] = null;
        $json_data = $this->json_data;
        if (isset($json_data->program_id)) {
            $this->appends['program'] = Program::ofEnableDevice()
                ->where('id', '=', $json_data->program_id)
                ->first();
        }
        return $this->appends['program'];
    }
}
