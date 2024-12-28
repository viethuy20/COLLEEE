<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>店舗画面</title>
</head>
<body>
	<center>店舗画面</center>
	<hr />
	店舗ID: <?= $viewContext['shop']['id'] ?><br />
	店舗名: <?= $viewContext['shop']['name'] ?><br />

	<br />
	<a href="<?= $viewContext['shopEntryURL'] ?>">応募する</a><br />

	<hr />
	(c) ROI, Inc.
</body>
</html>