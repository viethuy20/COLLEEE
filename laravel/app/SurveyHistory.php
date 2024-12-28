<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyHistory extends Model
{
    protected $table = 'survey_histories';
    
    protected $fillable = [
        'user_id',
        'order_id',
        'title',
        'media_id',
        'point',
        'answered_at'
    ];
    
}
