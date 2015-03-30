<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func/main.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func/csgo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');

$query = $db->prepare("SELECT * FROM `params` WHERE `name` = 'server_types'");
$query->execute();
$row = $query->fetch();

// Проверяем есть ли такой тип сервера
$arr = json_decode($row['key'], true);
if(empty($arr[$_GET['cid']])) die('error');
echo create_access($arr[$_GET['cid']], 25);

//var_dump($arr);

//if(!in_array("csgoserver", $arr)) die('error');

//echo create_server($_POST['cid'], $_POST['maxplayers']);
