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
	
	static $TIMESTART = null;
	static $FHQSESSION = null;
	static $FHQSESSION_ORIG = null;
	static $TOKEN = null;
	static $CONN = null;

	static function startpage($config) {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		APIHelpers::$TIMESTART = microtime(true);

		$issetToken = APIHelpers::issetParam('token');
		if ($issetToken) {
			APIHelpers::$TOKEN = APIHelpers::getParam('token', '');
			$conn = APIHelpers::createConnection($config);
			try {
				$stmt = $conn->prepare('SELECT data FROM users_tokens WHERE token = ? AND status = ? AND end_date > NOW()');
				$stmt->execute(array(APIHelpers::$TOKEN,'active'));
				if ($row = $stmt->fetch())
				{
					APIHelpers::$FHQSESSION = json_decode($row['data'],true);
					APIHelpers::$FHQSESSION_ORIG = json_decode($row['data'],true);
				}
			} catch(PDOException $e) {
				APIHelpers::showerror(1188, $e->getMessage());
			}
		} else {
			APIHelpers::$FHQSESSION = $_SESSION;
			APIHelpers::$FHQSESSION_ORIG = $_SESSION;
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
		
		echo json_encode($response);
	}
}

