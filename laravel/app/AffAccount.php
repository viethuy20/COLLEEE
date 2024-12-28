<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * アフィリエイトアカウント.
 */
class AffAccount extends Model
{
    use DBTrait;
    
    /** Fancrew. */
    const FANCREW_TYPE = 1;
    
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'aff_accounts';
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Add extra attribute.
     */
    protected $appends = [];

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function scopeOfType($query, int $type)
    {
        return $query->where('type', '=', $type)
            ->where('status', '=', 0);
    }
    
    /**
     * 銀行口座作成.
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function createAffAccount() :bool
    {
        $aff_account = $this;
        return self::saveWithLock(
            'create_aff_account_'.$aff_account->user_id,
            function () use ($aff_account) {
                $aff_account->save();
                return true;
            },
            function () use ($aff_account) {
                return !AffAccount::ofType($aff_account->type)
                    ->where('user_id', '=', $aff_account->user_id)
                    ->exists();
            }
        );
    }
    
    /**
     * アフィリエイトアカウント情報初期値取得.
     * @param int $type 種類
     * @param int $user_id ユーザーID
     * @param string $number アカウント番号
     * @return AffAccount アフィリエイトアカウント情報
     */
    public static function getDefault(int $type, int $user_id, string $number) : AffAccount
    {
        $aff_account = new self();
        $aff_account->type = $type;
        $aff_account->user_id = $user_id;
        $aff_account->number = $number;
        $aff_account->status = 0;
        return $aff_account;
    }
}
