<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SESSION['sess']) && !empty($_POST['login']) && !empty($_POST['password'])){
	$x = error(login($_POST['login'], $_POST['password']));
	if(empty($x)) echo "login"; else echo $x;
	
} else echo "error10";

