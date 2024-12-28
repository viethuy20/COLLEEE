<?php
require_once 'require.base.php';

require_once CLASS_PATH . 'ROI/Fancrew/MailReader.php';

class MailReaderTest extends PHPUnit_Framework_TestCase {
	var $mailReader;

	public function setUp() {
		$templateDirPath = BASE_PATH . "app/mail/";
		$this->mailReader = new ROI_Fancrew_MailReader($templateDirPath);
	}

	private function doTest($xmlEventMessage, $user, $expectedSubjects) {
		for ($userDeviceType = 1; $userDeviceType <= 2; $userDeviceType++) {
			$mail =  $this->mailReader->getMail($xmlEventMessage, $user, $userDeviceType);

			$subject = $mail['subject'];
			$expected = $expectedSubjects[$userDeviceType - 1];

			$body    = $mail['body'];

			$this->assertTrue(strlen($body) > 10);

			$this->assertEquals($expected, $subject);
		}
	}

	public function test1() {
		$url = BASE_PATH . "/test-data/EventMessageReceiver/EventMessages-size1.xml";
		$xml = simplexml_load_file($url);
		$xmlEventMessage = $xml->EventMessage;

		$user = $xml->EventMessage->Application->User;
		$user['name'] = 'ロイ太郎';

		$expectedSubjects = array(
			"[ファンくる]「お茶漬けBAR ZUZU　新宿店」モニター抽選結果のお知らせ",
			"[ﾌｧﾝくる]「お茶漬けBAR ZUZU　新宿店」ﾓﾆﾀｰ抽選結果のお知らせ",
		);

		$this->doTest($xmlEventMessage, $user, $expectedSubjects);
	}
}
?>
