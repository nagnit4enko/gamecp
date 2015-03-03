<?
function get_servers(){
	global $db, $user; $i = '';
	$query = $db->prepare("SELECT * FROM `servers` WHERE `user_id` = :id ORDER BY `port` DESC");
	$query->bindParam(':id', $user['id'], PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() > 0){
		while($row=$query->fetch()){
			$i = $i."<li><a href=\"/index.php?do=info&server=".urlencode(base64_encode($row['name']))."\"> {$row['ip']}:{$row['port']} </a></li>";
		}
	}
	return $i;
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


function server_info($name){
	global $db, $user;
	$query = $db->prepare("SELECT * FROM `servers` WHERE `user_id` = :id AND `name` = :name");
	$query->bindParam(':id', $user['id'], PDO::PARAM_STR);
	$query->bindParam(':name', $name, PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 1) die("no access");
	$row=$query->fetch();
	$i = ["ip" => $row['ip'], "port" => $row['port']];
	return $i;
}

function csgo_info($ip, $port){
	define( 'SQ_SERVER_ADDR', $ip );
	define( 'SQ_SERVER_PORT', $port );
	define( 'SQ_TIMEOUT',     1 );
	define( 'SQ_ENGINE',      SourceQuery :: SOURCE );
	
	$Query = new SourceQuery();
	$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
	$i = $Query->GetInfo();
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
	return ["id" => $row['id']];
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