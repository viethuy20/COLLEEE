<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>店舗画面</title>
</head>
<body>
	<h1>店舗画面</h1>
	<hr />
	店舗ID: <?= $viewContext['shop']['id'] ?><br />
	店舗名: <?= $viewContext['shop']['name'] ?><br />

	<br />
	<a href="<?= $viewContext['shopEntryURL'] ?>">応募する</a><br />

	<hr />
	(c) ROI, Inc.
</body>
</html>