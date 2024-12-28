<?php
require_once 'require.base.php';

class SystemTest extends PHPUnit_Framework_TestCase {
	public function test1() {
		$system = ROI_System::get();
		$trackingCode = $system->getTrackingCode("192.168.12.3");

		list($millis, $hostTrackingCode) = explode('.', $trackingCode);

		$this->assertEquals("0c03", $hostTrackingCode);
	}
}
?>
