<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func/main.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/sess.php');

if(empty($_POST['pass']) || empty($_POST['npass']) || empty($_POST['rpass'])) die('empty');
if($_POST['npass'] != $_POST['rpass']) die('wrong repeat');
if(strlen($_POST['npass']) < 6) die('short password');
if(!password_verify($_POST['pass'], $user['passwd'])) die('wrong password');


$query = $db->prepare("UPDATE `users` SET `passwd` =:passwd, `session` = :null WHERE `id` =:id");
$query->bindParam(':passwd', gen_passwd($_POST['npass']), PDO::PARAM_STR);
$query->bindParam(':null', $n = null, PDO::PARAM_INT);
$query->bindParam(':id', $user['id'], PDO::PARAM_STR);
$query->execute();

session_unset();
session_destroy();

echo 'OK';
