<?php

/**
 * 暗号化関連クラス。
 *
 * @author Yoshitada Tanahara
 *
 */
class ROI_Crypt {

    /**
     * アルゴリズム「blowfish」を使用した暗号化を行います。
     * 暗号化対象ソースのパディング処理には、Java のパディング処理である PKCS5パディングを使用します。
     *
     * @param $secretKey 暗号鍵
     * @param $text
     * @return string
     */
    public static function encrypt($secretKey, $text) {
        $size = mcrypt_get_block_size('blowfish', 'ecb');
        $input = self::pkcs5_pad($text, $size);
        $td = mcrypt_module_open('blowfish', '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $secretKey , $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $data;
    }

    /**
     * PKCS5パディングを行います。
     *
     * @param $text
     * @param $blocksize
     * @return string
     */
    private static function pkcs5_pad($text, $blocksize) {
    	$pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * URLセーフなBase64エンコードを行います。
     *
     * @param unknown_type $s
     * @return mixed
     */
    public static function base64_encode_urlsafe($s) {
        $s = base64_encode($s);
        return (str_replace(array('+', '/', '='), array('-', '_', ''), $s));
    }

    /**
     * アルゴリズム「blowfish」を使用した暗号データの復号を行います。
     * 暗号化対象ソースのパディング処理には、Java のパディング処理と同じ PKCS5パディングを使用します。
     *
     * @param $secretKey 暗号鍵
     * @param $text
     * @return string
     */
    public static function decrypt($secretKey, $data) {
        $td = mcrypt_module_open('blowfish', '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $secretKey , $iv);
        $paddedText = mdecrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        $text = self::pkcs5_unpad($paddedText);

        return $text;
    }

    /**
     * PKCS5パディングを行います。
     *
     * @param $text
     * @param $blocksize
     * @return string
     */
    private function pkcs5_unpad($text) {
    	$len = strlen($text);
    	$pad = ord($text[$len - 1]);
    	if ($pad > $len) return false;
    	if (strspn($text, chr($pad), $len - $pad) != $pad) return false;
    	return substr($text, 0, -1 * $pad);
    }

	public static function base64_decode_urlsafe($s) {
	    $s = (str_replace( array('-','_'), array('+','/'), $s));
	    return (base64_decode($s));
	}
}
?>