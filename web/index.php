<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func/main.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func/csgo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');

switch(@$_GET['do']){
	default: 
		if(isset($_SESSION['sess']))	require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/news.php');
			else						require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/login.php');
	break;
	
	case 'info': require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/info.php'); break;
	case 'exit': _exit(); break;
}
