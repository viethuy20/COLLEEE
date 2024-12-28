<?php

/**
 * ローカルシステムとの繋ぎこみを行うサービスクラス。
 *
 */
class ROI_Fancrew_EventMessageReceiver_LocalSystemService {
	/**
	 * 送信元メールアドレスを取得する。
	 *
	 */
	public function getMailFromAddress() {
		// TODO ここを修正してください。
		return 'foo@example.com';
	}

	/**
	 * 送信元の名前取得する。
	 *
	 */
	public function getMailFromName() {
		// TODO ここを修正してください。
		return 'ファンくる運営事務局';
	}

	/**
	 * ユーザ情報を設定する。<br />
	 *
	 * $user に以下の情報をセットする。
	 * $user['name'] - ユーザの名前
	 * $user['mailAddress'] - メールアドレス
	 *
	 * @param $user
	 */
	public function setLocalUserInformation($user) {
		// APIユーザIDを取得する。
		$apiUserId = $user['id'];

		// TODO データベースから APIユーザID に紐付くユーザの、以下の情報を取得し、 $user にセットしてください。
		// name (名前)
		// mailAddress (メールアドレス)

		// TODO 以下はサンプル。削除してください。
		$user['name'] = 'ロイ太郎';
		$user['mailAddress'] = 'foo@example.com';
	}
}
?>