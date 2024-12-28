<?php
namespace App\External;
use Illuminate\Support\Facades\Cookie;

class History
{
    /**
     * Cookieから取得したprogram_idをもとにProgramデータを取得
     * 
     * @param int $num
     * @return array
     */
    public static function getProgramHistoriesData($num = 10): array {
        $program_histories = self::getProgramHistories($num);
        if ($program_histories) {
            $history_program_list = \App\Program::ofEnable()
                ->whereIn('id', $program_histories)
                ->take($num)
                ->get();
            foreach ($history_program_list as $history_program) {
                $program_histories[$history_program->id] = $history_program;
            }
            foreach ($program_histories as $k => $v) {
                if (!is_object($v)) {
                    unset($program_histories[$k]);
                }
            }
        }
        // if ($program_histories) {
        //     foreach ($program_histories as $ph) {
        //         \Log::error("## Results of History Data: " . var_export($ph->id . "::" . $ph->title, true));
        //     }
        // } else {
        //     \Log::error("## Results of History Data: " . var_export($program_histories, true));
        // }
        return $program_histories;
    }

    /**
     * Cookieからprogram_idを取得
     * 
     * @param int $num
     * @return array
     */
    private static function getProgramHistories($num = 10): array {
        try {
            $user_id = md5(\Auth::user()->id);

            $history_counted = [];
            $history = request()->cookie('program_history');
            $history = json_decode($history, true) ?? [];
            // \Log::error("## Get Cookie: " . var_export($history, true));
            if (isset($history[$user_id])) {
                $i = 0;
                foreach ($history[$user_id] as $k => $v) {
                    if ($i < $num) {
                        $history_counted[$k] = $v;
                    }
                    $i++;
                }
            } else {
                Cookie::queue(Cookie::forget('program_history'));
            }
            return $history_counted;
        } catch (\Exception $e) {
            Cookie::queue(Cookie::forget('program_history'));
            return [];
        }
    }

    /**
     * Cookieにprogram_idをセット
     * 
     * @param int $program_id
     * @return bool
     */
    public static function setProgramHistories($program_id): bool {
        $num = 9; // 最大10件を保存
        $expire = 60 * 24 * 30; // minutes
        try {
            $user_id = md5(\Auth::user()->id);

            $program_histories = self::getProgramHistories($num);
            // 今回のprogram_idと重複する分があれば削除
            $program_histories_cookie[$user_id] = [];
            foreach ($program_histories as $program_history_id) {
                if ($program_history_id != $program_id) {
                    $program_histories_cookie[$user_id][$program_history_id] = $program_history_id;
                }
            }
            // 今回のprogram_idを先頭に追加
            $program_histories_cookie[$user_id] = [$program_id => $program_id] + $program_histories_cookie[$user_id];
            // \Log::error("## Set Cookie: " . var_export($program_histories_cookie, true));
            Cookie::queue('program_history', json_encode($program_histories_cookie), $expire);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
