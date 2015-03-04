<? 
	$server_name = base64_decode(urldecode($_GET['server']));
	if(empty($server_name)) die("empty server name");
	if(preg_match('/[^0-9a-z]/', $server_name)) die('error server name');
	
	$server = server_info($server_name);

	$server_info = csgo_info($server['ip'], $server['port']);
	$server_log = curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$server_name}&cmd=log", NULL);
	$menu = get_servers();
	
	$server_demo = curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$server_name}&cmd=gotv", NULL);
	$demo_arr = explode(':', $server_demo);
	krsort($demo_arr);
	

	
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>gameCP</title>
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
                <a class="navbar-brand" href="/">GameCP</a>
            </div>
            <!-- /.navbar-header -->
<ul class="nav navbar-top-links navbar-right">
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
            <li><a href="/?do=settings"><i class="fa fa-gear fa-fw"></i> Settings</a>
            </li>
            <li class="divider"></li>
            <li><a href="/?do=exit"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </li>
        </ul>
    </li>
    </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
					    <li><a href="/index.php?do=default"><i class="fa fa-newspaper-o fa-fw"></i> News</a></li>
						<li>
                            <a href="#"><i class="fa fa-desktop"></i> CSGO Servers<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
								<? echo $menu; ?>
							</ul>
						</li>
                    </ul>
				
                </div>
				
            </div>
			
            <!-- /.navbar-static-side -->
        </nav>
		
		

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><? echo $server_info['HostName']; ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			
			
			<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Информация</a></li>
    <li role="presentation"><a href="#console" aria-controls="console" role="tab" data-toggle="tab">Консоль</a></li>
    <li role="presentation"><a href="#gotv" aria-controls="gotv" role="tab" data-toggle="tab">Демо</a></li>
	<li role="presentation" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#" role="button" aria-expanded="false">Управление <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
		<li role="presentation"><a href="#start" data-toggle="dropdown">Включить</a></li>
		<li role="presentation"><a href="#stop" data-toggle="dropdown">Выключить</a></li>
		<li role="presentation"><a href="#restart" data-toggle="dropdown">Перезагрузить</a></li>
		<li role="presentation"><a href="#update" data-toggle="dropdown">Обновить</a></li>
    </ul>
  </li>	

  </ul>

  

  <!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane fade in active" id="info">
  
 <p><p>
  		IP: <? echo "{$server['ip']}:{$server['port']}"; ?> <br/>
				Online: <? echo $server_info['Players']."/".$server_info['MaxPlayers']; ?><br/>
					Map: <? echo $server_info['Map']; ?><br/>
					Server version: <? echo $server_info['Version']; ?>
				
  

   <p><p>
  
  </div>
  <div role="tabpanel" class="tab-pane fade" id="console">
  
   <p><p>
  <pre id="log_<? echo $server_name; ?>" style="max-height:600px;overflow:auto;"> <? echo $server_log; ?> </pre>
  			<input data-server-log="<? echo $server_name; ?>" type="submit" class="btn btn-info" value="Обновить">
  
   </p></p>
  
  
  
  
  </div>
  
  
  
  
  
  
  <div role="tabpanel" class="tab-pane fade" id="gotv">
  
  <p><p>
  					<div class="panel panel-default">
 
     
          <div class="panel-body">

		<div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
											<th style="text-align: center;">ID</th>
											<th style="text-align: center;">Size</th>
											<th style="text-align: center;">Download</th>
											<th style="text-align: center;">Delete</th>
											

                                        </tr>
                                    </thead>
									<tbody>
<?
foreach ($demo_arr as $val) {
	if(empty($val)) continue;
	echo "<tr>";
	echo "<td><center>$val</center></td>";
	echo "<td><center>32Mb</center></td>";
	echo "<td><center>X</center></td>";
	echo "<td><center>XX</center></td>";
	echo "</tr>";
}
?>
									</tbody>
									</table>
                            </div>		  

			</div>
       
      </div>
  
  
     </p></p>
  
  </div>
  <div role="tabpanel" class="dropdown-menu" id="restart">
  

  	
				<input data-server-restart="<? echo $server_name; ?>" type="submit" class="btn btn-primary btn-xs" value="restart">
				<input data-server-stop="<? echo $server_name; ?>" type="submit" class="btn btn-primary btn-xs" value="stop">
				<input data-server-start="<? echo $server_name; ?>" type="submit" class="btn btn-primary btn-xs" value="start">
				<input data-server-update="<? echo $server_name; ?>" type="submit" class="btn btn-primary btn-xs" value="server update">
			

  
  
  
  
  
  
  
  </div>
  
</div>

</div>	
			<div role="tabpanel" class="tab-pane fade" id="stop">
			
			
			</div>
			
			
			
     
			

		
  
            <!-- /.row -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

	<script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script> 
	<script src="/js/bootstrap-hover-dropdown.min.js"></script>
    <script src="/js/metisMenu.min.js"></script>
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/dataTables.bootstrap.min.js"></script>
    <script src="/js/sb-admin-2.js"></script>
	<script src="/js/alertify.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
				stateSave: true
        });
    });
    </script>
	
	<script>
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
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'update', user: $(this).data("server-update")}, function( data ){
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
		//$('#myModal').modal('show');
		//$("#modal_info").html("<center>Специально обученная обезьяна достает логи сервера.</center>");
		$.post("http://"+document.domain+"/public/cmd.php", {command: 'log', user: $(this).data("server-log")}, function( data ){
				//$('#myModal').modal('hide');
				if(data == 'error'){
					alertify.error('Ошибка'); return;
				} else {
					$("#"+div_name).html(data);
						var objDiv = document.getElementById(div_name);
						objDiv.scrollTop = objDiv.scrollHeight;
					alertify.success('Выполнено'); return;
				}
		});
	});
	

	$(".spoiler-trigger").click(function() {
		$(this).parent().next().collapse('toggle');
		//$("html, body").animate({ scrollTop: $(document).height() }, 1000);
	});
	</script>
</body>

</html>
