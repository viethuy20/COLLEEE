<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>サンプル</title>
</head>
<body topmargin="0" leftmargin="0">
	<h1>マイページ・フレーム</h1>
	<div style="width: 980px; height: 40px; background-color: #33ccff;font-size: 9pt">ヘッダー<br />
		<?php echo "iFrame src = " . $viewContext['remoteControllerURL']; ?>
	</div>

	<iframe width="800" height="600" style="border: 0px" src="<?= $viewContext['remoteControllerURL'] ?>"></iframe>

	<div style="width: 980px; height: 150px; background-color: #33ccff;">フッター</div>
</body>
</html>