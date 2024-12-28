<?php

require_once CLASS_PATH . 'ROI/ApiResponseXmlWriter.php';

/**
 * イベントの結果を出力するクラス。
 *
 * @author Yoshitada Tanahara
 *
 */
class ROI_Fancrew_EventMessageReceiver_ResponseWriter extends ROI_ApiResponseXmlWriter {

	public function __construct($trackingCode) {
		parent::__construct($trackingCode);
	}

	/**
	 * Data タグの中身を生成する。
	 *
	 * @see ROI_ApiResponseXmlWriter::createInnerDataElement()
	 */
	protected function createInnerDataElement($dom, $dataElement, $responses = null) {
		if (isset($responses)) {
			$eventMessageResponsesElement = $dataElement->appendChild($dom->createElement('EventMessageResponses'));
			foreach ($responses as $response) {
				$eventMessageResponseElement = $eventMessageResponsesElement->appendChild($dom->createElement('EventMessageResponse'));
				$eventMessageResponseElement->setAttribute('id', $response['id']);
				$eventMessageResponseElement->setAttribute('success', ROI_ApiResponseXmlWriter::asBoolean($response['success']));

				if (isset($response['error'])) {
					$eventMessageResponseElement->setAttribute('error', $response['error']);
				}

				if (isset($response['retryRequested'])) {
					$eventMessageResponseElement->setAttribute('retryRequested', ROI_ApiResponseXmlWriter::asBoolean($response['retryRequested']));
				}
			}

			return true;
		}
	}
}
?>