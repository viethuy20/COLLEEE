<?php
require_once 'require.base.php';

require_once CLASS_PATH . 'ROI/SimpleMailSender.php';

class SimpleMailSenderTest extends PHPUnit_Framework_TestCase {
	public function test1() {
		$fromAddress = 'test@j-roi.com';
		$fromName = 'ファンくる運営事務局';

		$smtpParams = array(

		);

		$sender = new ROI_SimpleMailSender($smtpParams);
		$from = $sender->makeFrom($fromAddress, $fromName);

		$this->assertEquals("=?ISO-2022-JP?B?GyRCJVUlISVzJC8kazE/MUQ7dkwzNkkbKEI=?= <test@j-roi.com>", $from);
	}
}
?>
