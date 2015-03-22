<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

if($_POST['api_key'] != $conf['api_key']) die('wrong api key');

// Регистрация нового пользователя

// Выключение/ включение сервера

// Удаление пользователя/ сервера