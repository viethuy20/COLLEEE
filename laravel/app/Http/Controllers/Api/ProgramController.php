<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProgramRequest;
use App\Program;
use Illuminate\Support\Facades\Cache;

class ProgramController extends Controller
{

    public function __construct()
    {
        $this->middleware('throttle:360,1');
    }

    public function search(ProgramRequest $request)
    {
        $base_url = config('app.url');;
        $query = Program::ofEnable();

        if ($request->has('ids')) {
            $req_ids = $request->input('ids');
            $ids = explode(',', $req_ids);    
            $query = $query->whereIn('id', $ids);
        }

        $cache_key = 'programs_' . md5(json_encode($request->all()));
        $programs = Cache::remember($cache_key, 60, function () use ($query, $request, $base_url) {
            return $query
                ->offset($request->input('offset') * $request->input('count'))
                ->limit($request->input('count'))
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($program) use ($base_url) {
                    $point = $program->point;
                    if ($point->fee_type == 1) {
                        $point_value = $point->point;
                    }
                    if ($point->fee_type == 2) {
                        $point_value= $point->rate_percent;
                    }               
                    return [
                        'title' => $program->title,
                        'description' => $program->description,
                        'url' => "{$base_url}/programs/{$program->id}",
                        'image_url' => $program->affiriate->img_url,
                        'fee_type' => $point->fee_type,
                        'fee_condition' => $program->fee_condition,
                        'point' => $point_value,
                    ];    
                });
        });

        $response = [
            'result' => [
                'programs' => $programs,
            ]
        ];
                
        return response()->json($response, 200);
    }
}