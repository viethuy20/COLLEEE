<?php
require_once 'require.base.php';

require_once CLASS_PATH . 'ROI/Fancrew/MailReader.php';

$templateDirPath = BASE_PATH . "app/mail/";
$mailReader = new ROI_Fancrew_MailReader($templateDirPath);

echo "■test1: EventMessage.id が 1 桁の場合\n";
{
	$url = BASE_PATH . "test-data/EventMessageReceiver/EventMessages-size1.xml";
	$xml = simplexml_load_file($url);
	$xmlEventMessage = $xml->EventMessage;

	$user = $xml->EventMessage->Application->User;
	$user['name'] = 'ロイ太郎';

	$userDeviceType = 1;

	$mail =  $mailReader->getMail($xmlEventMessage, $user, $userDeviceType);

	var_dump($mail);
}

echo "■test2: EventMessage.id が 10 の場合\n";
{
	$url = BASE_PATH . "test-data/EventMessageReceiver/EventMessages-size1-b.xml";
	$xml = simplexml_load_file($url);
	$xmlEventMessage = $xml->EventMessage;

	$user = $xml->EventMessage->Application->User;
	$user['name'] = 'ロイ太郎';

	$userDeviceType = 2;

	$mail =  $mailReader->getMail($xmlEventMessage, $user, $userDeviceType);

	var_dump($mail);
}
?>