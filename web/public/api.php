<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

//if($_POST['api_key'] != $conf['api_key']) die('wrong api key');

switch($_GET['do']){
	default: echo "error"; break;
	case 'register': break;
	case 'passwd': echo passwd_user(); break;
	case 'status': echo status_user(); break;
	case 'delete': break;
}
