<?php
/* 便利な関数群
 *
 * Copyright: ROI, Inc.
 * Version: 2011-12-07
 */

/**
 * startsWith
 * http://blog.anoncom.net/2009/02/20/124.html
 *
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function startsWith($haystack, $needle){
    return strpos($haystack, $needle, 0) === 0;
}

/**
 * endsWith
 * http://blog.anoncom.net/2009/02/20/124.html
 *
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function endsWith($haystack, $needle){
    $length = (strlen($haystack) - strlen($needle));
    if( $length <0) return false;
    return strpos($haystack, $needle, $length) !== false;
}

/**
 * 以下の URL に載っている obsafe_print_r を改良したバージョン。
 *
 * 再帰的に呼ばれるため、 stack overflow にならないようにするようにした。
 *
 * http://www.php.net/manual/en/function.print-r.php#75872
 */
function obsafe_print_r($var, $return = false, $html = false, $level = 0) {
    $space = $html ? "&nbsp;" : " ";
    $newline = $html ? "<br />" : "\n";

    $spaces = "";
    for ($i = 1; $i <= 4; $i++) {
        $spaces .= $space;
    }
    $tabs = $spaces;
    for ($i = 1; $i <= $level; $i++) {
        $tabs .= $spaces;
    }
    if (is_array($var)) {
        $title = "Array";
    } elseif (is_object($var)) {
        $title = get_class($var)." Object";
    }
    $output = $title . $newline;
    foreach($var as $key => $value) {
    	if ($key == 'GLOBALS') {
    		$value = "&array";
    	} else {
	        if (is_array($value) || is_object($value)) {
	        	if ($level == 10) {
	        		$value =  "...";
	        	} else {
	            	$value = obsafe_print_r($value, true, $html, $level + 1);
	        	}
	        }
    	}
    	if ($value === null) {
    		$value = "(null)";
    	} else if ($value === false) {
    		$value = "false";
    	} else if ($value === true) {
    		$value = "true";
    	}
        $output .= $tabs . "'" . $key . "' => " . $value . $newline;
    }
    if ($return) return $output;
	else echo $output;
}

/**
 * var_dump 結果をテキスト形式に変換し、文字列を返す。
 *
 * @param オブジェクト $var
 * @package int $maxlength 文字列の最大長。デフォルトは 0 (無制限)。
 * @return string
 */
function var_dump_as_text($var, $maxlength = 0) {
	ob_start();
	var_dump($var);
	$dump = ob_get_clean();

	// html 形式をテキスト形式に変換。
	$dump = htmlspecialchars_decode(strip_tags($dump));

	// 長すぎる場合は短縮する。
	if ($maxlength > 0) {
		if (strlen($dump) >= $maxlength) {
			$dump = substr($dump, 0, $maxlength);
		}
	}

	return $dump;
}

/**
 * 許可された IPアドレスかチェックを行います。
 *
 * @param string	$remoteIp	チェック対象の IPアドレス
 * @param array		$permitIps	許可IPアドレス一覧
 * @return boolean
 */
function chekPermitIP($permitIPs, $remoteIP) {
    // チェック
    $isPermitOK = false;
    foreach ($permitIPs as $permitIP) {
        @list($allowIP, $mask) = explode("/", $permitIP);

        if (isset($mask)) {
            $remoteLong	= ip2long($remoteIP) >> (32 - $mask);
            $allowLong	= ip2long($allowIP) >> (32 - $mask);

            if ($remoteLong == $allowLong) {
                $isPermitOK = true;
                break;
            }
        } else {
            if ($remoteIP == $allowIP) {
                $isPermitOK = true;
                break;
            }
        }
    }
    return $isPermitOK;
}

?>
