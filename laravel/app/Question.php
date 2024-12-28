<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * アンケート.
 */
class Question extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'questions';
    
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['start_at', 'stop_at', 'deleted_at'];

    protected $casts = [
        'start_at' => 'datetime',
        'stop_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    /**
     * Add extra attribute.
     */
    protected $appends = ['answer_total' => null, 'message_total' => null];
    
    // @codingStandardsIgnoreStart
    public function user_answers()
    {
        // @codingStandardsIgnoreEnd
        return $this->hasMany(UserAnswer::class, 'question_id', 'id')
            ->orderBy('updated_at', 'desc');
    }
    
    /**
     * 回答マップ取得.
     * @return array 回答マップ
     */
    public function getAnswerMapAttribute() : array
    {
        $answer_list = json_decode($this->answers);
        $answer_map = [];
        foreach ($answer_list as $answer) {
            $answer_map[$answer->id] = $answer->label;
        }
        return $answer_map;
    }
    
    /**
     * 回答数取得.
     * @return int 回答数
     */
    public function getAnswerTotalAttribute() : int
    {
        // 値を持っていなかった場合
        if (isset($this->appends['answer_total'])) {
            return $this->appends['answer_total'];
        }
        // 回答数取得
        $this->appends['answer_total'] = $this->user_answers()->count();
        return $this->appends['answer_total'];
    }
    
    /**
     * メッセージ数取得.
     * @return int メッセージ数
     */
    public function getMessageTotalAttribute() : int
    {
        // 値を持っていなかった場合
        if (isset($this->appends['message_total'])) {
            return $this->appends['message_total'];
        }
        // 回答数取得
        $this->appends['message_total'] = $this->user_answers()
            ->where('status', '=', 0)
            ->count();
        return $this->appends['message_total'];
    }
    
    public function scopeOfEnable($query)
    {
        return $query->where($this->table.'.status', '=', 0);
    }
    
    public function scopeOfEnableAnswer($query)
    {
        $now = Carbon::now();
        return $query->ofEnable()
            ->where($this->table.'.stop_at', '>', $now)
            ->where($this->table.'.start_at', '<=', $now);
    }

    /**
     * 回答登録.
     * @param int $user_id ユーザーID
     * @param int $answer_id 回答ID
     */
    public function addAnswer(int $user_id, int $answer_id)
    {
        $user_answer = new UserAnswer();
        $user_answer->user_id = $user_id;
        $user_answer->question_id = $this->id;
        $user_answer->answer_id = $answer_id;
        $user_answer->status = 2;
        
        $user_point = UserPoint::getDefault(
            $user_id,
            UserPoint::QUESTION_TYPE,
            1,
            0,
            $this->start_at->format('Ymd').' デイリーアンケート'
        );
        $user_point->parent_id = $user_answer->question_id;
        
        // トランザクション処理
        return $user_point->addPoint(function () use ($user_answer) {
            // 保存実行
            $user_answer->save();
            return true;
        });
    }
}
