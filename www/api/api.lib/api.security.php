<?php
class APISecurity {

	static function login($conn, $email, $hash_password) {
		
		$query = 'SELECT * FROM users WHERE email = ? AND pass = ?';
		$email = strtolower($email);
		$params = array(
			$email,
			$hash_password,
		);
		$stmt = $conn->prepare($query);
		$stmt->execute($params);
		if ($row = $stmt->fetch())
		{
			$_SESSION['user'] = array();
			$_SESSION['user']['id'] = $row['id'];
			$_SESSION['user']['email'] = $row['email'];
			$_SESSION['user']['nick'] = $row['nick'];
			$_SESSION['user']['role'] = $row['role'];
			APIHelpers::$FHQSESSION = array(
				'user' => array(
					'id' => $row['id'],
					'email' => $row['email'],
					'nick' => $row['nick'],
					'role' => $row['role'],
				),
			);
			return true;
		}
		return false;
	}
	
	static function login_by_google($conn, $email) {
		$query = 'SELECT * FROM users WHERE email = ?';
		$email = strtolower($email);
		$params = array(
			$email
		);
		$stmt = $conn->prepare($query);
		$stmt->execute($params);
		if ($row = $stmt->fetch())
		{
			$_SESSION['user'] = array();
			$_SESSION['user']['id'] = $row['id'];
			$_SESSION['user']['email'] = $row['email'];
			$_SESSION['user']['nick'] = $row['nick'];
			$_SESSION['user']['role'] = $row['role'];
			APIHelpers::$FHQSESSION = array(
				'user' => array(
					'id' => $row['id'],
					'email' => $row['email'],
					'nick' => $row['nick'],
					'role' => $row['role'],
				),
			);
			return true;
		}
		return false;
	}

	static function logout() {
		if(APIHelpers::isAuthorized()) {
			if (APIHelpers::$FHQSESSION != NULL) {
				unset(APIHelpers::$FHQSESSION['user']);
				unset(APIHelpers::$FHQSESSION['game']);
			}
		}
	}

	static function generatePassword2($email, $password) {
		return sha1(strtoupper($email).$password);
	}
	
	static function role() { 
		return (APIHelpers::isAuthorized()) ? APIHelpers::$FHQSESSION['user']['role'] : '';
	}
	
	static function isAdmin() {
		return (APISecurity::role() == 'admin');
	}
	
	static function isUser() {
		return (APISecurity::role() == 'user');
	}
	
	static function isTester() {
		return (APISecurity::role() == 'tester');
	}
	
	static function isGod() {
		return (APISecurity::role() == 'god');
	}
	
	static function score() { 
		if (APIHelpers::$FHQSESSION != NULL && APIHelpers::isAuthorized() && isset(APIHelpers::$FHQSESSION['user']['score'])) {
			return is_numeric(APIHelpers::$FHQSESSION['user']['score']) ? intval(APIHelpers::$FHQSESSION['user']['score']) : 0;
		}
		return (APIHelpers::isAuthorized() && is_numeric($_SESSION['user']['score'])) ? $_SESSION['user']['score'] : 0; 
	}
	
	static function setUserScore($newScore) {
		if (isset($_SESSION['user']['score']))
			$_SESSION['user']['score'] = $newScore;
		if (APIHelpers::$FHQSESSION != NULL && APIHelpers::isAuthorized() ) {
			APIHelpers::$FHQSESSION['user']['score'] = $newScore;
		}
	}	
	
	static function nick() { 
		if (APIHelpers::$FHQSESSION != NULL && APIHelpers::isAuthorized()) {
			return isset(APIHelpers::$FHQSESSION['user']['nick']) ? APIHelpers::$FHQSESSION['user']['nick'] : '';
		}
		return (APIHelpers::isAuthorized()) ? $_SESSION['user']['nick'] : ''; 
	}
	
	static function email() {
		if (APIHelpers::$FHQSESSION != NULL && APIHelpers::isAuthorized()) {
			return isset(APIHelpers::$FHQSESSION['user']['email']) ? $FHQSESSION['user']['email'] : '';
		}
		return (APIHelpers::isAuthorized()) ? strtolower($_SESSION['user']['email']) : '';
	}
  
	static function setNick($nick) {
		// TODO $FHQSESSION
		if(APIHelpers::isAuthorized())
			$_SESSION['user']['nick'] = $nick;
	}

	static function userid() {
		$userid = 0;
		if (APIHelpers::$FHQSESSION != NULL && APIHelpers::isAuthorized() && isset(APIHelpers::$FHQSESSION['user']['id'])) {
			$userid = intval(APIHelpers::$FHQSESSION['user']['id']);
		}
		return $userid;
	}

	static function updateLastDTLogin($conn) {
		$stmt_dls = $conn->prepare('UPDATE users SET dt_last_login = NOW() WHERE id = ?');
		$stmt_dls->execute(array(APISecurity::userid()));
	}
	
	static function saveByToken() { 
		$query = 'INSERT INTO users_tokens (userid, token, status, data, start_date, end_date) VALUES(?, ?, ?, ?, NOW(), NOW() + INTERVAL 1 DAY)';
		$params = array(
			APISecurity::userid(),
			APIHelpers::$TOKEN,
			'active',
			json_encode(APIHelpers::$FHQSESSION)
		);
		$stmt = APIHelpers::$CONN->prepare($query);
		$stmt->execute($params);
	}

	static function loadByToken() { 
		$query = 'SELECT data FROM users_tokens WHERE token = ? AND status = ?'; // AND end_date > NOW()
		$params = array(
			APIHelpers::$TOKEN,
			'active'
		);
		$stmt = $conn->prepare($query);
		$stmt->execute($params);
		if ($row = $stmt->fetch())
			APIHelpers::$FHQSESSION = json_decode($row['data'],true);
	}
	
	static function updateByToken() { 
		if (APIHelpers::$TOKEN == null || APIHelpers::$FHQSESSION == null || APIHelpers::$CONN == null)
			return;
		
		$query = 'UPDATE users_tokens SET data = ?, end_date = DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE token = ?';
		$params = array(
			json_encode(APIHelpers::$FHQSESSION),
			APIHelpers::$TOKEN,
		);
		$stmt = APIHelpers::$CONN->prepare($query);
		$stmt->execute($params);
	}
	
	static function removeByToken($conn, $token) { 
		$query = 'DELETE FROM users_tokens WHERE token = ?';
		$params = array(
			$token,
		);
		$stmt = $conn->prepare($query);
		$stmt->execute($params);
	}
}
