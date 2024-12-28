<?php
/*
 * test/ 内の全てのテストを実行します。
 *
 * 【準備】
 * コマンドプロンプト上で以下のコマンドを実行し、PHPUnit をインストールしてください。
 *
 * pear install phpunit/PHPUnit
 */

$_SERVER['argv'] = array(
	'phpunit',
	'.',
);

include "C:/xampp/php/phpunit";
?>