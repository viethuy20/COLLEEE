<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Auth;

use App\SpProgram;
use App\UserPoint;
use App\Services\Meta;

class SpProgramsController extends Controller
{
    use ControllerTrait;
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }

    public function index() {
        // クリック取得
        $click_list = SpProgram::ofEnableDevice()
                ->where('category_id', '=', 3)
                ->orderBy('priority', 'asc')
                ->get();
        //bredcrum
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('sp_programs.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "毎日ゲット", "item": "' . $link . '"},';

        return view('sp_programs.index', ['click_list' => $click_list,'application_json' => $application_json]);
    }

    /**
     * クリック.
     * @param int $sp_program_id 特別プログラムID
     */
    public function click(int $sp_program_id) {
        $sp_program = SpProgram::ofEnable()
                ->where('id', '=', $sp_program_id)
                ->first();
        // 特別プログラム取得
        if (!Auth::check()){
            if (isset($sp_program->id) && $sp_program->sp_program_type_id == 5) {
                return redirect()->route('login', ['back' => 0]);
            }
            return redirect()->route('entries.index');
        }        
        // 特別プログラムが存在しなかった場合
        if (!isset($sp_program->id)) {
            return view('sp_programs.error');
        }
        $sp_program_data = isset($sp_program->data) ? json_decode($sp_program->data) : null;
        
        $user = Auth::user();
        
        $user_id = $user->id;
        $parent_id = $sp_program->id;

        // 未配布ではない場合
        if ($sp_program->join_status != 0) {
            return redirect($sp_program_data->url);
        }
        
        $user_point = UserPoint::getDefault($user_id, UserPoint::SP_PROGRAM_TYPE,
                0, $sp_program->point, $sp_program->title);
        $user_point->parent_id = $parent_id;

        $builder = UserPoint::where('user_id', '=', $user_id)
                ->where('type', '=', UserPoint::SP_PROGRAM_TYPE)
                ->where('parent_id', '=', $parent_id);
        // クリックでゲット
        if ($sp_program->category_id == 3) {
            $now = Carbon::now();
            $builder = $builder->whereBetween('created_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()]);
        }       
        // トランザクション処理
        $user_point->addPoint(null, function() use ($builder) {
            return !($builder->exists());
        });

        return redirect($sp_program_data->url);
    }
}
