<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * ユーザーポイント.
 */
class UserPoint extends Model
{
    use DBTrait;

    /** 組戻し. */
    const ROLLBACK_TYPE = 1;
    /** 広告. */
    const PROGRAM_TYPE = 2;
    /** モニター. */
    const MONITOR_TYPE = 3;
    /** アンケート. */
    const QUESTION_TYPE = 4;
    /** 口コミ. */
    const REVIEW_TYPE = 6;
    /** 管理者. */
    const ADMIN_TYPE = 7;
    /** 金融機関振込. */
    const BANK_TYPE = 8;
    /** 電子マネー交換. */
    const EMONEY_TYPE = 9;
    /** ギフトコード交換. */
    const GIFT_CODE_TYPE = 10;
    /** 他社ポイント交換. */
    const OTHER_POINT_TYPE = 11;
    /** 旧広告. */
    const OLD_PROGRAM_TYPE = 13;
    /** 特別広告. */
    const SP_PROGRAM_TYPE = 14;
    /** 成果あり特別広告. */
    const SP_PROGRAM_WITH_REWARD_TYPE = 15;
    /** ポイントボックス. */
    const POINTBOX_TYPE = 16;

    /** 誕生日ボーナス. */
    const BIRTYDAY_BONUS_TYPE = 20;
    /** 広告ボーナス. */
    const PROGRAM_BONUS_TYPE = 21;
    /** お友達紹介ボーナス. */
    const ENTRY_BONUS_TYPE = 22;
    /** ゲーム. */
    const GAME_BONUS_TYPE = 23;

    /** アフィリエイト成果グループ. */
    const AFF_GROUP_TYPE = 1;
    /** 他の成果グループ. */
    const OTHER_GROUP_TYPE = 2;

    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'user_points';
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    public function scopeOfReward($query, int $type)
    {
        $group = [self::PROGRAM_TYPE, self::MONITOR_TYPE, self::OLD_PROGRAM_TYPE];
        $not_group = array_merge($group, [self::ROLLBACK_TYPE]);
        $query = $type == self::AFF_GROUP_TYPE ? $query->whereIn('type', $group) :
            $query->whereNotIn('type', $not_group);
        return $query->where(function ($query) {
            $query->orWhere('diff_point', '>', 0)
            ->orWhere('bonus_point', '>', 0);
        });
    }

    public function getProgramAttribute()
    {
        // プログラムではない場合
        if ($this->type != self::PROGRAM_TYPE) {
            return null;
        }
        return Program::find($this->parent_id);
    }

    /**
     * 初期値を取得.
     * @param int $user_id ユーザーID
     * @param int $type 種類
     * @param int $diff_point 差分ポイント
     * @param int $bonus_point ボーナスポイント
     * @param string $title タイトル
     * @return UserPoint
     */
    public static function getDefault(
        int $user_id,
        int $type,
        int $diff_point,
        int $bonus_point,
        string $title
    ) : UserPoint {
        $user_point = new self();
        $user_point->user_id = $user_id;
        $user_point->type = $type;
        $user_point->diff_point = $diff_point;
        $user_point->bonus_point = $bonus_point;
        $user_point->title = $title;
        return $user_point;
    }

    /**
     * 最後のポイント情報を登録.
     */
    private function setLastPoint()
    {
        // 最後のポイント更新を取得
        $last_point = self::where('user_id', '=', $this->user_id)
            ->orderBy('id', 'desc')
            ->first();

        if (isset($last_point->id)) {
            $this->point = $last_point->point;
            $this->exchanged_point = $last_point->exchanged_point;
        } else {
            $this->point = 0;
            $this->exchanged_point = 0;
        }
        $this->point = $this->point + $this->diff_point + $this->bonus_point;
        // 交換の場合
        if ($this->diff_point < 0) {
            $this->exchanged_point = $this->exchanged_point - $this->diff_point;
        }
    }

    /**
     * ポイントロック.
     * @param type $save_func 保存関数
     * @param type $check_func 検証関数
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function lockPoint($save_func = null, $check_func = null) : bool
    {
        return $this::saveWithLock(
            sprintf("user_point_%d", $this->user_id),
            function () use ($save_func) {
                return $save_func();
            },
            $check_func
        );
    }

    /**
     * ポイント更新.
     * @param type $check_func 検証関数
     * @param type $next_check_func 検証関数2
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function addPoint($check_func = null, $next_check_func = null) : bool
    {
        $user_point = $this;
        return $this::lockPoint(
            function () use ($user_point, $next_check_func) {
                // 関数が存在した場合は実行して、関数の戻り値がfalseの場合は正常終了する
                if (isset($next_check_func) && !($next_check_func())) {
                    return true;
                }

                $user_point->setLastPoint();
                if ($user_point->point < 0) {
                    return false;
                }
                // 保存実行
                $user_point->save();

                // ユーザー更新日時更新
                $now = Carbon::now();
                $today = $now->copy()->startOfDay();

                User::where('id', '=', $user_point->user_id)
                    ->where(function ($query) use ($today) {
                        $query->orWhereNull('actioned_at')
                            ->orWhere('actioned_at', '<', $today);
                    })
                    ->update(['actioned_at' => $now]);
                return true;
            },
            $check_func
        );
    }

    /**
     * ポイント組戻し.
     * @param int $admin_id 更新管理者ID
     * @param string $title タイトル
     * @param type $check_func 検証関数
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function rollbackPoint(int $admin_id, string $title, $check_func = null) : bool
    {
        // 交換処理ではない場合、組戻しをさせない
        if ($this->diff_point >= 0) {
            return false;
        }
        $user_point = $this;
        return self::lockPoint(

            function () use ($admin_id, $user_point, $title) {
                // 既に組戻されているか確認して、組戻しが発生していた場合は終了
                $exist = UserPoint::where('type', '=', $this::ROLLBACK_TYPE)
                        ->where('parent_id', '=', $user_point->id)
                        ->exists();
                if ($exist) {
                    return false;
                }

                // 組戻しを実行
                $next_user_point = UserPoint::getDefault(
                    $user_point->user_id,
                    UserPoint::ROLLBACK_TYPE,
                    -$this->diff_point,
                    0,
                    $title
                );
                $next_user_point->parent_id = $user_point->id;
                $next_user_point->admin_id = $admin_id;
                $next_user_point->setLastPoint();
                $next_user_point->exchanged_point = $next_user_point->exchanged_point - $next_user_point->diff_point;
                $next_user_point->save();
                return true;
            },
            $check_func
        );
    }


    /**
     * ロックして保存.
     * @param string $lock_key ロックキー
     * @param type $save_func 保存式
     * @param type $check_func 検証関数
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public static function saveWithLock(string $lock_key, $save_func, $check_func = null) : bool
    {
        // ロックに失敗した場合
        if (!self::lockString($lock_key)) {
            return false;
        }
        
        $res = true;
        try {
            // トランザクション処理
            DB::transaction(function () use ($save_func, $check_func) {
                // 関数が存在した場合は実行して、関数の戻り値がfalseの場合は終了する
                if (isset($check_func) && !($check_func())) {
                    // ロールバック
                    throw new RollbackException('Rollback');
                }
                $res = $save_func();
                if (!$res) {
                    // ロールバック
                    throw new RollbackException('Rollback');
                }
                // 登録実行
                return true;
            });
        } catch (RollbackException $e) {
            // ロールバック
            $res = false;
        } catch (\Throwable $e) {
            // ロック解除
            self::unlockString($lock_key);
            throw $e;
        }
        
        // ロック解除
        self::unlockString($lock_key);
        return $res;
    }

    /**
     * 文字列ロック解除.
     * @param string $key 文字列
     * @return boolean 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public static function unlockString(string $key = 'default_lock') : bool
    {
        // ロックSQL実行
        $data = DB::select("SELECT RELEASE_LOCK(?) AS 'lock';", [$key]);
        // ロック状態を返す
        return isset($data[0]->lock) && !empty($data[0]->lock);
    }

}
