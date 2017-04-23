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
		if (APIHelpers::$CONN != null)
			return APIHelpers::$CONN;
		
		APIHelpers::$CONN = new PDO(
			'mysql:host='.$config['db']['host'].';dbname='.$config['db']['dbname'].';charset=utf8',
			$config['db']['username'],
			$config['db']['userpass']
		);
		return APIHelpers::$CONN;
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
			'httpcode' => 400,
			'data' => array(),
		);
		$result['error']['code'] = $code;
		$result['error']['message'] = 'Error '.$code.': '.$message;
		APIHelpers::endpage($result);
		exit;
	}
	
	static function showerror2($code, $httpcode, $message) {
		$result = array(
			'result' => 'fail',
			'httpcode' => $httpcode,
			'data' => array(),
		);
		$result['error']['code'] = $code;
		$result['error']['message'] = 'Error '.$code.': '.$message;
		APIHelpers::endpage($result);
		exit;
	}

	static function calculateScore($conn)
	{
		// calculate score
		$query = '
			SELECT 
				ifnull(SUM(quest.score),0) as sum_score 
			FROM 
				users_quests
			INNER JOIN 
				quest ON quest.idquest = users_quests.questid AND quest.gameid = ?
			WHERE 
				(users_quests.userid = ?)
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
	
	static $TIMESTART = null;
	static $FHQSESSION = null;
	static $FHQSESSION_ORIG = null;
	static $TOKEN = null;
	static $CONN = null;

	static function startpage($config) {
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
		APIHelpers::$TIMESTART = microtime(true);
		if(isset($_COOKIE['fhqtoken'])){
			APIHelpers::$TOKEN = $_COOKIE['fhqtoken'];
		}else if(APIHelpers::issetParam('token')){
			APIHelpers::$TOKEN = APIHelpers::getParam('token', '');
		}else{
			APIHelpers::$TOKEN = null;
		}
		
		if(APIHelpers::$TOKEN != null){
			$conn = APIHelpers::createConnection($config);
			try {
				$stmt = $conn->prepare('SELECT data FROM users_tokens WHERE token = ? AND status = ? AND end_date > NOW()');
				$stmt->execute(array(APIHelpers::$TOKEN,'active'));
				if ($row = $stmt->fetch()){
					APIHelpers::$FHQSESSION = json_decode($row['data'],true);
					APIHelpers::$FHQSESSION_ORIG = json_decode($row['data'],true);
				}
			} catch(PDOException $e) {
				APIHelpers::showerror(1188, $e->getMessage());
			}
		}

		$response = array(
			'result' => 'fail',
			'lead_time_sec' => 0,
			'data' => array(),
		);
		return $response;
	}
	
	static function endpage($response) {
		if (APIHelpers::$TIMESTART != null)
			$result['lead_time_sec'] = microtime(true) - APIHelpers::$TIMESTART;

		$hash_session = null;
		$hash_session_orig = null;
		if (APIHelpers::$FHQSESSION != null && APIHelpers::$FHQSESSION_ORIG != null)
			$hash_session = md5(json_encode(APIHelpers::$FHQSESSION));
			$hash_session_orig = md5(json_encode(APIHelpers::$FHQSESSION_ORIG));
		
		if ($hash_session != $hash_session_orig && $hash_session_orig != null) {
			APISecurity::updateByToken();
		}
		if($response['result'] == 'fail'){
			if(isset($response['httpcode'])){
				http_response_code($response['httpcode']);
			}else{
				http_response_code(400);
			}
		}
		echo json_encode($response);
	}
	
	static function find_captcha($conn, $captcha_uuid){
		// cleanup captures
		$conn->prepare('DELETE FROM users_captcha WHERE dt_expired < NOW();')->execute();

		$stmt = $conn->prepare('SELECT * FROM users_captcha WHERE captcha_uuid = ?');
		$stmt->execute(array($captcha_uuid));
		$captcha_val = '';
		if($row = $stmt->fetch()){
			$captcha_val = $row['captcha_val'];
		}
		$conn->prepare('DELETE FROM users_captcha WHERE captcha_uuid = ?')->execute(array($captcha_val));
		return $captcha_val;
	}
}

