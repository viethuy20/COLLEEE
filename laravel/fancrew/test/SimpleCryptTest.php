<?php
require_once 'require.base.php';

require_once CLASS_PATH . 'ROI/SimpleCrypt.php';

class SimpleCryptTest extends PHPUnit_Framework_TestCase {

	private function createTemplate() {
	}

    public function test1() {
		$key = "test";
		$plain = "abc123";

		$enc = ROI_SimpleCrypt::encrypt($key, $plain);
        $plain2 = ROI_SimpleCrypt::decrypt($key, $enc);
        $this->assertEquals($plain, $plain2);
    }

    // ブロック長を 2 にしてみる。
    public function test2() {
		$key = "test";
		$plain = "abc123";

		$enc = ROI_SimpleCrypt::encrypt($key, $plain, 2);
        $plain2 = ROI_SimpleCrypt::decrypt($key, $enc, 2);
        $this->assertEquals($plain, $plain2);
    }

    // 平文長を 7 にする。padding 処理が行われない。
    public function test3() {
		$key = "test";
		$plain = "abc123a";

		$enc = ROI_SimpleCrypt::encrypt($key, $plain);
        $plain2 = ROI_SimpleCrypt::decrypt($key, $enc);
        $this->assertEquals($plain, $plain2);
    }

    // 平文より暗号鍵の方が長い。
    public function test4() {
		$key = "testtest";
		$plain = "abc123";

		$enc = ROI_SimpleCrypt::encrypt($key, $plain);
        $plain2 = ROI_SimpleCrypt::decrypt($key, $enc);
        $this->assertEquals($plain, $plain2);
    }

}

?>
