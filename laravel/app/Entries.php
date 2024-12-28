<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
/**
 * 欄内容.
 */
class Entries extends Model
{
    protected $table = 'entries';
    protected $guarded = ['id'];
    public static function getDefault()
    {
        $entries = new self();
        return $entries;
    }
}