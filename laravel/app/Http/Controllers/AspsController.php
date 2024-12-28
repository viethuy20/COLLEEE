<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Validator;

use App\Asp;
use App\Device\Device;
use App\ExternalLink;
use App\External\Estlier;

class AspsController extends Controller
{
    use ControllerTrait;

    /**
     * クリック.
     * @param Request $request {@link Request}
     * @param int $asp_id ASP
     */
    public function click(Request $request, int $asp_id)
    {
        if (!Asp::enable()->where('asps.id', '=', $asp_id)->exists()) {
            abort(404, 'Not Found.');
        }
        if (!Auth::check()){
            return redirect()->route('entries.index');
        }
        // ユーザー情報を取得
        $user = Auth::user();

        $c_options = null;
        $e_options = null;

        // エストリエの場合
        if ($asp_id == Asp::ESTLIER_TYPE) {
            // バリデーション
            $validator = Validator::make(
                $request->all(),
                ['ganre' => ['required',],
                    'enq_date' => ['required', 'date_format:Ymd',],],
                [],
                []
            );
            // バリデーションに失敗した場合
            if ($validator->fails()) {
                abort(404, 'Not Found.');
            }

            $c_options = $request->only(['ganre', 'enq_date']);
            $e_options = ['asp_affiliate_id' => Estlier::getGanreNumber($c_options['ganre'])];
        }

        // Sansanの場合
        if ($asp_id == Asp::SANSAN_TYPE) {
            $e_options = ['asp_affiliate_id' => 'sansan-game'];
        }

        // セレスの場合
        if ($asp_id == Asp::CERES_TYPE) {
            $e_options = ['asp_affiliate_id' => 'ceresquiz'];
        }

        // メダルモール
        if ($asp_id == Asp::MEDAL_MALL_TYPE) {
            $e_options = ['asp_affiliate_id' => 'medalmall'];
        }


        // GACHA
        if ($asp_id == Asp::GACHA_TYPE) {
            $e_options = ['asp_affiliate_id' => 'gacha'];
        }

        // Brain Exercise
        if ($asp_id == Asp::BRAIN_EXERCIES) {
            $e_options = ['asp_affiliate_id' => 'brain-exercise'];
        }
        
        if ($asp_id == Asp::FARM_LIFE) {
            $e_options = ['asp_affiliate_id' => 'farm_life'];
        }

        if ($asp_id == Asp::APP_DRIVER_OW) {
            $e_options = ['asp_affiliate_id' => 'app_driver_ow'];
        }
        // クリックURL取得
        $url = Asp::getClickUrl($asp_id, $user, $c_options);
        // クリックURL作成に失敗した場合
        if (!isset($url)) {
            abort(404, 'Not Found.');
        }

        // クリックログ保存
        ExternalLink::addExternalLink(
            $url,
            $user->id,
            $asp_id,
            request()->header('User-Agent'),
            Device::getIp(),
            $e_options
        );

        // PC版のSansanの場合、自社側にページが存在する
        $device_id = Device::getDeviceId();
        if ($asp_id == Asp::SANSAN_TYPE && $device_id == 1) {
            return view('sansan', ['iframe_url' => $url]);
        }

        // リダイレクト
        return redirect($url);
    }
}
