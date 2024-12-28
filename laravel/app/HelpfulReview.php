<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 参考になった.
 */
class HelpfulReview extends Model
{
    use DBTrait;
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'helpful_reviews';
    
     /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * 参考になった追加.
     * @param int $user_id ユーザーID
     * @param int $review_id レビューID
     * @return boolean 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public static function addHelpful(int $user_id, int $review_id)
    {
        $lock_key = sprintf("review_helpful_add_%d", $review_id);
        
        return self::saveWithLock(
            $lock_key,
            function () use ($user_id, $review_id) {
                $helpful_review = new HelpfulReview();
                $helpful_review->user_id = $user_id;
                $helpful_review->review_id = $review_id;
                $helpful_review->save();
                
                $total = HelpfulReview::where('review_id', '=', $review_id)
                    ->count();
                Review::where('id', '=', $review_id)
                    ->update(['helpful_total' => $total]);
                return true;
            },
            function () use ($user_id, $review_id) {
                // 既に登録されている場合
                if (HelpfulReview::where('user_id', '=', $user_id)
                    ->where('review_id', '=', $review_id)
                    ->exists()) {
                    return false;
                }
                return true;
            }
        );
    }
}
