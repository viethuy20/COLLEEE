<?php
namespace App\Http\Middleware;

use Closure;
use \Illuminate\Http\Request;

use App\External\Google;
use App\Device\Device;

class GoogleRecaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->check($request)) {
            abort(404);
        }

        return $next($request);
    }

    /**
     * リキャプチャ検証.
     * @param  Request $request
     * @return bool 正常な場合はtrueを、異常な場合はfalseを返す
     */
    public function check(Request $request) : bool {
        $g_recaptcha_response = $request->input(Google::getRecaptchaParamKey());
        $ip = Device::getIp();
        return Google::checkRecaptcha($g_recaptcha_response, $ip, function($recaptcha_data) {
            //\Log::info('Google checkRecaptcha:'.json_encode($recaptcha_data));
            // スコア0.0で失敗
            if ($recaptcha_data->score == 0.0) {
                return false;
            }
            return true;
        });
    }
}
