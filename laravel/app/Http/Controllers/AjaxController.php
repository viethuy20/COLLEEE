<?php
namespace App\Http\Controllers;

use App\Program;
use App\Search\ProgramCondition;
use Auth;
use Illuminate\Http\Request;

use App\UserRecipe;

class AjaxController extends Controller
{
    /**
     * ユーザー情報.
     */
    public function user()
    {
        $user = Auth::user();
        
        if (!isset($user->id)) {
            return response()->json((object)['status' => 1]);
        }
        
        return response()->json((object)['status' => 0, 'User' => (object)[
            'name' => $user->name, 'nickname' => $user->nickname, 'point' => $user->point,
            'new_message_total' => 0]]);
    }
    
    /**
     * レシピ一覧.
     * @param Request $request {@link Request}
     */
    public function recipeList(Request $request)
    {
        $recipe_ids = $request->input('recipe_ids');
        
        if (!isset($recipe_ids)) {
            return response()->json((object)['status' => 0, 'RecipeList' => []]);
        }
        
        $recipe_id_list = explode(',', $recipe_ids);
        
        $exists_recipe_id_list = [];
        $user = Auth::user();
        if (isset($user->id)) {
            $exists_recipe_id_list = $user->user_recipes()
                ->whereIn('recipe_id', $recipe_id_list)
                ->pluck('recipe_id')
                ->all();
        }
        
        $recipe_total_map = UserRecipe::getTotalMap($recipe_id_list);
        
        $user_recipe_list = [];
        foreach ($recipe_id_list as $recipe_id) {
            $user_recipe_list[] = (object)['UserRecipe' => (object)['recipe_id' => $recipe_id,
                'has' => in_array($recipe_id, $exists_recipe_id_list),
                'total' => $recipe_total_map[$recipe_id] ?? 0]];
        }
        
        return response()->json((object)['status' => 0, 'UserRecipeList' => $user_recipe_list]);
    }

    /**
     * サービスプログラム一覧.
     *　@param Request $request {@link Request}
     */
    public function serviceList(Request $request)
    {
        $keyword_list = ['rakuten' => '楽天サービス', 'yahoo' => 'Yahoo!サービス', 'docomo' => 'docomoサービス', 'au' => 'auサービス'] ;
        
        $now = date('Y-m-d H:i:s');

        $service_list = [];
        foreach ($keyword_list as $key => $keyword) {
            // Attributeの一貫性を保つためにhasManyを利用（パフォーマンスが悪ければ改良）
            $program_list = Program::ofEnable()
                ->ofKeyword([$keyword])
                ->take(8)
                ->get(['id', 'title', 'description', 'fee_condition']);
            
            if ($program_list->isEmpty()) continue;
            
            $service_program_list = $program_list->map(function ($program) {
                $progam_data = $program->toArray();
                $point = $program->point;
                $affiriate = $program->affiriate->toArray();
                $pointa_data = ['fee_type' => $point->fee_type, 'fee_label' => $point->fee_label];
                $affiriate_data = empty($affiriate) ? [] : ['img_url' => $affiriate['img_url']];

                return array_merge(
                    $progam_data,
                    ['affiriate' => $affiriate_data],
                    ['point' => $pointa_data]
                );
            });

            $service_link = ProgramCondition::getStaticListUrl((object) ['keyword_list' => [$keyword]]);

            $service_list[] = ["key" => $key, 'name' => $keyword, 'program_list' => $service_program_list, 'service_link' => $service_link];
        }

        return response()->json((object)['status' => 0, 'service_list' => $service_list]);
    }

    /**
     * 特集一覧.
     * @param Request $request {@link Request}
     */
    public function featureList(Request $request) {
        $feature_contents = \App\Content::ofSpot(\App\Content::SPOT_FEATURE)
            ->orderBy('start_at', 'asc')
            ->limit(3)
            ->get();
        
        $feature_content_list = $feature_contents->map(function ($content) {
            $content_data = $content->json_data;
            return (object)['id' => $content->id, 'title' => $content->title, 'url' => $content_data->url, 'img_url' => $content_data->img_url];
        });

        return response()->json((object)['status' => 0, 'feature_content_list' => $feature_content_list]);
    }
}
