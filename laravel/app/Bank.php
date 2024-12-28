<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 銀行.
 */
class Bank extends Model
{
    use DBTrait;
    
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'banks';
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];
    
    public function branches()
    {
        return $this->hasMany(BankBranch::class, 'bank_id', 'id');
    }
    
    /**
     * 手数料全額取得.
     * @return int手数料全額
     */
    public function getFullChargeAttribute() :int
    {
        $bank_charge = config('bonus.bank')[0];
        if (isset($bank_charge[$this->code])) {
            return $bank_charge[$this->code];
        }
        return $bank_charge['default'];
    }
    
    public function scopeOfStable($query)
    {
        $version = Bank::orderBy('version', 'asc')->value('version');
        return $query->where('version', '=', $version)
            ->orderBy('hurigana_index', 'asc');
    }
    
    /**
     * 手数料取得.
     * @param User $user ユーザー
     * @return int 手数料
     */
    public function getCharge(User $user) :int
    {
        $bank_charge = config('bonus.bank')[$user->rank];
        if (isset($bank_charge[$this->code])) {
            return $bank_charge[$this->code];
        }
        return $bank_charge['default'];
    }
}
