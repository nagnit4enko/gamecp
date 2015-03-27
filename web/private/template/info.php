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
<? require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/include/modal.php'); ?>
<div id="wrapper">
<? require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/include/nav.php'); ?>
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
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? if(isset($server_info['info']['ModDesc'])) echo remove_xss($server_info['info']['ModDesc']); else if(go_issuspended($server_name) == 1) echo "сервер заблокирован"; else echo "сервер выключен"; ?></span>
						</div>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">Локация</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1">Germany</span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">IP</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? echo strip_tags("{$server['ip']}:{$server_info['info']['GamePort']}"); ?></span>
						</div></p>
						<p><div class="input-group">
							<span class="input-group-addon" id="basic-addon1" style="width:96px">GOTV</span>
							<span class="form-control" style="width:238px" aria-describedby="basic-addon1"><? if(isset($server_info['info']['SpecPort'])) echo strip_tags("{$server['ip']}:{$server_info['info']['SpecPort']}"); else echo "выключено";?></span>
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
							<div class="panel-body" style="height:auto; padding: 6px 22px;"><a href="steam://connect/<? echo remove_xss("{$server['ip']}:{$server['port']}/{$server['passwd']}");?>">connect <? echo remove_xss("{$server['ip']}:{$server['port']}; password {$server['passwd']}");?></a></div>
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
<script src="/js/gamecp.js"></script>
</body>
</html>
