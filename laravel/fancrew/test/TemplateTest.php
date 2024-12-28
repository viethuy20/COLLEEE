<?php
require_once 'require.base.php';

require_once CLASS_PATH . 'ROI/Template.php';

class TemplateTest extends PHPUnit_Framework_TestCase {
	var $templateDirectory;

	/**
	* 初期化
	*
	* @see PHPUnit_Framework_TestCase::setUp()
	*/
	public function setUp() {
		$this->templateDirectory = BASE_PATH . "test-data/EventMessageReceiver/template/";
	}

	private function createTemplate() {
		$template = new ROI_Template();
		$template->setTemplateDirectory($this->templateDirectory);
		$template->templateCharset = 'utf-8';

		return $template;
	}

    /**
     * ${name} のテスト
     */
    public function test1() {
    	$template = $this->createTemplate();

    	$template->assign("name", "roi");
    	$actual = $template->fetch("1.txt");

        $this->assertEquals("こんにちは roi です。", $actual);
    }

    /**
     * ${shop.name}の例 (配列参照)
     */
    public function test2_1() {
    	$template = $this->createTemplate();

    	$shop['name'] = "ぐるリザ";

    	$template->assign("shop", $shop);
    	$actual = $template->fetch("2.txt");

        $this->assertEquals("店舗名: ぐるリザ", $actual);
    }

    /**
     * ${shop.name}の例 (オブジェクト参照)
     */
    public function test2_2() {
    	$template = $this->createTemplate();

    	$shop = new TestShop();
    	$shop->name = 'ファンくる';

    	$template->assign("shop", $shop);
    	$actual = $template->fetch("2.txt");

    	$this->assertEquals("店舗名: ファンくる", $actual);
    }

    /**
     * xml 連携テスト
     */
    public function test3() {
    	$template = $this->createTemplate();

		$shop = simplexml_load_file($this->templateDirectory ."Shop.xml");

    	$template->assign("shop", $shop);
    	$actual = $template->fetch("3.txt");

    	$expected = "店舗名: お茶漬けBAR ZUZU　新宿店\r\n"
    		. "ジャンル: 居酒屋";
    	$this->assertEquals($expected, $actual);
     }
}

class TestShop {
	var $name;
}

?>
