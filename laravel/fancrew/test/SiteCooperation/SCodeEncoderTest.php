<?php
require_once realpath(dirname( __FILE__)) . '/../require.base.php';

require_once CLASS_PATH . 'ROI/Fancrew/SiteCooperation/SCodeEncoder.php';

/**
 * SCodeEncoder.php のテストクラス。
 * Encoder.php のテストも行います。
 *
 * @author Yoshitada Tanahara
 *
 */
class SCodeEncoderTest extends PHPUnit_Framework_TestCase {

    /**
     * 初期化
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        // 暗号化キー
        $this->secretKey = 'secret';
        $this->apiId = "46";
        $this->apiKey = "sampleKey";
        $this->apiUserId = "429298";
        $this->VERSION = 1;

        $this->sCodeEncoder = new ROI_Fancrew_SiteCooperation_SCodeEncoder($this->secretKey, $this->apiId, $this->apiKey);
    }

    /**
     * SCodeCreate.createSCode のテスト。
     *
     */
    public function testCreateSCode() {
        $dateTime =  '2011/11/01 10:08:56';

        $text = "" . $this->VERSION . "\t" . $this->apiKey . "\t" . $this->apiUserId . "\t" . $dateTime;

        $encodeData = $this->sCodeEncoder->createSCode($this->apiUserId, $dateTime);

        $this->assertEquals("46:8YOHvRL_GZip6TqrsllJMWbYg-rd2f_T-2wDwh1sr-jl5QD4pvafxg", $encodeData);
    }

    /**
     * Encoder.base64_encode_urlsafe のテスト。
     *
     */
    public function testBase64_encode_UrlSafe() {
        // 手計算結果
        // 251 240
        // 0xfb f0                    16 進数で表現
        // 1111 1011 1111 0000        2 進数に変換
        // 111110 1111111 0000        6 bit ずつにまとめる。
        // + / A =                    BASE64 エンコード表を見ながら文字に直す。 http://ja.wikipedia.org/wiki/Base64
        // - _ A                      URL Safe にする。

        $text = chr(251) . chr(240);

        $data = ROI_Crypt::base64_encode_urlsafe($text);

        $this->assertEquals("-_A", $data);


    }

}
?>
