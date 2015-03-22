<?
if(!empty($_SESSION['sess'])){ 
	$x = error(auth($_SESSION['login'], $_SESSION['sess']));
	
	if($x['err'] == 'OK'){
		$user = $x['mess']; 
		unset($x);
	}else{
		session_unset();
		session_destroy();
		header('refresh: 3; url=http://game.lepus.su');
		die($x['err']);
	}
	
	if($_SERVER["REQUEST_URI"] == '/index.php'){
		header('Location: http://game.lepus.su/index.php?do=default');
		die();
	}
}
?>
