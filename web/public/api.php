<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

if($_POST['api_key'] != $conf['api_key']) die('wrong api key');

switch($_GET['do']){
	default: echo "error"; break;
	case 'register': break;
	case 'passwd': break;
	case 'status':
		if(empty((int)$_POST['uid'])) die('error');
		if(empty((int)$_POST['status'])) die('error');
		status_user((int)$_POST['uid']),(int)$_POST['status']);
	break;
	case 'delete': break;
}
