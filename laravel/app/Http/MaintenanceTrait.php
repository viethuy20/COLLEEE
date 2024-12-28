<?php
namespace App\Http;

use App\ExchangeInfo;
use App\Exceptions\MaintenanceException;

trait MaintenanceTrait {
    public static function checkExchangeInfo($type) : ExchangeInfo
    {
        $exchange_info = ExchangeInfo::ofType($type)
            ->ofNow()
            ->first();

        if (!isset($exchange_info)) {
            throw new MaintenanceException('メンテナンス中です。');
        }
        if ($exchange_info->status != ExchangeInfo::SUCCESS_STATUS) {
            throw new MaintenanceException($exchange_info->message_body ?? 'メンテナンス中です。');
        }
        return $exchange_info;
    }
}
