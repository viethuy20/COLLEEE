<?php
/**
 * ROI のテンプレート・エンジン<br />
 *
 * Java の freemarker と互換性を持つ。
 *
 * @author Kenkichi Mahara
 * @version 2011-12-05
 *
 */
class ROI_Template {
	/** テンプレート・ディレクトリ */
	private $templateDirectory;

	/** テンプレートに適用するパラメータ: key=パラメータ名, value=パラメータ値 */
	protected $parameterMap = array();

	/** 置換対象の正規表現 */
	private $pattern = '/\$\{([a-zA-Z0-9_.]+)\}/';

	/** テンプレート・ファイルの文字コード。デフォルト: sjis-win */
	public $templateCharset = 'sjis-win';

	/**
	 * テンプレート・ディレクトリを指定する。
	 *
	 * @param string $templateDirectory テンプレート・ディレクトリ。最後は / で終わる。
	 */
	public function setTemplateDirectory($templateDirectory) {
		if (!endsWith($templateDirectory, '/')) {
			$templateDirectory .= '/';
		}

		$this->templateDirectory = $templateDirectory;
	}

	public function getTemplateDirectory() {
	    return $this->templateDirectory;
	}

	/**
	 * パラメータをセットする。
	 *
	 * @param mixed $name	パラメータ名
	 * @param mixed $value	パラメータ値
	 */
	public function assign($name, $value) {
		$this->parameterMap["$name"] = $value;
	}

	/**
	 * テンプレートを解釈し、テキストを得る。
	 *
	 * @param string $filename	テンプレート・ファイル名
	 * @return mixed パラメータを適用したテキスト
	 */
	public function fetch($filename) {
		// テンプレート・ファイル名を完成。
		$templateFilename = $this->templateDirectory . $filename;

		// テンプレートを読み込む。
		$template = file_get_contents($templateFilename);

		// MS932 を UTF-8 に変換する。
		$template = mb_convert_encoding($template, "UTF-8", $this->templateCharset);

		// 置換処理
		$text = preg_replace_callback($this->pattern, array ($this, "_eval"), $template);

		return $text;
	}

	/**
	 * テンプレートを読み込み、解釈して表示する。
	 *
	 * @param string $filename	テンプレート・ファイル名
	 */
	public function display($filename) {
		$text = $this->evaluate($filename);
		echo $text;
	}

	/**
	 * preg_replace_callback から呼ばれる。テンプレート中のパラメータを置換する。
	 *
	 * @param array $matches 一致した文字列一覧。
	 * @throws Exception パラメータ解析失敗
	 * @return text パラメータ値
	 */
	protected function _eval($matches) {
		// パラメータ名
		$paramName = $matches[1];

		// "." で分割する。
		$names = explode('.', $paramName);

		$first = true;		// 最初の実行？
		$target = null;		// 現在処理中のオブジェクト
		$targetName = null;	// オブジェクト名

		// 名前階層を辿っていく。
		foreach ($names as $name) {
			if ($first) {
				if (!isset($this->parameterMap[$name])) {
					throw new Exception("パラメータ名 $name は存在しません。", 1);
				}

				$target = $this->parameterMap[$name];
				$first = false;
			} else {
				if (is_object($target)) {
					if (property_exists($target, $name)) {
						$target = $target->$name;
					} else if (isset($target[$name])) {
						$target = $target[$name];
					} else {
   						throw new Exception("オブジェクト $targetName のプロパティ $name は存在しません。", 1);
					}
				} else {
					if (isset($target[$name])) {
						$target = $target[$name];
					} else {
						throw new Exception("配列 $targetName の要素名 $name は存在しません。", 1);
					}
				}
			}
			$targetName = $name;
		}

		return $target;
	}
}
?>
