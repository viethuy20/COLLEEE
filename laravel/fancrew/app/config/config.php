<?php

// 開発環境用の設定を利用するか？
//$isDevelopment = true;
$isDevelopment = config('app.env') != 'production';

if ($isDevelopment) {
	require 'config.devel.php';
} else {
	require 'config.product.php';
}
?>