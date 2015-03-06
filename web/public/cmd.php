<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');

if(empty($_POST['command']) || empty($_POST['user'])) die('empty');
if(preg_match('/[^0-9a-z]/', $_POST['user'])) die('er_user');

$commands = ["restart", "stop", "start", "log", "update-restart", "delete", "cnf"];
if (!in_array($_POST['command'], $commands)) die('er_command');

if($_POST['command'] == 'delete'){
	if(empty($_POST['file'])) die('empty');
	if(preg_match('/[^0-9a-zA-Z_.-]/', $_POST['file'])) die('er_file');
	$_POST['command'] = $_POST['command']."&file=".$_POST['file'];
}

$row = get_access($_POST['user']);
if(!$row["accsess"]) die("error");
	
echo curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user={$_POST['user']}&cmd={$_POST['command']}", NULL);
