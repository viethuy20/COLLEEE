<?php
require_once 'require.base.php';

require_once CLASS_PATH . 'ROI/Crypt.php';

class CryptTest extends PHPUnit_Framework_TestCase {

	private function createTemplate() {
	}

    public function test1() {
		$secretKey = "test";
		$text = "abc123";

		$enc = ROI_Crypt::encrypt($secretKey, $text);

		$base46UrlSafeData = ROI_Crypt::base64_encode_urlsafe($enc);
        $this->assertEquals("ijZG_55ppmA", $base46UrlSafeData);

        $enc2 = ROI_Crypt::base64_decode_urlsafe($base46UrlSafeData);
        $this->assertEquals($enc, $enc2);

        $text2 = ROI_Crypt::decrypt($secretKey, $enc2);
        $this->assertEquals($text, $text2);
    }
}

?>
