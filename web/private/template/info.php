<? 
	$server_name = base64_decode(urldecode($_GET['server']));
	if(empty($server_name)) die("empty server name");
	if(preg_match('/[^0-9a-z]/', $server_name)) die('error server name');
	
	$server = server_info($server_name);

	$server_info = csgo_info($server['ip'], $server['port']);
	$server_log = remove_xss(curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$server_name}&cmd=log", NULL));
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
    <title>Панель управления</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/metisMenu.min.css" rel="stylesheet">
    <link href="/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/css/dataTables.responsive.css" rel="stylesheet">
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="/css/alertify.core.css" rel="stylesheet">
	<link href="/css/alertify.bootstrap.css" rel="stylesheet">
	<link href="/css/bootstrap-select.css" rel="stylesheet">
</head>
<body>
<div id="myModal" class="modal fade" data-backdrop="static">
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
		<ul class="nav navbar-top-links navbar-right">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i></a>
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
				<h2 class="page-header">
				<? if(isset($server_info['info']['HostName'])) echo '<img src="img/online.png" style="margin-top:-4px" alt="online"> '; else if(go_issuspended($server_name) == 1) echo '<img src="img/block.png" style="margin-top:-4px" alt="block"> '; else echo '<img src="img/offline.png" style="margin-top:-4px" alt="offline"> ';
				if(isset($server_info['info']['HostName'])) echo "<lu id=\"sname\">".strip_tags(str_replace(" by lepus.su", "", $server_info['info']['HostName']))."</lu>"; else echo "<lu id=\"sname\">".strip_tags($server['name'])."</lu>"; ?>
				&nbsp;
				<a data-server-restart="<? echo $server_name; ?>" href="#" data-toggle="tab" class="btn btn-danger <? if(go_issuspended($server_name) == 1) echo "disabled"; ?>"><span class="glyphicon glyphicon-refresh"></span> Перезагрузить</a>
				<a data-server-update="<? echo $server_name; ?>" href="#" data-toggle="tab" class="btn btn-primary <? if(go_issuspended($server_name) == 1) echo "disabled"; ?>"><span class="glyphicon glyphicon-download-alt"></span> Обновить сервер</a>
				<? if($user['admin'] == 1) if(isset($server_info['info']['HostName'])) echo '&nbsp;<input data-server-stop='.$server_name.' type="submit" class="btn btn-default" value="Выключить">'; else echo '&nbsp;<input data-server-start='.$server_name.' type="submit" class="btn btn-default" value="Включить">'; ?>
				<? if($user['admin'] == 1) if(go_issuspended($server_name) == 0) echo '<input data-server-suspend='.$server_name.' type="submit" class="btn btn-default" value="Заблокировать">'; else echo '<input data-server-unsuspend='.$server_name.' type="submit" class="btn btn-warning" value="Разблокировать">'; ?>
				</h2>
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
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Игра</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? if(isset($server_info['info']['ModDesc'])) echo $server_info['info']['ModDesc']; else if(go_issuspended($server_name) == 1) echo "сервер заблокирован"; else echo "сервер выключен"; ?></span>
						</div>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Локация</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1">Germany</span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">IP</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo "{$server['ip']}:{$server_info['info']['GamePort']}"; ?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">GOTV</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? if(isset($server_info['info']['SpecPort'])) echo "{$server['ip']}:{$server_info['info']['SpecPort']}"; else echo "выключено";?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Карта</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? if (strpos($server_info['info']['Map'], 'workshop') !== false) echo strip_tags(substr(strstr($server_info['info']['Map'], '/'), 1)); else echo strip_tags($server_info['info']['Map']); ?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Онлайн</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo intval($server_info['info']['Players'])."/".intval($server_info['info']['MaxPlayers']); ?></span>
						</div></p>
							<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Плагины</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo strip_tags(($server['addons'] == 2 ? true : false) ? 'выключены' : 'включены'); ?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">VAC</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo strip_tags(($server_info['info']['Secure'] ? true : false) ? 'включен' : 'выключен'); ?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Версия</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo strip_tags($server_info['info']['Version']); ?></span>
						</div></p>
						<div class="panel panel-default" style="width:334px">
							<div class="panel-heading" style="height:auto; padding: 6px 22px; color: #555;">Подключение</div>
							<div class="panel-body" style="height:auto; padding: 6px 22px;"><a href="steam://connect/<? echo "{$server['ip']}:{$server['port']}/{$server['passwd']}"?>">connect <? echo "{$server['ip']}:{$server['port']}; password {$server['passwd']}"?></a></div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="console">
					<div class="panel-body">
						<pre id="log_<? echo $server_name; ?>" style="max-height:580px;overflow:auto;"> <? echo $server_log; ?> </pre>
						<a data-server-log="<? echo $server_name; ?>" href="#" data-toggle="tab" class="btn btn-primary <? if(go_issuspended($server_name) == 1) echo "disabled"; ?>"><span class="glyphicon glyphicon-repeat"></span> Обновить</a>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="config">
					<div class="panel-body">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Название</span>
							<input id="server_name" type="text" maxlength="15" class="form-control" placeholder="hostname" value="<? echo $server['name']; ?>" style="width:238px" aria-describedby="basic-addon1" autofocus value="value <? echo $server['name']; ?>" onfocus="this.value = this.value">
						</div>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Пароль</span>
							<input id="server_pass" type="text" maxlength="15" class="form-control" placeholder="sv_password" value="<? echo $server['passwd']; ?>" style="width:238px" aria-describedby="basic-addon1">
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">RCON</span>
							<input id="server_rcon" type="text" maxlength="15" class="form-control" placeholder="rcon_password" value="<? echo $server['rcon']; ?>" style="width:238px" aria-describedby="basic-addon1">
						</div></p>
						<p><div class="input-group">
						<span class="input-group-addon" id="basic-addon1" style="width:96px">Плагины</span>
						<select id="server_addons" class="selectpicker" data-width="239px">
							<option value="1" <? if($server['addons'] == 1) echo 'selected="selected"'; ?>>включены</option>
							<option value="2" <? if($server['addons'] == 2) echo 'selected="selected"'; ?>>выключены</option>
						</select>
						</div></p>
						<a data-server-cnf="<? echo $server_name; ?>" href="#" data-toggle="tab" class="btn btn-success <? if(go_issuspended($server_name) == 1) echo "disabled"; ?>"><span class="glyphicon glyphicon-ok"></span> Сохранить</a>
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
<script src="/js/bootstrap-select.js"></script>
<script src="/js/htmlentities.js"></script>
<script>
	var lock = 0;
	
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
	
	$(document).on("click", "[data-server-cnf]", function(e) {
		$(this).blur();
		if(lock == 1) return;
		lock = 1;
		$('#myModal').modal('show');
		$("#modal_info").html("<center>Специально обученная обезьяна меняет настройки и перезагружает ваш сервер.</center>");
		server_name = $('input[id=server_name]').val();
		server_pass = $('input[id=server_pass]').val();
		server_rcon = $('input[id=server_rcon]').val();
		server_addons = $('select[id=server_addons]').val();
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'cnf', user: $(this).data("server-cnf"), name: server_name, pass: server_pass, rcon: server_rcon, addons: server_addons}, function( data ){
				$('#myModal').modal('hide');
				if(data == 'OK'){
					$("#sname").html(server_name);
					alertify.success('Выполнено');
					lock = 0;
					return;
				} else {
					alertify.error(data);
					lock = 0; 
					return;
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
		if(lock == 1) return;
		lock = 1;
		$('#myModal').modal('show');
		$("#modal_info").html("<center>Специально обученная обезьяна перезагружает ваш сервер.</center>");
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'restart', user: $(this).data("server-restart")}, function( data ){
				$('#myModal').modal('hide');
				if(data == 'OK'){
					lock = 0; alertify.success('Выполнено'); return;
				} else {
					lock = 0; alertify.error('Ошибка'); return;
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
					alertify.error(data); return;
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
		if(lock == 1) return;
		lock = 1;
		$('#myModal').modal('show');
		$("#modal_info").html("<center>Специально обученная обезьяна обновляет ваш сервер.</center>");
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'update-restart', user: $(this).data("server-update")}, function( data ){
				$('#myModal').modal('hide');
				if(data == 'OK'){
					lock = 0; alertify.success('Выполнено'); return;
				} else {
					lock = 0; alertify.error('Ошибка'); return;
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
					//$("#"+div_name).html(htmlentities(data, "ENT_NOQUOTES"));
					$("#"+div_name).html(data);
						var objDiv = document.getElementById(div_name);
						objDiv.scrollTop = objDiv.scrollHeight;
					alertify.success('Обновлено'); return;
				}
		});
	});
	
	$(document).on("click", "[data-server-suspend]", function(e) {
		$(this).blur();
		$('#myModal').modal('show');
		$("#modal_info").html("<center>Сервер замораживается.</center>");
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'suspend', user: $(this).data("server-suspend")}, function( data ){
				$('#myModal').modal('hide');
				if(data == 'OK'){
					alertify.success('Выполнено'); return;
				} else {
					alertify.error(data); return;
				}
		});
	});
	
		$(document).on("click", "[data-server-unsuspend]", function(e) {
		$(this).blur();
		$('#myModal').modal('show');
		$("#modal_info").html("<center>Сервер размораживается.</center>");
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'unsuspend', user: $(this).data("server-unsuspend")}, function( data ){
				$('#myModal').modal('hide');
				if(data == 'OK'){
					alertify.success('Выполнено'); return;
				} else {
					alertify.error(data); return;
				}
		});
	});
</script>
</body>
</html>
