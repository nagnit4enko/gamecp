<?
function create_access($type, $maxplayers){
	global $db, $user;
	$access = json_decode($user['access'], true);
	
	if(empty($access[$type])) return 'error';
	
	$query = $db->prepare("SELECT * FROM `servers` WHERE `user_id` = :uid");
	$query->bindParam(':uid', $user['id'], PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount()+1 >  $access[$type][0]) return 'limit servers'; // count servers
	
	$query = $db->prepare("SELECT SUM(maxplayers) FROM `servers` WHERE `user_id` = :uid");
	$query->bindParam(':uid', $user['id'], PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() == 1){
		$row = $query->fetch();
		if($row['SUM(maxplayers)']+$maxplayers > $access[$type][1]) return 'limit maxplayers';
	}
	return 'OK';
}

function create_server($type, $maxplayers){
	global $db, $user, $conf;
	$port = 27015; $tvport = 28015; $clport = 29015;
	$settings = '{"name":"hostname","passwd":"sv_password","rcon":"rcon_password","addons":"1"}';
	
	$query = $db->prepare("SELECT MAX(port), MAX(tvport), MAX(clport) FROM `servers`");
	$query->execute();
	if($query->rowCount() != 0){
		$get = $query->fetch();
		$port = $get['MAX(port)']+1;
		$tvport = $get['MAX(tvport)']+1;
		$clport = $get['MAX(clport)']+1;	
	}
	
	$query = $db->prepare("INSERT INTO `servers` (`type`, `user_id`, `ip`, `port`, `tvport`, `clport`, `maxplayers`, `settings`) 
							VALUES (:type, :userid, '88.198.25.76', :port, :tvport, :clport, :maxplayers, :settings)");
	$query->bindParam(':type', $type, PDO::PARAM_STR);
	$query->bindParam(':userid', $user['id'], PDO::PARAM_STR);
	$query->bindParam(':port', $port, PDO::PARAM_STR);
	$query->bindParam(':tvport', $tvport, PDO::PARAM_STR);
	$query->bindParam(':clport', $clport, PDO::PARAM_STR);
	$query->bindParam(':maxplayers', $maxplayers, PDO::PARAM_STR);
	$query->bindParam(':settings', $settings, PDO::PARAM_STR);
	$query->execute();
	
	curl_query("https://game.lepus.su:8081/?key={$conf['go_key']}&command=csgo&cmd=create&user=".$db->lastInsertId()."&MAX={$maxplayers}&GPORT={$port}&SPORT={$tvport}&CPORT={$clport}", NULL);
	return 'OK';
}

function get_servers(){
	global $db, $user; $i = ''; $count = 0;
	if($user['admin'] == 1){
		$query = $db->prepare("SELECT * FROM `servers` ORDER BY `port` ASC");
	}else{
		$query = $db->prepare("SELECT * FROM `servers` WHERE `user_id` = :id ORDER BY `port` ASC");
		$query->bindParam(':id', $user['id'], PDO::PARAM_STR);
	}

	$query->execute();
	if($query->rowCount() > 0){
		while($row=$query->fetch()){
			$server = server_info($row['id']);
			$i = $i."<li><a href=\"/index.php?do=info&server=".urlencode(base64_encode($row['id']))."\">".$server['name']."<br/><small>{$row['ip']}:{$row['port']}</small></a></li>";
			$count++;
		}
	}
	return array($i, $count);;
}

function update_settings($name, $pass, $rcon, $addons, $server){
		global $db;
		$name = urldecode($name);
		$arr = ["name" => $name, "passwd" => $pass, "rcon" => $rcon, "addons" => $addons];
		$query = $db->prepare("UPDATE `servers` SET `settings` = :settings WHERE `id` = :server");
		$query->bindParam(':settings', json_encode($arr), PDO::PARAM_STR);
		$query->bindParam(':server', $server, PDO::PARAM_STR);
		$query->execute();
		return 'OK';
}

function nginx_link($server, $file){
	global $db, $user;
	if($user['admin'] == 1){
		$query = $db->prepare("SELECT * FROM `servers` WHERE `id` =:id");
		$query->bindParam(':id', $server, PDO::PARAM_STR);
		$query->execute();
		$row = $query->fetch();
		
		$query = $db->prepare("SELECT * FROM `users` WHERE `id` = :id");
		$query->bindParam(':id', $row['user_id'], PDO::PARAM_STR);
		$query->execute();
		$row = $query->fetch();
		
		$user['nginx_key'] = $row['nginx_key'];
	}
	$domain = 'http://game.lepus.su';
	$dir = "/gotv/csgoserver$server/";
	$time = time()+60*60*24;
	$key = str_replace("=", "", strtr(base64_encode(md5($time.$dir.$file.getenv("REMOTE_ADDR")." {$user['nginx_key']}", TRUE)), "+/", "-_"));
	return htmlspecialchars($domain.$dir.$file."?hash=$key&time=$time");
}

function go_suspend($id, $suspend){
	global $db, $user;
	if($user['admin'] != 1) return 'error';
	$query = $db->prepare("UPDATE `servers` SET `go_suspend` =:suspend WHERE `id` =:id");
	$query->bindParam(':suspend', $suspend, PDO::PARAM_STR);
	$query->bindParam(':id', $id, PDO::PARAM_STR);
	$query->execute();
	return 'OK';
}

function go_issuspended($id){
	global $db, $user;
//	if($user['admin'] != 1) return 'error';
	$query = $db->prepare("SELECT * FROM `servers` WHERE `id` =:id AND `go_suspend` = 1");
	$query->bindParam(':id', $id, PDO::PARAM_STR);
	$query->execute();	
	if($query->rowCount() == 1)
		return 1;
	else return 0;
}

function get_access($id){
	global $db, $user;
	if($user['admin'] == '1'){
		$query = $db->prepare("SELECT * FROM `servers` WHERE `id` = :id");
		$query->bindParam(':id', $id, PDO::PARAM_STR);
	}else{
		$query = $db->prepare("SELECT * FROM `servers` WHERE `user_id` = :uid AND `id` = :id");
		$query->bindParam(':uid', $user['id'], PDO::PARAM_STR);
		$query->bindParam(':id', $id, PDO::PARAM_STR);
	}
	
	$query->execute();
	if($query->rowCount() != 1)
		$i =  ["accsess" => FALSE]; 
	else
		$i = ["accsess" => TRUE, "data" => $query->fetch()];
	
	return $i;
}

function go_status($id, $status){
	global $db;
	$query = $db->prepare("UPDATE `servers` SET `go_status` =:status WHERE `id` =:id");
	$query->bindParam(':status', $status, PDO::PARAM_STR);
	$query->bindParam(':id', $id, PDO::PARAM_STR);
	$query->execute();
	return 'OK';
}

function server_info($id){
	$row = get_access($id);
	if(!$row["accsess"]) die("no_accsess");
	if(!empty($row['data']['settings'])){
		$j = json_decode($row['data']['settings'], true);
	}else{
		$j = ["name" => NULL, "passwd" => NULL, "rcon" => NULL, "addons" => NULL];
	}
	$i = ["ip" => $row['data']['ip'], "port" => $row['data']['port'], "name" => $j['name'], "passwd" => $j['passwd'], "rcon" => $j['rcon'], "addons" => @$j['addons']];
	return $i;
}

function csgo_info($ip, $port){
	define( 'SQ_SERVER_ADDR', $ip );
	define( 'SQ_SERVER_PORT', $port );
	define( 'SQ_TIMEOUT',     1 );
	define( 'SQ_ENGINE',      SourceQuery :: SOURCE );
	
	$Query = new SourceQuery();
	$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
	//$i = ["info" => $Query->GetInfo(), "players" => $Query->GetPlayers()];
	$i = ["info" => $Query->GetInfo()];
	$Query->Disconnect();
	return $i;
}
