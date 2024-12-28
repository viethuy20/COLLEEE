<?php
namespace App\Device;

use \Illuminate\Http\Request;
use \Jenssegers\Agent\Agent;

class Device {
    private static $ip = null;
    static $device_id = null;
    public static function getDeviceId() : int
    {
        if (isset(self::$device_id)) {
            return self::$device_id;
        }
        $ua = request()->header('User-Agent');
        self::$device_id = isset($ua) ? Device::getDeviceIdFromUA($ua) : 1;
        return self::$device_id;
    }

    public static function getDeviceIdFromUA($ua) : int
    {
        $agent = new Agent();
        $agent->setUserAgent($ua);
        if ($agent->isPhone()) {
            if ($agent->isAndroidOS()) {
                return 3;
            }
            if ($agent->is('iPhone')) {
                return 2;
            }
        }
        return 1;
    }

    /**
     * IPアドレスを取得.
     * @return string IPアドレス
     */
    public static function getIp() : string
    {
        // 既にIPが作成されていた場合
        if (isset(self::$ip)) {
            return self::$ip;
        }

        // プロキシ数取得
        $proxy_total = config('app.proxy_total');

        $first_ip = \Request::ip();
        // プロキシが存在する場合
        if (!empty($proxy_total)) {
            $proxy_ip_list = [];
            for ($i = 0; $i < $proxy_total; ++$i) {
                $proxy_ip_list[] = $first_ip;
                \Request::setTrustedProxies($proxy_ip_list, Request::HEADER_X_FORWARDED_AWS_ELB);
                $next_ip = \Request::ip();
                if (empty($next_ip)) {
                    self::$ip = $first_ip;
                    return self::$ip;
                }
                $first_ip = $next_ip;
            }
        }

        self::$ip = $first_ip;
        return self::$ip;
    }

    public static function isMobile()
    {
        $userAgent = request()->header('User-Agent');
        $mobileDevices = ['iPhone', 'iPad', 'Android'];

        foreach ($mobileDevices as $device) {
            if (stripos($userAgent, $device) !== false) {
                return true;
            }
        }

        return false;
    }
}
