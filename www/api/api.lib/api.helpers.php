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

	static function isAuthorized() {
		if (APIHelpers::$FHQSESSION == NULL) {
			return false;
		}
		return isset(APIHelpers::$FHQSESSION['user']);
	}

	static function checkAuth()
	{
		if(!APIHelpers::isAuthorized()) {
			APIHelpers::showerror(401, 'Not authorized request');
			exit;
		}
	}
	
	static function createConnection()
	{
		if (APIHelpers::$CONN != null)
			return APIHelpers::$CONN;
		
		APIHelpers::$CONN = new PDO(
			'mysql:host='.APIHelpers::$CONFIG['db']['host'].';dbname='.APIHelpers::$CONFIG['db']['dbname'].';charset=utf8',
			APIHelpers::$CONFIG['db']['username'],
			APIHelpers::$CONFIG['db']['userpass']
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
		$response = array(
			'result' => 'fail',
			'data' => array(),
		);
		$response['error']['code'] = $code;
		$response['error']['message'] = $message;
		APIHelpers::endpage($response);
		exit;
	}

	static function calculateScore($conn, $gameid){
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
		$stmt->execute(array($gameid, APISecurity::userid()));
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
	static $CONFIG = null;

	static function startpage() {
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
		APIHelpers::$TIMESTART = microtime(true);
		if(isset($_COOKIE['fhqtoken'])){
			APIHelpers::$TOKEN = $_COOKIE['fhqtoken'];
		}else if(APIHelpers::issetParam('token')){
			APIHelpers::$TOKEN = APIHelpers::getParam('token', '');
		}else if(APIHelpers::is_json_input()){
			$request = APIHelpers::read_json_input();
			if(isset($request['token']) && $request['token'] != ''){
				APIHelpers::$TOKEN = $request['token'];
			}else{
				APIHelpers::$TOKEN = null;
			}
		}else{
			APIHelpers::$TOKEN = null;
		}
		
		if(APIHelpers::$TOKEN != null){
			$conn = APIHelpers::createConnection();
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
			if(isset($response['code'])){
				http_response_code($response['code']);
			}else{
				http_response_code(400);
			}
		}else{
			http_response_code(200);
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
	
	static function is_json_input(){
		$headers =  getallheaders();
		$is_json = false;
		foreach($headers as $key=>$val){
			if(strtolower($key) == "content-type" && strtolower($val) == "application/json"){
				$is_json = true;
			}
		}
		return $is_json;
	}
	
	static function read_json_input(){
		return json_decode(file_get_contents('php://input'), true);
	}

	static function checkGameDates($conn, $gameid, &$message) {
		
		if ($gameid <= 0) {
			$message = 'Select game please';
			return false;
		}

		$stmt = $conn->prepare('SELECT * FROM games WHERE id = ?');
		$stmt->execute(array($gameid));
		if($row = $stmt->fetch()){
			if (APISecurity::isAdmin() || APISecurity::isTester())
				return true;
			$date_start = new DateTime();
			date_timestamp_set($date_start, strtotime($row['date_start']));
			$date_stop = new DateTime();
			date_timestamp_set($date_stop, strtotime($row['date_stop']));
			$date_restart = new DateTime();
			date_timestamp_set($date_restart, strtotime($row['date_restart']));
			$date_current = new DateTime();
			date_timestamp_set($date_current, time());
			$di_start = $date_current->diff($date_start);
			$di_stop = $date_current->diff($date_stop);
			$di_restart = $date_current->diff($date_restart);

			$bWillBeStarted = ($di_start->invert == 0);
			$bWillBeRestarted = ($di_stop->invert == 1 && $di_restart->invert == 0);
			
			// echo date_diff($date_current, $date_start)."<br>";
			if ( $bWillBeStarted || $bWillBeRestarted) {
				$label = $bWillBeStarted ? 'Game will be started after: ' : 'Game will be restarted after: ';
				$di = $bWillBeStarted ? $di_start : $di_restart;

				$message = $label.'<br>
					<div class="fhq_timer" id="days">'.$di->d.'</div> day(s) 
					<div class="fhq_timer" id="hours">'.$di->h.'</div> hour(s) 
					<div class="fhq_timer" id="minutes">'.$di->i.'</div> minute(s)
					<div class="fhq_timer" id="seconds">'.$di->s.'</div> second(s)<br>
					<div id="reload_content" onclick="startTimer();"/></div> <br><br>
				';
				
				return false;
			}
			return true;
		}else{
			$message = "Game not found";
			APIHelpers::showerror(404, "Game not found");
			return false;
		}

		return true;
	}
	
	static function sendMail($to_, $cc_, $bcc_, $subject, $body, &$errormsg)
	{	
		/*if (isset($config['mail']['allow']) && $config['mail']['allow'] != 'yes' )
			return false;*/
		
		// Pear Mail Library
		require_once "Mail.php";
		
		$to = '<'.$to_.'>';
		
		$headers = array(
			'From' => '<'.APIHelpers::$CONFIG['mail']['from'].'>',
			'To' => '<'.$to_.'>',
			'Subject' => $subject
		);
		
		if(strlen($cc_) > 0)
			$headers['Cc'] = '<'.$cc_.'>';

	if(strlen($bcc_) > 0)
			$headers['Bcc'] = '<'.$bcc_.'>';

		// @ - hide warnings
		$smtp = @Mail::factory('smtp', array(
			'host' => APIHelpers::$CONFIG['mail']['host'],
			'port' => APIHelpers::$CONFIG['mail']['port'],
			'auth' => APIHelpers::$CONFIG['mail']['auth'],
			'username' => APIHelpers::$CONFIG['mail']['username'],
			'password' => APIHelpers::$CONFIG['mail']['password']
		));

		$mail = @$smtp->send($to, $headers, $body);
		// $errormsg = $mail->getMessage();
		return true; // PEAR::isError($mail);
	}

	static function sendMailToAdmin($subject, $body, &$errormsg){
		APIHelpers::sendMail(APIHelpers::$CONFIG['mail']['system_message_admin_email'], '', '', $subject, $body, $errormsg);
	}
}

include_once ($curdir_helpers."/../../config/config.php");

if(!isset($config)){
	http_response_code(400);
	echo "Config not found";
	exit;
}
APIHelpers::$CONFIG = $config;

