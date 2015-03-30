<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func/main.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func/csgo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/sess.php');

if(!isset($_POST['cid']) || !isset($_POST['maxplayers'])) die('empty post data');
if(!is_numeric($_POST['cid']) || !is_numeric($_POST['maxplayers'])) die('only int');

$query = $db->prepare("SELECT * FROM `params` WHERE `name` = 'server_types'");
$query->execute();
$row = $query->fetch();

$arr = json_decode($row['key'], true);
if(empty($arr[$_POST['cid']])) die('error');
if(create_access($arr[$_POST['cid']], (int) $_POST['maxplayers']) != 'OK') die('error');
echo create_server($arr[$_POST['cid']], $_POST['maxplayers']);
