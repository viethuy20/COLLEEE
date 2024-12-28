<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramLabel extends Model
{
    public function scopeOfEnable($query)
    {
        return $query->where('program_labels.status', '=', 0);
    }
}
