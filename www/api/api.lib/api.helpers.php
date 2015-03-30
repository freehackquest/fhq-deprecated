<?php
$curdir_helpers = dirname(__FILE__);
include_once ($curdir_helpers."/api.security.php");

function issetParam($name) {
  return isset($_GET[$name]) || isset($_POST[$name]);
}

function getParam($name, $defaultValue = "") {
  return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
}

class APIHelpers {
	static function checkAuth()
	{
		if(!APISecurity::isLogged()) {
			APIHelpers::showerror(1224, 'Not authorized request');
			exit;
		}
	}
	
	static function createConnection($config)
	{
		return new PDO('mysql:host='.$config['db']['host'].';dbname='.$config['db']['dbname'].';charset=utf8', $config['db']['username'], $config['db']['userpass']);
	}
	
	static function issetParam($name) {
		return isset($_GET[$name]) || isset($_POST[$name]);
	}

	static function getParam($name, $defaultValue = "") {
	  return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
	}
	
	static function showerror($code, $message) {
		$result = array(
			'result' => 'fail',
			'data' => array(),
		);
		
		$result['error']['code'] = $code;
		$result['error']['message'] = 'Error '.$code.': '.$message;
		echo json_encode($result);
		exit;
	}
	
	static function calculateScore($conn)
	{
		// calculate score
		$query = '
			SELECT 
				ifnull(SUM(quest.score),0) as sum_score 
			FROM 
				userquest 
			INNER JOIN 
				quest ON quest.idquest = userquest.idquest AND quest.gameid = ?
			WHERE 
				(userquest.iduser = ?) 
				AND ( userquest.stopdate <> \'0000-00-00 00:00:00\' );
		';
		$score = 0;
		$stmt = $conn->prepare($query);
		$stmt->execute(array(APIGame::id(), APISecurity::userid()));
		if($row = $stmt->fetch())
			$score = $row['sum_score'];
		return $score;
	}
	
	static function gen_guid() {
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12);
		return $uuid;	
	}
	
	static function isMobile()
	{
		$browser = $_SERVER['HTTP_USER_AGENT']."\n\n";
		$pos = strpos($browser,"Mobile");
		return !($pos === false);
	}
}

