<?
function update_settings($name, $pass, $rcon, $addons, $server){
		global $db;
		$name = urldecode($name);
		$arr = ["name" => $name, "passwd" => $pass, "rcon" => $rcon, "addons" => $addons];
		$query = $db->prepare("UPDATE `servers` SET `settings` = :settings WHERE `name` = :server");
		$query->bindParam(':settings', json_encode($arr), PDO::PARAM_STR);
		$query->bindParam(':server', $server, PDO::PARAM_STR);
		$query->execute();
		return 'OK';
}

function nginx_link($server, $file){
	global $db, $user;
	if($user['admin'] == 1){
		$query = $db->prepare("SELECT * FROM `servers` WHERE `name` =:name");
		$query->bindParam(':name', $server, PDO::PARAM_STR);
		$query->execute();
		$row = $query->fetch();
		
		$query = $db->prepare("SELECT * FROM `users` WHERE `id` = :id");
		$query->bindParam(':id', $row['user_id'], PDO::PARAM_STR);
		$query->execute();
		$row = $query->fetch();
		
		$user['nginx_key'] = $row['nginx_key'];
	}
	$domain = 'http://game.lepus.su';
	$dir = "/gotv/$server/";
	$time = time()+60*60*24;
	$key = str_replace("=", "", strtr(base64_encode(md5($time.$dir.$file.getenv("REMOTE_ADDR")." {$user['nginx_key']}", TRUE)), "+/", "-_"));
	return htmlspecialchars($domain.$dir.$file."?hash=$key&time=$time");
}

function get_servers(){
	global $db, $user; $i = ''; $count = 0;
	if($user['admin'] == 1){
		$query = $db->prepare("SELECT * FROM `servers` ORDER BY `port` ASC");
	}else{
		$query = $db->prepare("SELECT * FROM `servers` WHERE `user_id` = :id ORDER BY `port` ASC");
		$query->bindParam(':id', $user['id'], PDO::PARAM_STR);
	}

	$query->execute();
	if($query->rowCount() > 0){
		while($row=$query->fetch()){
			$server = server_info($row['name']);
			$i = $i."<li><a href=\"/index.php?do=info&server=".urlencode(base64_encode($row['name']))."\">".$server['name']."<br/><small>{$row['ip']}:{$row['port']}</small></a></li>";
			$count++;
		}
	}
	return array($i, $count);;
}

function bytesToSize1000($bytes){
    return @round($bytes / pow(1000, ($i = floor(log($bytes, 1000)))), 2);
}

function curl_query($link, $post){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.137 Safari/537.36');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		
		if($post !== null){
			curl_setopt($ch, CURLOPT_REFERER, 'https://lepus.su');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		
		$data = curl_exec($ch);	
		curl_close($ch);
		return $data;
}

function __autoload($class_name) {
	include $_SERVER['DOCUMENT_ROOT'].'/private/class/'.$class_name.'.class.php';
}

function get_access($name){
	global $db, $user;
	if($user['admin'] == '1'){
		$query = $db->prepare("SELECT * FROM `servers` WHERE `name` = :name");
		$query->bindParam(':name', $name, PDO::PARAM_STR);
	}else{
		$query = $db->prepare("SELECT * FROM `servers` WHERE `user_id` = :id AND `name` = :name");
		$query->bindParam(':id', $user['id'], PDO::PARAM_STR);
		$query->bindParam(':name', $name, PDO::PARAM_STR);
	}
	
	$query->execute();
	if($query->rowCount() != 1)
		$i =  ["accsess" => FALSE]; 
	else
		$i = ["accsess" => TRUE, "data" => $query->fetch()];
	
	return $i;
}

function server_info($name){
	$row = get_access($name);
	if(!$row["accsess"]) die("no_accsess");
	if(!empty($row['data']['settings'])){
		$j = json_decode($row['data']['settings'], true);
	}else{
		$j = ["name" => NULL, "passwd" => NULL, "rcon" => NULL, "addons" => NULL];
	}
	$i = ["ip" => $row['data']['ip'], "port" => $row['data']['port'], "name" => $j['name'], "passwd" => $j['passwd'], "rcon" => $j['rcon'], "addons" => @$j['addons']];
	return $i;
}

function csgo_info($ip, $port){
	define( 'SQ_SERVER_ADDR', $ip );
	define( 'SQ_SERVER_PORT', $port );
	define( 'SQ_TIMEOUT',     1 );
	define( 'SQ_ENGINE',      SourceQuery :: SOURCE );
	
	$Query = new SourceQuery();
	$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
	//$i = ["info" => $Query->GetInfo(), "players" => $Query->GetPlayers()];
	$i = ["info" => $Query->GetInfo()];
	$Query->Disconnect();
	return $i;
}

function error($message){
	if(!is_array($message)){
		$err = [
			"no_auth" => "Неудачная попытка входа.",
			"no_user" => "Неправильный логин.",
			"bad_passwd" => "Неправильный пароль."
		];
		
		if (array_key_exists($message, $err)){
			die('error');
		}
	} else return $message;
}

function auth($login, $session){
	global $db;
	$query = $db->prepare("SELECT * FROM `users` WHERE `login` = :login AND `session` = :session");
	$query->bindParam(':login', $login, PDO::PARAM_STR);
	$query->bindParam(':session', $session, PDO::PARAM_STR);
	$query->execute();
	
	if($query->rowCount() != 1){
		$query = $db->prepare("UPDATE `users` SET `session` = NULL WHERE `login` = :login AND `session` = :session");
		$query->bindParam(':login', $login, PDO::PARAM_STR);
		$query->bindParam(':session', $session, PDO::PARAM_STR);
		$query->execute();
		
		session_unset();
		session_destroy();
		return 'no_auth';
	}
	
	$row = $query->fetch();
	return ["id" => $row['id'], "nginx_key" => $row['nginx_key'], "admin" => $row['admin']];
}

function login($login, $passwd){
	global $db;
	$query = $db->prepare("SELECT * FROM `users` WHERE `login` =:login");
	$query->bindParam(':login', $login, PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 1) return 'no_user';
	$row = $query->fetch();

	if (password_verify($passwd, $row['passwd'])){
		$new_passwd = rehash($passwd, $row['passwd']);
		$_SESSION['id'] = $row['id'];
		$_SESSION['login'] = $login;
		$_SESSION['sess'] = hash('sha256' ,$login.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
	
		if($new_passwd != 'no_hash'){	// Обновляем hash в базе
			$query = $db->prepare("UPDATE `users` SET `passwd` = :passwd WHERE `login` = :login");
			$query->bindParam(':passwd', $new_passwd, PDO::PARAM_STR);
			$query->bindParam(':login', $login, PDO::PARAM_STR);
			$query->execute();
		}
		
		$query = $db->prepare("UPDATE `users` SET `session` = :sess WHERE `login` = :login");
		$query->bindParam(':login', $login, PDO::PARAM_STR);
		$query->bindParam(':sess', $_SESSION['sess'], PDO::PARAM_STR);
		$query->execute();
		
		return 'enter';
		
	} else return 'bad_passwd';
}

function rehash($passwd, $hash){
	if (password_needs_rehash($hash, PASSWORD_DEFAULT))
		return password_hash($passwd, PASSWORD_DEFAULT);
	else
		return 'no_hash';
}

function gen_passwd($a){
		return password_hash($a, PASSWORD_DEFAULT, ['cost' => 12]);
}

function generateRandomString($length) 
{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';	
		for ($i = 0; $i < $length; $i++)
		{
			$randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		return $randomString;
}

function _exit(){
		global $conf;
		session_unset();
		session_destroy();
		header("Location: {$conf['url']}");
}
