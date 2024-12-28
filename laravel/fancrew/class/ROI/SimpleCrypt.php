<?php
/**
 * mcrypt を使わない暗号処理
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_SimpleCrypt {

	private static function getRndIV($ivLen) {
		$iv = '';
		while ($ivLen-- > 0) {
			$iv .= chr(mt_rand() & 0xff);
		}
		return $iv;
	}

	/**
	 * 暗号化を行う。
	 *
	 * @param string	$key	秘密鍵
	 * @param byte[]	$plain	平文(文字列, バイナリ)
	 * @param int		$ivLen	初期化ベクトル(IV)長およびブロック長。暗号文の先頭にこの文字数分、ランダムな文字列が付加される。
	 * @return byte[]			暗号文(バイナリ)
	 */
	public static function encrypt($key, $plain, $ivLen = 8) {
		$plain .= "\x13";
		$n = strlen($plain);

		if ($n % $ivLen) {
			$plain .= str_repeat("\0", $ivLen - ($n % $ivLen));
		}

		$i = 0;
		$enc = self::getRndIV($ivLen);
		$iv = substr($key ^ $enc, 0, 512);
		while ($i < $n) {
			$block = substr($plain, $i, $ivLen) ^ pack('H*', sha1($iv));
			$enc .= $block;
			$iv = substr($block . $iv, 0, 512) ^ $key;
			$i += $ivLen;
		}
		return $enc;
}

	/**
	 * 復号化を行う。
	 *
	 * @param string	$key	秘密鍵
	 * @param byte[]	$enc	暗号文(バイナリ)
	 * @param int		$ivLen	初期化ベクトル(IV)長およびブロック長
	 * @return byte[]			平文
	 */
	public static function decrypt($key, $enc, $ivLen = 8) {
		$n = strlen($enc);
		$i = $ivLen;
		$plain = '';
		$iv = substr($key ^ substr($enc, 0, $ivLen), 0, 512);

		while ($i < $n) {
			$block = substr($enc, $i, $ivLen);

			$plain .= $block ^ pack('H*', sha1($iv));
			$iv = substr($block . $iv, 0, 512) ^ $key;

			$i += $ivLen;
		}
		return stripslashes(preg_replace('/\\x13\\x00*$/', '', $plain));
	}
}
?>