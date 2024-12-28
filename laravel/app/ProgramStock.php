<?php
namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

/**
 * プログラム予定.
 */
class ProgramStock extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'program_stocks';
    
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];
    
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
}
