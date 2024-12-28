<?php

// アプリケーションの基本ディレクトリ
define('APP_BASE_PATH', realpath(dirname( __FILE__) . "/../") . '/');

// プロジェクトの基本ディレクトリ
define('BASE_PATH', realpath(dirname( __FILE__) . "/../../") . '/');

// 基本設定読み込み。
//require_once BASE_PATH . 'class/require.base.php';

// アプリケーション固有の class ディレクトリ
define('APP_CLASS_PATH', APP_BASE_PATH . 'class/');

// view ディレクトリ
define('APP_VIEW_PATH', APP_BASE_PATH . 'view/');

// data ディレクトリ
define('APP_DATA_PATH', APP_BASE_PATH . 'data/');

require_once APP_BASE_PATH . 'class/require.base.php';

// 設定読み込み
//require_once CLASS_PATH . 'ROI/Fancrew/Config.php';
require_once APP_CLASS_PATH . 'ROI/Fancrew/Config.php';
require_once APP_CLASS_PATH . 'ROI/Fancrew/EventMessageReceiver/Config.php';

require_once APP_BASE_PATH . 'config/config.php';
?>