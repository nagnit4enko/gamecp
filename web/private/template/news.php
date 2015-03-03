<? $server_info = csgo_info('88.198.25.76', '27024');
   $server_log = curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user=csgoserver10&cmd=log", NULL);
   $menu = get_servers(); 
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
                    <h1 class="page-header">News</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
				<div class="col-md-12 news6">
				<div class="panel panel-default">
				<div class="panel-heading">
				<span class="label label-info">01.03.15 19:03</span>
				<strong style="margin-left: 10px;">Основной функционал</strong>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
				<p> - Авторизация.<br/>
					- Вывод console.log CSGO сервера.<br/>
					- Возможность скачивать демки из панели.<br/>
					- Перезагрузка/ включение/ выключение CSGO сервера.<br/>
					- Вывод информации о CSGO  сервере: онлайн, карта, версия.<br/>
				</p>
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
</body>

</html>
