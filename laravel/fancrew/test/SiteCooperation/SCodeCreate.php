<?php
/**
 * SCode 生成テスト。
 */

require_once realpath(dirname( __FILE__)) . '/../require.base.php';

require_once CLASS_PATH . 'ROI/Fancrew/SiteCooperation/SCodeEncoder.php';

// 暗号化キー
$secretKey = 'secret';
$apiId = "46";
$apiKey = "sampleKey";
$apiUserId = "429298";


$obj = new ROI_Fancrew_SiteCooperation_SCodeEncoder($secretKey, $apiId, $apiKey);

// SCode の生成。
$s = $obj->createSCode($apiUserId);
echo $s . "\n";


?>