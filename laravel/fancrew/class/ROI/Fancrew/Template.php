<?php
require_once CLASS_PATH . 'ROI/Template.php';

/**
 * ファンくる固有の解釈を行うテンプレート
 *
 * @author Kenkichi Mahara
 *
 */
class ROI_Fancrew_Template extends ROI_Template {
	/**
	 * ROI 固有の追加処理を行う、_eval() をオーバーライドしたもの。
	 *
	 * @param unknown_type $matches
	 * @throws Exception
	 * @return Ambiguous
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
                  throw new Exception("パラメータ名 $name は存在しません。(paramName = $paramName)");
              }

              $target = $this->parameterMap[$name];
              $first = false;
          } else {
              if (is_object($target)) {
                  // 【処理1】application.event 呼び出しの場合、 application->Monitor と解釈する。
                  if ($targetName == 'application' && $name == 'event') {
                      $target = $target->Monitor;
                  } else if (property_exists($target, $name)) {
                      $target = $target->$name;
                  } else if (isset($target[$name])) {
                      $target = $target[$name];
                  } else {
                      // 【処理2】単語の先頭を大文字にして再度調査。
                      // roi の xml では、タグ名はクラスと同じ意味なので、先頭が大文字となっている。
                      // 一方、メールテンプレートで利用している Java の freemarker 方式では、
                      // application.event という記述は application.getEvent() という呼び出しを意味する。
                      // しかし php で取得した xml は Application->Event という構造で DOM が作成されるので、
                      // ここで先頭大文字の名前で再調査する。
                      $name2 = ucfirst($name);

                      if (property_exists($target, $name2)) {
                          $target = $target->$name2;
                      } else if (isset($target[$name2])) {
                          $target = $target[$name2];
                      } else {
                          throw new Exception("オブジェクト $targetName のプロパティ $name は存在しません。");
                      }
                  }
              } else {
                  if (isset($target[$name])) {
                      $target = $target[$name];
                  } else {
                      throw new Exception("配列 $targetName の要素名 $name は存在しません。");
                  }
              }
          }
          $targetName = $name;
      }

      return $target;
  }
}
?>