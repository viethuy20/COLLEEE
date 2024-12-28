<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProgramCampaign extends Model
{
    use DBTrait;

    protected $table = 'program_campaigns';

    protected $guarded = ['id'];

    protected $date = ['deleted_at'];
    //
    /**
     * Add extra attribute.
     */
    protected $appends = [];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

}
