<?php
function email_quote(string $email) : string {
    $p = strrpos($email, '@');
    $local = substr($email, 0 ,$p);
    $local = str_replace("\\", "\\\\", $local);
    $local = str_replace("\"", "\\\"", $local);
    $local = str_replace(" ", "\\ ", $local);
    return '"'.$local.'"'.substr($email, $p);
}
function email_unquote(string $email) : string {
    $p = strrpos($email, '@');
    $local = substr($email, 0 ,$p);
    $local = trim($local, '"');
    $local = str_replace("\\ ", " ", $local);
    $local = str_replace("\\\"", "\"", $local);
    $local = str_replace("\\\\", "\\", $local);
    return $local.substr($email, $p);
}

/**
 * IP検証.
 * @param string $remote_ip 検証IP
 * @param string|array $accept 許可IP
 * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
 */
function is_allowed_ip(string $remote_ip, $accept) : bool
{
    // 配列の場合
    if (is_array($accept)) {
        foreach ($accept as $accept_ip) {
            if (is_allowed_ip($remote_ip, $accept_ip)) {
                return true;
            }
        }
        return false;
    }

    // マスクがない場合は完全一致で確認
    if (strpos($accept, '/') === false) {
        return ($remote_ip === $accept);
    }
    // マスクが存在する場合
    list($accept_ip, $mask) = explode('/', $accept);
    $accept_long            = ip2long($accept_ip) >> (32 - $mask);
    $remote_long            = ip2long($remote_ip) >> (32 - $mask);
    return ($accept_long == $remote_long);
}

function is_secure() {
    $base_url = config('app.url');
    return strpos($base_url, 'https://') === 0;
}
