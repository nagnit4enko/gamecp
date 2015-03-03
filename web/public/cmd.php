<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');

if(empty($_POST['command']) || empty($_POST['user'])) die('empty');
if(preg_match('/[^0-9a-z]/', $_POST['user'])) die('er_user');
if(($_POST['command'] != 'restart')  && ($_POST['command'] != 'stop') && ($_POST['command'] != 'start') && ($_POST['command'] != 'log') && ($_POST['command'] != 'update')) die('er_command');

echo curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&user=csgoserver10&cmd={$_POST['command']}", NULL);


