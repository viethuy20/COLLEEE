<?php
require_once 'require.base.php';

require_once CLASS_PATH . 'ROI/Template.php';

class functionTest extends PHPUnit_Framework_TestCase {
	private $permitIPs;

	public function setUp() {
		$this->permitIPs = array('127.0.0.1', '113.43.105.136/29');
	}

	public function test1() {
		// maskなし 一致 true
		$this->assertEquals(true, chekPermitIP($this->permitIPs, '127.0.0.1'));

		// maskなし 不一致 false
		$this->assertEquals(false, chekPermitIP($this->permitIPs, '127.0.0.10'));

		// maskあり 下限 範囲外 false
		$this->assertEquals(false, chekPermitIP($this->permitIPs, '113.43.105.135'));

		// maskあり 下限 一致 true
		$this->assertEquals(true, chekPermitIP($this->permitIPs, '113.43.105.136'));

		// maskあり 下限 範囲内 true
		$this->assertEquals(true, chekPermitIP($this->permitIPs, '113.43.105.137'));

		// maskあり 上限 範囲外 false
		$this->assertEquals(false, chekPermitIP($this->permitIPs, '113.43.105.144'));

		// maskあり 上限 一致 true
		$this->assertEquals(true, chekPermitIP($this->permitIPs, '113.43.105.143'));

		// maskあり 上限 範囲内 true
		$this->assertEquals(true, chekPermitIP($this->permitIPs, '113.43.105.142'));

	}
}

?>
