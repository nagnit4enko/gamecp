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
					<? if($user['admin'] == 1) echo '<li><a data-add-server href="#addserver"><i class="fa fa-wrench fa-fw"></i> Новый сервер</a></li>'; ?>
					<li>
						<? if($menu[1] > 1) echo '<a href="#"><i class="fa fa-desktop"></i> CS:GO Сервера<span class="fa arrow"></span></a>'; else echo '<a href="#"><i class="fa fa-desktop"></i> CS:GO Сервер<span class="fa arrow"></span></a>'; ?>
						<ul class="nav nav-second-level"><? echo $menu[0]; ?></ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
