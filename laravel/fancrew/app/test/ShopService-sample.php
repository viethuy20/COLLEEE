<?php

require_once realpath(dirname( __FILE__) . '/../class/require.app.php');
require_once CLASS_PATH . 'ROI/Fancrew/ShopService.php';

$shopService = ROI_Fancrew_ShopService::get();

$shopService->logger->level = ROI_Logger::DEBUG;
$shopService->logger->stdout = true;

$xmlShop = $shopService->getShop(1624);

echo $xmlShop->asXML() . "\n";
?>