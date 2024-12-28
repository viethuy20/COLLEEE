<?php
require_once realpath(dirname( __FILE__)) . '/../require.base.php';

require_once CLASS_PATH . 'ROI/Fancrew/EventMessageReceiver/ResponseWriter.php';

{
    $responseWriter = new ROI_Fancrew_EventMessageReceiver_ResponseWriter("abc.0000");

    $responseWriter->outputXml(9000, "サンプルエラー");
}

{
    $responses = array();

    $responses[] = array(
        'id' => 5,
        'success' => true,
    );
    $responses[] = array(
            'id' => 6,
            'success' => false,
            'error' => "あいう",
            'retryRequested' => true,
    );
    $responses[] = array(
                'id' => 7,
                'success' => false,
                'error' => "あいう",
                'retryRequested' => false,
    );

    $responseWriter = new ROI_Fancrew_EventMessageReceiver_ResponseWriter("test1");

    $responseWriter->outputXml(null, null, $responses);
}


?>