<?php

// パス関連定数

// class ディレクトリなどがあるディレクトリ
if (!defined('BASE_PATH')) {
	//define('BASE_PATH', realpath(dirname( __FILE__) . "/../") . '/');
    define('BASE_PATH', realpath(dirname( __FILE__) . "/../../") . '/');
}

// アプリケーションの library ディレクトリをパスに通す。
//set_include_path(BASE_PATH . 'library/' . PATH_SEPARATOR . get_include_path());


define('CLASS_PATH', BASE_PATH . 'class/');

//require_once CLASS_PATH . 'functions.php';
//require_once CLASS_PATH . 'ROI/System.php';
require_once APP_CLASS_PATH . 'ROI/System.php';

$roiSystem = ROI_System::initInstance();

// 共通で高頻度に利用されるクラス。
//require_once CLASS_PATH . 'ROI/Logger.php';

?>