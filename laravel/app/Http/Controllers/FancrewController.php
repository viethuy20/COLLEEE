<?php
namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\AffAccount;
use App\Device\Device;
use App\Mainte;

class FancrewController extends Controller
{
    /**
     * Fancrewページ処理.
     */
    public function index($action = null, $ext = null)
    {
        $device_id = Device::getDeviceId();

        if ($device_id != 1) {
            return view('fancrew.mainte');
        }

        $mainte = Mainte::ofType(Mainte::FANCREW_TYPE)->first();
        if (isset($mainte)) {
            throw new \App\Exceptions\MaintenanceException($mainte->message);
        }

        require_once base_path('fancrew/app/class/require.app.php');
        require_once base_path('fancrew/app/class/ROI/Fancrew/SiteCooperation/PcController.php');
        $controller = new \ROI_Fancrew_SiteCooperation_PcController();
        $controller->exec();
        return $controller->getResponse();
    }

    /**
     * アカウント作成.
     * @param Request $request {@link Request}
     */
    public function createAccount(Request $request)
    {
        $url = $request->input('url');

        $user = Auth::user();
        // すでに登録済みの場合
        if (isset($user->fancrew_account_number)) {
            return isset($url) ? redirect($url) : redirect()->back();
        }

        //
        $this->validate(
            $request,
            [
                'gender' => ['required', 'in:0,1',],
                'birthday' => ['required', 'date_format:"Ymd"'],
            ],
            [
                'birthday.date_format' => ':attributeが不正です',
            ],
            [
                'gender' => '性別',
                'birthday' => '誕生日',
            ]
        );

        $birthday = Carbon::parse(sprintf(
            "%04d-%02d-%02d 00:00:00",
            substr($request->input('birthday'), 0, 4),
            substr($request->input('birthday'), 4, 2),
            substr($request->input('birthday'), 6, 2)
        ));
        if ($birthday->age < 16) {
            return redirect()->back();
        }

        $params = ['gender' => $request->input('gender'), 'birthday' => $birthday->format('Y-m-d')];
        // APIユーザーIDが存在しない場合
        require_once base_path('fancrew/app/class/require.app.php');
        require_once base_path('fancrew/app/class/ROI/Fancrew/ShopService.php');
        $apiUserId = \ROI_Fancrew_ShopService::get()->createApiUserId($params);

        // 取得に失敗した場合
        if (!isset($apiUserId)) {
            return abort(500);
        }

        $aff_account = AffAccount::getDefault(AffAccount::FANCREW_TYPE, $user->id, $apiUserId);
        // 保存に失敗した場合
        $aff_account->createAffAccount();

        return isset($url) ? redirect($url) : redirect()->back();
    }
}
