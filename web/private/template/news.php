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
<div id="ModalSettings" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Изменить пароль</h4>
			</div>
			<div id="modal_info" class="modal-body"><center>
				<p><input class="form-control input-sm" id="my_password" style="display:inline; position:relative;top:2px;width:300px;" type="password" placeholder="Старый пароль"> </p>
				<p><input class="form-control input-sm" id="new_password" style="display:inline; position:relative;top:2px;width:300px;" type="password" placeholder="Новый пароль"> </p>
				<p><input class="form-control input-sm" id="repeat_password" style="display:inline; position:relative;top:2px;width:300px;" type="password" placeholder="Повторите пароль"> </p></center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-save-settings>Сохранить</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<div id="wrapper">
	<!-- Navigation -->
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Навигация</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Панель управления <? if ($user['admin'] == 1) echo '(админ)'?></a>
		</div>
			<!-- /.navbar-header -->
		<ul class="nav navbar-top-links navbar-right">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-user fa-fw"></i>
					<i class="fa fa-caret-down"></i>
				</a>
			<ul class="dropdown-menu dropdown-user">
				<li><a data-change-settings href="#settings" aria-controls="settings"><i class="fa fa-gear fa-fw"></i> Изменить пароль</a></li>
				<li class="divider"></li>
				<li><a href="/?do=exit"><i class="fa fa-sign-out fa-fw"></i> Выход</a></li>
			</ul>
			</li>
		</ul>
		<div class="navbar-default sidebar" role="navigation">
			<div class="sidebar-nav navbar-collapse">
				<ul class="nav" id="side-menu">
					<li><a href="/index.php?do=default"><i class="fa fa-newspaper-o fa-fw"></i> Новости</a></li>
					<li>
						<? if($menu[1] > 1) echo '<a href="#"><i class="fa fa-desktop"></i> CS:GO Сервера<span class="fa arrow"></span></a>'; else echo '<a href="#"><i class="fa fa-desktop"></i> CS:GO Сервер<span class="fa arrow"></span></a>'; ?>
						<ul class="nav nav-second-level"><? echo $menu[0]; ?></ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
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
<script>
	$(document).on("click", "[data-save-settings]", function(e) {
		$(this).blur();
		e.preventDefault();
		pass = $('input[id=my_password]').val();
		npass = $('input[id=new_password]').val();
		rpass = $('input[id=repeat_password]').val();
		$.post("http://"+document.domain+"/public/change_password.php", {pass: pass, npass: npass, rpass: rpass}, function( data ){
			$('#ModalSettings').modal('hide');
			$("#my_password").val('');;
			$("#new_password").val('');;
			$("#repeat_password").val('');;
			if(data == 'OK'){
				alertify.success('Выполнено');
				setTimeout("document.location.href='http://game.lepus.su/'", 1500);
				} else {
				alertify.error(data);
			}
			return;
		});
	});
	$(document).on("click", "[data-change-settings]", function(e) {
		e.preventDefault();
		$(this).blur();
		$('#ModalSettings').modal('show');
	});
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
				stateSave: true
        });
    });
</script>
</body>
</html>
