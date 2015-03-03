<?
if(!empty($_SESSION['sess'])){ $user = error(auth($_SESSION['login'], $_SESSION['sess']));
	if($_SERVER["REQUEST_URI"] == '/index.php'){
		header('Location: http://game.lepus.su/index.php?do=default');
		die();
	}
}
?>