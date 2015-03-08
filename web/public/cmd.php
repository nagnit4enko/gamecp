<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');

if(empty($_POST['command']) || empty($_POST['user'])) die('empty');
if(preg_match('/[^0-9a-z]/', $_POST['user'])) die('er_user');

$commands = ["restart", "stop", "start", "log", "update-restart", "delete", "cnf", "addons"];
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
//	if(strpos($_POST['name'],'by lepus.su') === false) die('В названии сервера обязательно должно присутствовать "by lepus.su"');
	$_POST['name'] = str_replace(" ","%20", $_POST['name']);
	if(preg_match('/[^0-9a-zA-Z_.%-]/', $_POST['name'])) die('er_name');
	if(preg_match('/[^0-9a-zA-Z_.-]/', $_POST['pass'])) die('er_pass');
	if(preg_match('/[^0-9a-zA-Z_.-]/', $_POST['rcon'])) die('er_rcon');
	if(update_settings($_POST['name'], $_POST['pass'], $_POST['rcon'], $_POST['user']) == 'OK'){
		if(curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$_POST['user']}&cmd={$_POST['command']}&server_name={$_POST['name']}&server_passwd={$_POST['pass']}&server_rcon={$_POST['rcon']}", NULL) == 'OK'){
			$_POST['command'] = 'restart';
		}else{
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

echo curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$_POST['user']}&cmd={$_POST['command']}", NULL);