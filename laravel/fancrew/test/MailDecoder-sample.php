<?php
require_once 'require.base.php';

require_once CLASS_PATH . 'ROI/MailDecoder.php';

$rawMail = file_get_contents('c:/test.eml');

// var_dump($rawMail);

$decoder = new ROI_MailDecoder($rawMail);

$from = $decoder->getFromAddr();
$to = $decoder->getToAddr();

echo "from = $from\n";
echo "to = $to\n";

$numOfAttach = $decoder->getNumOfAttach();
echo "numOfAttach = $numOfAttach\n";

$attachments = $decoder->attachments;

for ($i = 0; $i < $numOfAttach; $i++) {
	$attach = $attachments[$i];

	echo "■$i\n";
	echo "mime_type = " . $attach['mime_type'] . "\n";
	echo "file_name = " . $attach['file_name'] . "\n";
	echo "filesize = " . strlen($attach['binary']) . "\n";
}

?>