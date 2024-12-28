<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Content;
use App\FeatureProgram;
use App\FeatureSubCategory;
use App\Services\Meta;

class FeaturesController extends Controller
{
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * 一覧.
     */
    public function index()
    {
        // 特集カテゴリ取得
        $feature_category_list = Content::ofSpot(Content::SPOT_FEATURE_CATEGORY)
            ->orderBy('id', 'asc')
            ->get();
        // プログラムが存在しなかった場合
        if ($feature_category_list->isEmpty()) {
            abort(404, 'Not Found.');
        }

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('features.index');
        $application_json .= '{"@type": "ListItem","position":' . $position . ', "name": "特集一覧", "item": "' . $link . '"}';

        return view('features.index', [
            'feature_category_list' => $feature_category_list, 
            'application_json' => $application_json
        ]);
    }

    /**
     * 詳細.
     * @param int $feature_id 特集カテゴリID
     */
    public function show(int $feature_id)
    {
        // 特集カテゴリ取得
        $feature_category = Content::ofSpot(Content::SPOT_FEATURE_CATEGORY)
            ->where('id', '=', $feature_id)
            ->firstOrFail();

        $feature_category_data = json_decode($feature_category->data);

        // 特集サブカテゴリ取得
        $feature_sub_category_list = FeatureSubCategory::ofCategory($feature_id)
            ->orderBy('priority', 'asc')
            ->get();

        // 特集広告取得
        $feature_program_list = FeatureProgram::ofCategory($feature_id)
            ->where('status', '=', 0)
            ->orderBy('priority', 'asc')
            ->get()
            ->mapToGroups(function ($item) {
                $now = Carbon::now();
                if (!$item['program'] || !$item['program']->is_enable) {
                    return [];
                }
                return [($item['sub_category_id'] ?? 0) => $item];
            });

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('features.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "特集一覧", "item": "' . $link . '"},';
        $position++;
        $link = route('features.show', ['feature_id' => $feature_id]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $feature_category->title . '", "item": "' . $link . '"}';

        return view('features.show', ['feature_category' => $feature_category,
            'feature_category_data' => $feature_category_data,
            'feature_sub_category_list' => $feature_sub_category_list,
            'feature_program_list' => $feature_program_list,
            'application_json' => $application_json
        ]);
    }
}
