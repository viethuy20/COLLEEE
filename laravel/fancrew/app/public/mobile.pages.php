<?php
/*
 * OEM 側コントローラ (携帯用)
 */
require_once realpath(dirname( __FILE__) . '/../class/require.app.php');
require_once APP_CLASS_PATH . 'ROI/Fancrew/SiteCooperation/MobileController.php';

$controller = new ROI_Fancrew_SiteCooperation_MobileController();

$controller->exec();
?>
