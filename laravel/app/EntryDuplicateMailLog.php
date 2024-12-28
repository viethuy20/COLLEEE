<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class EntryDuplicateMailLog extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'entry_duplicate_mail_logs';
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    public function insertLog($mail)
    {
        $user = User::select('id')->ofEmail($mail)->first();
        if(!empty($user)){
            $duplicate_mail = new self();
            $duplicate_mail->user_id = $user->id;
            // 保存実行
            $duplicate_mail->save();
        }
    }
}