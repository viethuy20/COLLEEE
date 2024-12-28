<?php
namespace App;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * お気に入りレシピ.
 */
class UserRecipe extends Model
{
    use DBTrait;
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'user_recipes';
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
    
    public static function getTotalMap(array $recipe_id_list)
    {
        if (empty($recipe_id_list)) {
            return [];
        }
        
        return self::ofEnable()
            ->whereIn('recipe_id', $recipe_id_list)
            ->select(DB::raw('count(*) as total, recipe_id'))
            ->groupBy('recipe_id')
            ->pluck('total', 'recipe_id')
            ->all();
    }
    
    /**
     * お気に入り追加.
     * @param int $user_id ユーザーID
     * @param int $recipe_id レシピID
     * @return boolean 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public static function addRecipe(int $user_id, int $recipe_id) : bool
    {
        $lock_key = sprintf("user_recipe_edit_%d", $user_id);

        return self::saveWithLock(
            $lock_key,
            function () use ($user_id, $recipe_id) {
                $user_program = new UserRecipe();
                $user_program->user_id = $user_id;
                $user_program->recipe_id = $recipe_id;
                $user_program->status = 0;
                $user_program->save();
                return true;
            },
            function () use ($user_id, $recipe_id) {
                // 既に登録されている場合
                if (UserRecipe::ofEnable()
                    ->where('user_id', '=', $user_id)
                    ->where('recipe_id', '=', $recipe_id)
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
     * @param int $recipe_id レシピID
     * @return boolean 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public static function removeRecipe(int $user_id, int $recipe_id)
    {
        $lock_key = sprintf("user_recipe_edit_%d", $user_id);

        return self::saveWithLock(
            $lock_key,
            function () use ($user_id, $recipe_id) {
                // お気に入り削除
                UserRecipe::ofEnable()
                    ->where('user_id', '=', $user_id)
                    ->where('recipe_id', '=', $recipe_id)
                    ->update(['status' => 1, 'deleted_at' => Carbon::now()]);
                return true;
            }
        );
    }
}
