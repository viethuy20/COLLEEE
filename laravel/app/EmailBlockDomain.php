<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

/**
 * メールアドレスブロックドメイン情報.
 */
class EmailBlockDomain extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'email_block_domains';

     /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    public static function checkEmail(string $email) : bool
    {
        // メールアドレスの書式を確認
        $validator = \Validator::make(
            ['email' => email_quote($email)],
            ['email' => ['required', 'email'],]
        );
        if ($validator->fails()) {
            // 書式エラーもブロックとして扱う
            return false;
        }

        $p_email = email_unquote($email);
        $parsed_email = explode('@', $p_email);
        $email_domain = array_pop($parsed_email);

        return !self::whereRaw(
            "(domain = :domain1 or :domain2 like concat('%.', domain))",
            ['domain1' => $email_domain, 'domain2' => $email_domain]
        )->exists();
    }
}
