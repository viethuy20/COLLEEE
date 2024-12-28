<?php
require_once realpath(dirname( __FILE__) . '/../../class/require.app.php');
require_once CLASS_PATH . 'ROI/MultiPartFormBuilder.php';

$url = 'http://localhost/fancrew/eventMessage.receive';

/*
 * xml を POST するサンプル。
 *
 * このプログラムを実行すると、APIユーザID 433392 に対応する メールアドレスに
 * 当選メールを送信します。
 *
 * (1) APIユーザID を変更するには test-data/EventMessages-size1.xml の User タグを修正ください。
 * (2) この APIユーザID に対応するメールアドレスを変更するには、
 *    app/class/ROI/Fancrew/EventMessageReceiver/LocalSystemService.php の LocalSystemService.php()
 *    を修正ください。
 *
 */
{
    $filename = BASE_PATH . "test-data/EventMessageReceiver/EventMessages-size1.xml";

    $xmlString = file_get_contents($filename);

    $formBuilder = new ROI_MultiPartFormBuilder();

    $formBuilder->addData('xml', $xmlString, 'text/plain; charset="UTF-8"');
    $ctx = $formBuilder->toStreamContext();

    $contents = file_get_contents($url, false, $ctx);

    echo $contents;
}
?>