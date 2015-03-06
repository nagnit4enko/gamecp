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
<div id="wrapper">
	<!-- Navigation -->
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Панель управления</a>
		</div>
			<!-- /.navbar-header -->
		<ul class="nav navbar-top-links navbar-right">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-user fa-fw"></i>
					<i class="fa fa-caret-down"></i>
				</a>
			<ul class="dropdown-menu dropdown-user">
				<li><a href="/?do=settings"><i class="fa fa-gear fa-fw"></i> Настройки</a></li>
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
						<a href="#"><i class="fa fa-desktop"></i> CS:GO Сервера<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level"><? echo $menu; ?></ul>
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
						<span class="label label-info">01.03.15 19:03</span>
						<strong style="margin-left: 10px;">Основной функционал</strong>
					</div>
					<div class="panel-body">
						<p> - Авторизация.<br/>
							- Вывод console.log CSGO сервера.<br/>
							- Возможность обновить версию сервера.<br/>
							- Возможность скачивать демки из панели.<br/>
							- Перезагрузка/ включение/ выключение CSGO сервера.<br/>
							- Вывод информации о CSGO  сервере: онлайн, карта, версия.<br/>
							<br/>---<br/><br/>
							1) выбор редактирование конфигов<br/>
							2) выбор/отключение античита<br/>
							3) выбор гейммода<br/>
							4) выбор карты для старта<br/>
							5) ввод workshop ключа<br/>
							6) отключение/включение моих плагинов<br/>
						</p>
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
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
				stateSave: true
        });
    });
</script>
</body>
</html>
