<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Device\Device;
/**
 * 欄内容.
 */
class PopupAds extends Model
{
    use SoftDeletes;
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'popup_ads';
    
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
    public function scopeOfEnable($query)
    {
        $now = Carbon::now();
        return $query->where($this->table.'.stop_at', '>=', $now)
            ->where($this->table.'.start_at', '<=', $now);
    }

    public function scopeOfEnableDevice($query)
    {
        $device_id = Device::getDeviceId();
        return $query->ofEnable()
            ->whereRaw($this->table.'.devices & ? > 0', [1 << ($device_id - 1)]);
    }
}
