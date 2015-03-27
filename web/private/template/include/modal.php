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
<div id="ModalAdd" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Добавить сервер</h4>
			</div>
			<div id="modal_info" class="modal-body">
				<center>
					<select class="form-control">
						<option>Counter-Strike: Global Offensive</option>
					</select>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-server-create>Создать</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
