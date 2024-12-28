<?php
namespace App;

use Carbon\Carbon;
use \DB;
use Illuminate\Database\Eloquent\Model;

/**
 * お気に入り.
 */
class UserProgram extends Model
{
    use DBTrait;
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'user_programs';
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    
    public function scopeOfEnable($query)
    {
        return $query->where('status', '=', 0);
    }
    
    /**
     * お気に入り追加.
     * @param int $user_id ユーザーID
     * @param int $program_id プログラムID
     * @return boolean 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public static function addProgram(int $user_id, int $program_id) : bool
    {
        $lock_key = sprintf("user_program_edit_%d", $user_id);

        return self::saveWithLock(
            $lock_key,
            function () use ($user_id, $program_id) {
                $user_program = new UserProgram();
                $user_program->user_id = $user_id;
                $user_program->program_id = $program_id;
                $user_program->status = 0;
                $user_program->save();
                return true;
            },
            function () use ($user_id, $program_id) {
                // 既に登録されている場合
                if (UserProgram::ofEnable()
                    ->where('user_id', '=', $user_id)
                    ->where('program_id', '=', $program_id)
                    ->where('status', '=', 0)
                    ->exists()) {
                    return false;
                }
                return true;
            }
        );
    }
    
    /**
     * お気に入り削除.
     * @param int $user_id ユーザーID
     * @param int $program_id プログラムID
     * @return boolean 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public static function removeProgram(int $user_id, int $program_id)
    {
        $lock_key = sprintf("user_program_edit_%d", $user_id);

        return self::saveWithLock(
            $lock_key,
            function () use ($user_id, $program_id) {
                // お気に入り削除
                UserProgram::ofEnable()
                    ->where('user_id', '=', $user_id)
                    ->where('program_id', '=', $program_id)
                    ->update(['status' => 1, 'deleted_at' => Carbon::now()]);
                return true;
            }
        );
    }
}
