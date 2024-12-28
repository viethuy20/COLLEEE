<?php

class ROI_Logger {
	/** デフォルトのログ出力レベル */
	public static $default_level = self::INFO;

	/** デフォルトでログの内容を標準出力に出力するか？ デバッグ用。 */
	public static $default_stdout = false;

	/** デフォルトのログ出力先ファイル名。ull なら php のデフォルトの出力先 (例: Apache の error_log) に出力される。 */
	public static $default_filename = null;

	/** ログに出力する名前 */
	private $name;

	/** ログ出力レベル。この値より低いレベルのログは出力しない。 */
	public $level;

	/** ログの内容を標準出力に出力するか？ デバッグ用。 */
	public $stdout;

	const DEBUG = 0;
	const INFO = 1;
	const WARN = 2;
	const ERROR = 3;

	/** ログ出力先ファイル名。null なら php のデフォルトの出力先 (例: Apache の error_log) に出力される。 */
	private $filename;

	public function __construct($object, $filename = null) {
		if (is_object($object)) {
			$this->name = get_class($object);
		} else {
			$this->name = $object;
		}

		$this->filename = $filename;
		$this->level    = self::$default_level;
		$this->stdout   = self::$default_stdout;
		$this->filename = self::$default_filename;
	}

	private function getLevelName($level) {
		switch ($level) {
			case self::DEBUG: return "DEBUG";
			case self::INFO: return "INFO";
			case self::WARN: return "WARN";
			case self::ERROR: return "ERROR";
			default: new Exception("未対応の level: " . $this->level);
		}
	}

	private function log($level, $message, $exception) {
		if ($this->level > $level) {
			return;
		}

		$levelName = $this->getLevelName($level);

		$s = sprintf('%5s', $levelName) . ' [' . date('Y-m-d H:i:s.u') . '] ' . $this->name . ': ' . $message . "\n";

		if ($exception != null) {
			$s .= "\n■Stack Trace\n" . $exception->__toString() . "\n";
		}

		if ($this->filename == null) {
			error_log($s);
		} else {
			error_log($s, 3, $this->filename);
		}

		// デバッグ出力
		if ($this->stdout) {
			echo $s;
		}
	}

	public function debug($message, $exception = null) {
		$this->log(self::DEBUG, $message, $exception);
	}

	public function info($message, $exception = null) {
		$this->log(self::INFO, $message, $exception);
	}

	public function warn($message, $exception = null) {
		$this->log(self::WARN, $message, $exception);
	}

	public function error($message, $exception = null) {
		$this->log(self::ERROR, $message, $exception);
	}

	public function isDebugEnabled() {
		return ($this->level > self::DEBUG) ? false : true;
	}

	public function isInfoEnabled() {
		return ($this->level > self::INFO) ? false : true;
	}

	public function isWarnEnabled() {
		return ($this->level > self::WARN) ? false : true;
	}

	public function isErrorEnabled() {
		return ($this->level > self::ERROR) ? false : true;
	}
}

?>