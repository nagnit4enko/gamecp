<?php
	try {  
		$db = new PDO("mysql:host={$conf['db_host']};dbname={$conf['db_name']}", $conf['db_user'], $conf['db_pass']);
		$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$db->exec("set names utf8");
	}
	catch(PDOException $e) {
		echo "MySQL ERROR";
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/private/logs/PDOErrors.txt', PHP_EOL . $e->getMessage(), FILE_APPEND);
	}
?>
