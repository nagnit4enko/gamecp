<? 
	$server_name = base64_decode(urldecode($_GET['server']));
	if(empty($server_name)) die("empty server name");
	if(preg_match('/[^0-9a-z]/', $server_name)) die('error server name');
	
	$server = server_info($server_name);

	$server_info = csgo_info($server['ip'], $server['port']);
	$server_log = curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$server_name}&cmd=log", NULL);
	$server_cnf = curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$server_name}&cmd=cnf", NULL);
	$menu = get_servers();
	
	$server_demo = curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$server_name}&cmd=gotv", NULL);
	$demo_arr = json_decode($server_demo, true);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Панель уравления</title>
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
<div id="myModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<center> <h4 class="modal-title">Пожалуйста, подождите.</h4> </center>
			</div>
			<div id="modal_info" class="modal-body"></div>
		</div>
	</div>
</div>
<div id="myModal2" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div id="modal_info" class="modal-body">
				Вы уверены?
			</div>
			<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Удалить</button>
			<button type="button" data-dismiss="modal" class="btn btn-primary">Отмена</button>
			</div>
		</div>
	</div>
</div>
<div id="wrapper">
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Навигация</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Панель управления</a>
		</div>
		<ul class="nav navbar-top-links navbar-right">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i></a>
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
				<h1 class="page-header">
				<?
				if(!empty($server_info['info']['ModDir'])) echo '<img src="img/online.png" alt="online">';
					else echo '<img src="img/offline.png" alt="offline">';
				echo strip_tags($server_info['info']['HostName']);
				?>
				&nbsp;
				<!--<input data-server-start="<? echo $server_name; ?>" type="submit" class="btn btn-primary" value="Включить">
				<input data-server-stop="<? echo $server_name; ?>" type="submit" class="btn btn-primary" value="Выключить">-->
				<input data-server-restart="<? echo $server_name; ?>" type="submit" class="btn btn-danger" value="Перезагрузить">
				<input data-server-update="<? echo $server_name; ?>" type="submit" class="btn btn-primary" value="Обновить сервер">
				</h1>
			</div>
		</div>
		<div role="tabpanel">
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">О сервере</a></li>
				<li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">Настройки</a></li>
				<li role="presentation"><a href="#gotv" aria-controls="gotv" role="tab" data-toggle="tab">Демо</a></li>
				<li role="presentation"><a href="#console" aria-controls="console" role="tab" data-toggle="tab">Консоль</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="info" style="width:412px">
					<div class="panel-body">
<!-- 						<div class="panel panel-default">
							<ul class="list-group">
								<li class="list-group-item">Игра: <? echo strip_tags($server_info['info']['ModDesc']); ?></li>
								<li class="list-group-item">123</li>
								<li class="list-group-item">123</li>
								<li class="list-group-item">123</li>
								<li class="list-group-item">123</li>
							</ul>
						</div> -->
						
						
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Игра</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo strip_tags($server_info['info']['ModDesc']); ?></span>
						</div>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">IP</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo "{$server['ip']}:{$server_info['info']['GamePort']}"; ?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">GOTV</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo "{$server['ip']}:{$server_info['info']['SpecPort']}"; ?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Карта</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo strip_tags($server_info['info']['Map']); ?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Онлайн</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo intval($server_info['info']['Players'])."/".intval($server_info['info']['MaxPlayers']); ?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">VAC</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo strip_tags(($server_info['info']['Secure'] ? true : false) ? 'включен' : 'выключен'); ?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Версия</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo strip_tags($server_info['info']['Version']); ?></span>
						</div></p>
						
						
<!-- 					Игра: <? echo strip_tags($server_info['info']['ModDesc']); ?><br/>
						IP: <? echo "{$server['ip']}:{$server_info['info']['GamePort']}"; ?> <br/>
						GOTV: <? echo "{$server['ip']}:{$server_info['SpecPort']}"; ?> <br/>
						Карта: <? echo strip_tags($server_info['info']['Map']); ?><br/>
						Онлайн: <? echo intval($server_info['info']['Players'])."/".intval($server_info['info']['MaxPlayers']); ?><br/>
						VAC: <? echo strip_tags(($server_info['info']['Secure'] ? true : false) ? 'включен' : 'выключен'); ?><br/>
						Версия: <? echo strip_tags($server_info['info']['Version']); ?><br/> -->
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="console">
					<div class="panel-body">
						<pre id="log_<? echo $server_name; ?>" style="max-height:580px;overflow:auto;"> <? echo $server_log; ?> </pre>
						<input data-server-log="<? echo $server_name; ?>" type="submit" class="btn btn-primary" value="Обновить">
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="config">
					<div class="panel-body">
						<!--<textarea class="form-control" type="text" style="width:100%;height:550px;overflow:auto;resize:vertical;"><? echo $server_cnf; ?></textarea><br/>-->
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Название</span>
							<input id="server_name" type="text" class="form-control" placeholder="Lepus Server" value="<? echo $server['name']; ?>" style="width:238px" aria-describedby="basic-addon1">
						</div>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Пароль</span>
							<input id="server_pass" type="text" class="form-control" placeholder="pcw" value="<? echo $server['passwd']; ?>" style="width:238px" aria-describedby="basic-addon1">
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">RCON</span>
							<input id="server_rcon" type="text" class="form-control" placeholder="123" value="<? echo $server['rcon']; ?>" style="width:238px" aria-describedby="basic-addon1">
						</div></p>
						<input data-server-cnf="<? echo $server_name; ?>" type="submit" class="btn btn-success" value="Сохранить">
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="gotv">
					<div class="panel-body">
						<div class="dataTable_wrapper">
							<table class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead>
									<tr>
										<th style="text-align: center;">Время</th>
										<th style="text-align: center;">Карта</th>
										<th style="text-align: center;">Размер (мб)</th>
										<th style="text-align: center;">Скачать</th>
										<th style="text-align: center;">Удалить</th>
									</tr>
								</thead>
								<tbody>
								<?
								$tr = 0;
								foreach ($demo_arr as $val) {
									if(empty($val)) continue;
									echo "<tr id=\"$tr\">";
									echo "<td><center>".strip_tags($val['time'])."</center></td>";
									echo "<td><center>".strip_tags(strstr(substr($val['name'], 0, strpos($val['name'], '.')), 'de'))."</center></td>";
									echo "<td><center>".bytesToSize1000(intval($val['size']))."</center></td>";
									echo "<td><center><a href=\"".nginx_link($server_name, strip_tags($val['name']))."\" title=\"Скачать\"><i class=\"fa fa-download fa-fw\"></i></a></center></td>";
									echo "<td><center><a data-delete-id=\"$tr\" data-server-name=\"$server_name\" data-demo-name=\"".strip_tags($val['name'])."\" href=\"#\" title=\"Удалить\"><i class=\"glyphicon glyphicon-remove\"></i></a></center></td>";
									echo "</tr>";
									$tr++;
								}
								?>
								</tbody>
							</table>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script> 
<script src="/js/bootstrap-hover-dropdown.min.js"></script>
<script src="/js/metisMenu.min.js"></script>
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap.min.js"></script>
<script src="/js/sb-admin-2.js"></script>
<script src="/js/alertify.min.js"></script>
<script>
	$(document).on("click", "[data-server-cnf]", function(e) {
		$(this).blur();
		server_name = $('input[id=server_name]').val();
		server_pass = $('input[id=server_pass]').val();
		server_rcon = $('input[id=server_rcon]').val();
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'cnf', user: $(this).data("server-cnf"), name: server_name, pass: server_pass, rcon: server_rcon}, function( data ){
				if(data == 'OK'){
					alertify.success('Выполнено'); return;
				} else {
					alertify.error(data); return;
				}
		});
	});
	
	$(document).on("click", "[data-delete-id]", function(e) {
		e.preventDefault();
		var table = $('#dataTables-example').dataTable();
		tr_id = $(this).data("delete-id");
		user = $(this).data("server-name");
		file = $(this).data("demo-name");
		$('#myModal2').modal('show')
		.one('click', '#delete', function (e) {
			$.post("http://"+document.domain+"/public/cmd.php", { command: 'delete', user: user, file: file}, function( data ){
				if(data == 'OK'){
					table.fnDeleteRow(table.$("#"+tr_id));
					alertify.success('Запись игры удалена');
					return;
				} else {
					alertify.error('Ошибка'); return;
				}
			});
		});
	});

	$(document).ready(function() {
		$('#dataTables-example').DataTable({
			stateSave: true
		});
	});

	$(".spoiler-trigger").click(function() {
		$(this).blur();
		$(this).parent().next().collapse('toggle');
	});

	$(document).on("click", "[data-server-restart]", function(e) {
		$(this).blur();
		$('#myModal').modal('show');
		$("#modal_info").html("<center>Специально обученная обезьяна перезагружает ваш сервер.</center>");
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'restart', user: $(this).data("server-restart")}, function( data ){
				$('#myModal').modal('hide');
				if(data == 'OK'){
					alertify.success('Выполнено'); return;
				} else {
					alertify.error('Ошибка'); return;
				}
		});
	});
	
	$(document).on("click", "[data-server-stop]", function(e) {
		$(this).blur();
		$('#myModal').modal('show');
		$("#modal_info").html("<center>Специально обученная обезьяна выключает ваш сервер.</center>");
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'stop', user: $(this).data("server-stop")}, function( data ){
				$('#myModal').modal('hide');
				if(data == 'OK'){
					alertify.success('Выполнено'); return;
				} else {
					alertify.error('Ошибка'); return;
				}
		});
	});
	
	$(document).on("click", "[data-server-start]", function(e) {
		$(this).blur();
		$('#myModal').modal('show');
		$("#modal_info").html("<center>Специально обученная обезьяна включает ваш сервер.</center>");
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'start', user: $(this).data("server-start")}, function( data ){
				$('#myModal').modal('hide');
				if(data == 'OK'){
					alertify.success('Выполнено'); return;
				} else {
					alertify.error('Ошибка'); return;
				}
		});
	});
	
	$(document).on("click", "[data-server-update]", function(e) {
		$(this).blur();
		$('#myModal').modal('show');
		$("#modal_info").html("<center>Специально обученная обезьяна обновляет ваш сервер.</center>");
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'update-restart', user: $(this).data("server-update")}, function( data ){
				$('#myModal').modal('hide');
				if(data == 'OK'){
					alertify.success('Выполнено'); return;
				} else {
					alertify.error('Ошибка'); return;
				}
		});
	});
	
	$(document).on("click", "[data-server-gotv]", function(e) {
		$(this).blur();
		window.open('http://game.lepus.su/gotv/'+$(this).data("server-gotv")+'/', '_blank');
	});
	
		$(document).on("click", "[data-server-log]", function(e) {
		$(this).blur();
		div_name =	"log_"+$(this).data("server-log");
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'log', user: $(this).data("server-log")}, function( data ){
				if(data == 'error'){
					alertify.error('Ошибка'); return;
				} else {
					$("#"+div_name).html(data);
						var objDiv = document.getElementById(div_name);
						objDiv.scrollTop = objDiv.scrollHeight;
					alertify.success('Обновлено'); return;
				}
		});
	});
</script>
</body>
</html>
