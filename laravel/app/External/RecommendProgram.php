<?php
namespace App\External;

use Carbon\Carbon;
use App\RecommendProgram as RecommendProgramModel;



class RecommendProgram
{
    public function getRecommendPrograms($limit = 10,$device_type = 'pc')
    {
        $programs = [];
        $list = [];
        $return  = [];
        $now = Carbon::now();
        $sql = RecommendProgramModel::leftJoin('programs', 'programs.id', '=', 'recommend_programs.program_id')
            ->where('recommend_programs.start_at', '<=', $now)
            ->where('recommend_programs.stop_at', '>=', $now)
            ->whereNull('recommend_programs.delete_at')
            ->where('programs.status', '=', 0)
            ->where('programs.start_at', '<=', $now)
            ->where('programs.stop_at', '>=', $now)
            ->whereNull('programs.deleted_at');
            if($device_type == 'pc'){
                $sql->where(function ($query) {
                    $query->where('recommend_programs.device_type', '=', 7)
                        ->orWhere('recommend_programs.device_type', '=', 1);
                });
            }
            if($device_type == 'sp'){
                $sql->where(function ($query) {
                    $query->where('recommend_programs.device_type', '=', 7)
                        ->orWhere('recommend_programs.device_type', '=', 6);
                });
            }
            
            $sql->orderBy('recommend_programs.sort', 'asc');
            //$sql->limit($limit);
            $programs = $sql->get();

        foreach ($programs as $program) {
            $list[$program->sort][] = $program->program;
        }
        foreach($list as $key => $value) {
            
            shuffle($value);
            foreach($value as $v){
                $return[] = $v;
            }
        }

        if(is_array($return) && count($return)>$limit){
            $return_list = array_slice($return,0,$limit);
        }else{
            $return_list = $return;
        }
        
        return $return_list;
    }
}