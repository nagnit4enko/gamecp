<?	$menu = get_servers();	?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Панель управления</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/metisMenu.min.css" rel="stylesheet">
    <link href="/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/css/dataTables.responsive.css" rel="stylesheet">
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="/css/alertify.core.css" rel="stylesheet">
	<link href="/css/alertify.bootstrap.css" rel="stylesheet">
</head>
<body>
<? require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/include/modal.php'); ?>
<div id="wrapper">
<? require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/include/nav.php'); ?>
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Новости</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 news6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span class="label label-info">08.03.15 15:10</span>
						<strong style="margin-left: 10px;">Информация для тестеров</strong>
					</div>
					<div class="panel-body">
						<p>Добро пожаловать в ранюю альфа версию панели управления серверами.</p>
						<p>Функционал постепенно дописывается. Все косяки и предложения - мне (Анубису).</p>
						<br/><strong><font color=red>Отключайте плагины при игре на ESL!</font></strong><br/><br/>
						<p>rcon w - вармап с бесконечными деньгами, временем и киком ботов (включается автоматом при заходе первого игрока на сервер)</p>
						<p>rcon s - старт матча с автоматической записью GOTV демо (заканчивает писать при запуске вармапа/смене карты), ESL конфиг последний с полезными настройками, вроде у всех на сервере 128000 рейты итд</p>
						<p>rcon s30 - тоже самое, только играются полностью все 30 раундов (прак)</p>
						<p>rcon k - ножи</p>
						<p>rcon tr - тренировка, включаются sv_cheats итд, rcon w - отключает все команды тренировки, также все запуски матчей отключают это</p>
						<p>rcon rtr - тренировка раунда, тоже самое что rcon s, но маленький freezetime и 10000 денег</p>
						<p>rcon dmon - включить режим DM</p>
						<p>rcon dmoff - выключить режим DM</p>
						<p>rcon d2/mirage/cache/inferno/train/nuke/overpass/cbble/season - смена карты</p>
						<p>rcon aim - включает конфиг aim 1on1</p>
						<p>rcon flon - включает режим тренировки флешек/смоков/HE</p>
						<p>rcon floff - выключает режим тренировки флешек/смоков/HE</p>
						<p>rcon host_workshop_map ID - запуск workshop карты</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/metisMenu.min.js"></script>
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap.min.js"></script>
<script src="/js/sb-admin-2.js"></script>
<script src="/js/alertify.min.js"></script>
<script src="/js/gamecp.js"></script>
</body>
</html>
