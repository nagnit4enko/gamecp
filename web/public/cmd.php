<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func/main.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func/csgo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/sess.php');

if(empty($_POST['command']) || empty($_POST['user'])) die('empty');
if(preg_match('/[^0-9a-z]/', $_POST['user'])) die('er_user');

$query = $db->prepare("SELECT * FROM `servers` WHERE `id` =:id AND `go_status` > UNIX_TIMESTAMP(NOW())");
$query->bindParam(':id', $_POST['user'], PDO::PARAM_STR);
$query->execute();	
if($query->rowCount() == 1) die("lock!");
if($user['admin'] != 1 && go_issuspended($_POST['user']) == 1) die('server block');

$commands = ["restart", "stop", "start", "log", "update-restart", "delete", "cnf", "addons", "suspend", "unsuspend"];
if (!in_array($_POST['command'], $commands)) die('er_command');

if($_POST['command'] == 'delete'){
	if(empty($_POST['file'])) die('empty');
	if(preg_match('/[^0-9a-zA-Z_.-]/', $_POST['file'])) die('er_file');
	$_POST['command'] = $_POST['command']."&file=".$_POST['file'];
}

$row = get_access($_POST['user']);
if(!$row["accsess"]) die("error");

if($_POST['command'] == 'cnf'){
	if(!isset($_POST['name']) || !isset($_POST['pass']) || !isset($_POST['rcon'])) die('empty');
	$_POST['name'] = str_replace(" ","%20", $_POST['name']);
	if(preg_match('/[^0-9a-zA-Z_.%-]/', $_POST['name'])) die('er_name');
	if(preg_match('/[^0-9a-zA-Z_.-]/', $_POST['pass'])) die('er_pass');
	if(preg_match('/[^0-9a-zA-Z_.-]/', $_POST['rcon'])) die('er_rcon');
	if(!is_numeric($_POST['addons'])) die('er_addons');
	if(strlen($_POST['name']) > 15 || strlen($_POST['pass']) > 15 || strlen($_POST['rcon']) > 15) die('max length');
	if(update_settings($_POST['name'], $_POST['pass'], $_POST['rcon'], $_POST['addons'], $_POST['user']) == 'OK'){
		//if(strpos($_POST['name'],'by lepus.su') === FALSE) $_POST['name'] = $_POST['name'].'%20by%20lepus.su';
		go_status($_POST['user'], time()+60*3);
		if(curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$_POST['user']}&cmd={$_POST['command']}&server_name={$_POST['name']}&server_passwd={$_POST['pass']}&server_rcon={$_POST['rcon']}&server_addons={$_POST['addons']}", NULL) == 'OK'){
			$_POST['command'] = 'restart';
		}else{
			go_status($_POST['user'], 0);
			die("error");
		}
	}
}

if($_POST['command'] == 'addons'){
	if(curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$_POST['user']}&cmd={$_POST['command']}&do={$_POST['do']}", NULL) == 'OK'){
		$_POST['command'] = 'restart';
	}else{
		die("error");
	}
}

if($_POST['command'] == 'create'){
	if(curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&cmd={$_POST['command']}&user_new={$_POST['user_new']}", NULL) == 'OK'){
	}else{
		die("error");
	}
}

if($_POST['command'] == 'suspend'){
	if(go_suspend($_POST['user'], 1) == 'error') die('error');
	$_POST['command'] = 'stop';
}

if($_POST['command'] == 'unsuspend'){
	if(go_suspend($_POST['user'], 0) == 'error') die('error');
	$_POST['command'] = 'start';
}

go_status($_POST['user'], time()+60*3);
echo remove_xss(curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$_POST['user']}&cmd={$_POST['command']}", NULL));
go_status($_POST['user'], 0);
