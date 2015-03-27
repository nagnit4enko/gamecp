	var lock = 0;
	
	$(document).on("click", "[data-server-create]", function(e) {
		$(this).blur();
		e.preventDefault();
		cid = $('select[id=server_create_id]').val();
		$.post("http://"+document.domain+"/public/create_server.php", {cid: cid}, function( data ){
			$('#ModalAdd').modal('hide');
			if(data == 'OK'){
				alertify.success('Выполнено');
				} else {
				alertify.error(data);
			}
			return;
		});
	});	
	
	$(document).on("click", "[data-add-server]", function(e) {
		$(this).blur();
		e.preventDefault();
		$('#ModalAdd').modal('show');
	});
	
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
