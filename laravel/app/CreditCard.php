<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 特集広告.
 */
class CreditCard extends Model
{
    use DBTrait;
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'credit_cards';
    
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

    public function getRecommendProgramListAttribute()
    {
        $program_list = collect();
        // 値が存在しない場合
        if (!isset($this->recommend_shops)) {
            return $program_list;
        }
        // プログラムIDが存在しない場合
        $program_id_list = json_decode($this->recommend_shops);
        if (empty($program_id_list)) {
            return $program_list;
        }
        $program_map = Program::ofEnable()
            ->whereIn('id', $program_id_list)
            ->get()
            ->keyBy('id')
            ->all();
        // プログラムが存在しない場合
        if (empty($program_map)) {
            return $program_list;
        }
        
        foreach ($program_id_list as $program_id) {
            // プログラムが存在しない場合
            if (!isset($program_map[$program_id])) {
                continue;
            }
            $program_list->push($program_map[$program_id]);
        }
        
        return $program_list;
    }
    
    public function scopeOfEnable($query)
    {
        $now = Carbon::now();
        return $query->where($this->table.'.status', '=', 0)
            ->where($this->table.'.stop_at', '>=', $now)
            ->where($this->table.'.start_at', '<=', $now);
    }

    public function scopeOfBrand($query, array $brand_id_list)
    {
        $mask = 0;
        foreach ($brand_id_list as $key) {
            $mask = $mask + (1 << ($key - 1));
        }
        return $query->whereRaw('credit_cards.brands & ? = ?', [$mask, $mask]);
    }

    public function scopeOfEmoney($query, array $emoney_id_list)
    {
        $mask = 0;
        foreach ($emoney_id_list as $key) {
            $mask = $mask + (1 << ($key - 1));
        }
        return $query->whereRaw('credit_cards.emoneys & ? = ?', [$mask, $mask]);
    }

    public function scopeOfInsurance($query, array $insurance_id_list)
    {
        $mask = 0;
        foreach ($insurance_id_list as $key) {
            $mask = $mask + (1 << ($key - 1));
        }
        return $query->whereRaw('credit_cards.insurances & ? = ?', [$mask, $mask]);
    }

    private function getIdList($value)
    {
        $id_list = [];
        for ($i = 1; $i <= 64; $i++) {
            if ($value >> ($i - 1) & 1 == 1) {
                $id_list[] = $i;
            }
        }
        return $id_list;
    }
    /**
     * ブランドID一覧取得.
     * @return array データ
     */
    public function getBrandIdsAttribute() : array
    {
        return $this->getIdList($this->brands);
    }

    /**
     * 電子マネーID一覧取得.
     * @return array データ
     */
    public function getEmoneyIdsAttribute() : array
    {
        $emoney_map = config('map.credit_card_emoney');
        return $this->getIdList($this->emoneys);
    }

    /**
     * 付帯保険ID一覧取得.
     * @return array データ
     */
    public function getInsuranceIdsAttribute() : array
    {
        $insurance_map = config('map.credit_card_insurance');
        return $this->getIdList($this->insurances);
    }
}
