<? $server_info = csgo_info('88.198.25.76', '27024');
   $server_log = curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user=csgoserver10&cmd=log", NULL);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Midas-Bank: wallet</title>
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
                <a class="navbar-brand" href="/">Midas Bank</a>
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
                        <li>
                            <a href="/"><i class="fa fa-newspaper-o fa-fw"></i> News</a>
                        </li>
						<li>
                            <a href="/"> Menu2</a>
                        </li>
                    </ul>
                </div>
				
            </div>
			
            <!-- /.navbar-static-side -->
        </nav>
		
		

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">News</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
				<div class="col-md-12 news6">
				<div class="panel panel-default">
				<div class="panel-heading">
				<strong style="margin-left: 0px;">CSGO 88.198.25.76:27024</strong> 
				<input data-server-restart="csgoserver10" type="submit" class="btn btn-primary btn-xs" value="restart">
				<input data-server-stop="csgoserver10" type="submit" class="btn btn-primary btn-xs" value="stop">
				<input data-server-start="csgoserver10" type="submit" class="btn btn-primary btn-xs" value="start">
				<input data-server-log="csgoserver10" type="submit" class="btn btn-primary btn-xs" value="update log">
				<input data-server-gotv="csgoserver10" type="submit" class="btn btn-primary btn-xs" value="GOTV">
				</div><!-- /.panel-heading -->
				<div class="panel-body">
				
				
				<p>Online: <? echo $server_info['Players']."/".$server_info['MaxPlayers']; ?><br/>
					Map: <? echo $server_info['Map']; ?><br/>
					Server version: <? echo $server_info['Version']; ?></p>
					
				<pre id="log_csgoserver10" style="max-height:300px;overflow:auto;"> <? echo $server_log; ?> </pre>
				
				</div><!-- /.panel-body -->
				</div>
				</div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
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
	</script>
</body>

</html>
