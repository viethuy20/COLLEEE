<?php
/*
 * OEM 側コントローラ (PC用)
 */
require_once realpath(dirname( __FILE__) . '/../class/require.app.php');
require_once APP_CLASS_PATH . 'ROI/Fancrew/SiteCooperation/PcController.php';

$controller = new ROI_Fancrew_SiteCooperation_PcController();

$controller->exec();
?>
