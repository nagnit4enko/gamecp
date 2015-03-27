<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func/main.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SESSION['sess']) && !empty($_POST['login']) && !empty($_POST['password'])){
	$x = error(login($_POST['login'], $_POST['password']));
	if($x['err'] == 'OK') echo 'login'; else echo $x['err'];
} else echo "error10";
