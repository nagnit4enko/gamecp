<?
function del_user(){
	global $db; $uid = $_GET['uid'];
	
	if(!isset($uid)) return 'empty uid';
	if(!is_numeric($uid)) return 'not int uid';
	
	$query = $db->prepare("SELECT * FROM `users` WHERE `id` =:id");
	$query->bindParam(':id', $uid, PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 1) return 'error';
	
	$query = $db->prepare("DELETE FROM `users` WHERE `id` =:id");
	$query->bindParam(':id', $uid, PDO::PARAM_STR);
	$query->execute();
	return 'OK';
}

function reg_user(){
	global $db; $login = $_GET['login']; $hash = $_GET['hash'];
	
	if(!isset($login)) return 'empty login';
	if(!isset($hash)) return 'empty hash';
	
	$query = $db->prepare("SELECT * FROM `users` WHERE `id` =:id");
	$query->bindParam(':id', $uid, PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 0) return 'error';
	
	$query = $db->prepare("INSERT INTO `users` (`login`, `passwd`, `nginx_key`) VALUES (:login, :hash, :nginx)");
	$query->bindParam(':login', $login, PDO::PARAM_STR);
	$query->bindParam(':hash', $hash, PDO::PARAM_STR);
	$query->bindParam(':nginx', generateRandomString(8), PDO::PARAM_STR);
	$query->execute();
	return 'OK';
}

function passwd_user(){
	global $db; $uid = $_GET['uid']; $hash = $_GET['hash'];
	
	if(!isset($uid)) return 'empty uid';
	if(!isset($hash)) return 'empty hash';
	if(!is_numeric($uid)) return 'not int uid';
	
	$query = $db->prepare("SELECT * FROM `users` WHERE `id` =:id");
	$query->bindParam(':id', $uid, PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 1) return 'error';
	
	$query = $db->prepare("UPDATE `users` SET `passwd` = :hash WHERE `id` =:id");
	$query->bindParam(':id', $uid, PDO::PARAM_STR);
	$query->bindParam(':hash', $hash, PDO::PARAM_STR);
	$query->execute();
	return 'OK';
}

function status_user(){
	global $db; $uid = $_GET['uid']; $status = $_GET['status'];

	if(!isset($uid)) return 'empty uid';
	if(!isset($status)) return 'empty status';
	if(!is_numeric($uid)) return 'not int uid';
	if(!is_numeric($status)) return 'not int status';
	if($status != 0 && $status != 1) return "wrong status";
	
	$query = $db->prepare("SELECT * FROM `users` WHERE `id` =:id");
	$query->bindParam(':id', $uid, PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 1) return 'error';
	
	$query = $db->prepare("UPDATE `users` SET `block` = :status WHERE `id` =:id");
	$query->bindParam(':id', $uid, PDO::PARAM_STR);
	$query->bindParam(':status', $status, PDO::PARAM_STR);
	$query->execute();
	
	// if status=1 -> get_list_user_servers and shutdown it.
	// if status=0 -> get_list_user_servers and turnon it.
	return 'OK';
}
