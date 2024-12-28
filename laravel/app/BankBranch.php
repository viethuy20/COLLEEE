<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 銀行支店.
 */
class BankBranch extends Model
{
    use DBTrait;
    
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'bank_branchs';
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];
    
    public function scopeOfStable($query)
    {
        $version = BankBranch::orderBy('version', 'asc')->value('version');
        return $query->where('version', '=', $version);
    }
}
