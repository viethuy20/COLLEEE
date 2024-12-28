<?php
/*
 * OEM 側コントローラ (スマートフォン用)
 */
require_once realpath(dirname( __FILE__) . '/../class/require.app.php');
require_once APP_CLASS_PATH . 'ROI/Fancrew/SiteCooperation/SmartphoneController.php';

$controller = new ROI_Fancrew_SiteCooperation_SmartphoneController();

$controller->exec();
?>
