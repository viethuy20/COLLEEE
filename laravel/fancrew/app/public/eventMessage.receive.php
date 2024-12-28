<?php
require_once realpath(dirname( __FILE__) . '/../class/require.app.php');

require_once APP_CLASS_PATH . 'ROI/Fancrew/EventMessageReceiver/Controller.php';

$controller = new ROI_Fancrew_EventMessageReceiver_Controller();

$controller->exec();
?>