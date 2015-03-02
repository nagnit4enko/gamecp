<?
if(!empty($_SESSION['sess'])) $user = error(auth($_SESSION['login'], $_SESSION['sess']));
?>