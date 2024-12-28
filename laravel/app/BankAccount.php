<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 銀行口座.
 */
class BankAccount extends Model
{
    use DBTrait;
    
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'bank_accounts';
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
    
    /**
     * 銀行取得.
     * @return Bank|NULL 銀行
     */
    public function getBankAttribute()
    {
        // 値を持っていなかった場合
        if (array_key_exists('bank', $this->appends)) {
            return $this->appends['bank'];
        }
        // 銀行取得
        $this->appends['bank'] = Bank::ofStable()
            ->where('code', '=', $this->bank_code)
            ->first();
        return $this->appends['bank'];
    }
    
    /**
     * 銀行支店取得.
     * @return BankBranch|null 銀行支店
     */
    public function getBankBranchAttribute()
    {
        // 値を持っていなかった場合
        if (array_key_exists('bank_branch', $this->appends)) {
            return $this->appends['bank_branch'];
        }
        
        $bank = $this->getBankAttribute();
        if (isset($bank->id)) {
            // 銀行支店取得
            $this->appends['bank_branch'] = $bank->branches()
                ->where('code', '=', $this->branch_code)
                ->first();
        } else {
            $this->appends['bank_branch'] = null;
        }

        return $this->appends['bank_branch'];
    }
    
    /**
     * 銀行口座作成.
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function createBankAccount() :bool
    {
        $bank_account = $this;
        return self::saveWithLock(
            'create_bank_account_'.$bank_account->user_id,
            function () use ($bank_account) {
                // 現在銀行情報を停止
                BankAccount::where('user_id', '=', $bank_account->user_id)
                    ->where('status', '=', 0)
                    ->update(['status' => 1]);
                $bank_account->save();
                return true;
            }
        );
    }
    
    /**
     * 銀行口座情報初期値取得.
     * @param int $user_id ユーザーID
     * @param string $bank_code 銀行コード
     * @param string $branch_code 支店コード
     * @param string $number 口座番号
     * @param string $first_name 名
     * @param string $last_name 姓
     * @param string $first_name_kana 名カナ
     * @param string $last_name_kana 姓カナ
     * @return BankAccount 銀行口座情報
     */
    public static function getDefault(
        int $user_id,
        string $bank_code,
        string $branch_code,
        string $number,
        string $first_name,
        string $last_name,
        string $first_name_kana,
        string $last_name_kana
    ) : BankAccount {
        $bank_account = new self();
        $bank_account->user_id = $user_id;
        $bank_account->bank_code = $bank_code;
        $bank_account->branch_code = $branch_code;
        $bank_account->number = $number;
        $bank_account->first_name = $first_name;
        $bank_account->last_name = $last_name;
        $bank_account->first_name_kana = $first_name_kana;
        $bank_account->last_name_kana = $last_name_kana;
        $bank_account->status = 0;
        return $bank_account;
    }
}
