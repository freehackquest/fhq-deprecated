<?php
class APISecurity {
	static function isLogged() {
		if (APIHelpers::$FHQSESSION != NULL) {
			return isset(APIHelpers::$FHQSESSION['user']);
		}
		return isset($_SESSION['user']);
	}

	static function login($conn, $email, $hash_password) {
		// try {
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
		// } catch(PDOException $e) {
			// APIHelpers::showerror(1201, $e->getMessage());
		// }
		return false;
	}

	static function logout() {
		if(APISecurity::isLogged()) {
			unset($_SESSION['user']);
			unset($_SESSION['game']);
			
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
		if (APIHelpers::$FHQSESSION != NULL)
			return (APISecurity::isLogged() ? APIHelpers::$FHQSESSION['user']['role'] : '' ); 
		return (APISecurity::isLogged()) ? $_SESSION['user']['role'] : '';
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
		if (APIHelpers::$FHQSESSION != NULL && APISecurity::isLogged() && isset(APIHelpers::$FHQSESSION['user']['score'])) {
			return is_numeric(APIHelpers::$FHQSESSION['user']['score']) ? intval(APIHelpers::$FHQSESSION['user']['score']) : 0;
		}
		return (APISecurity::isLogged() && is_numeric($_SESSION['user']['score'])) ? $_SESSION['user']['score'] : 0; 
	}
	
	static function nick() { 
		if (APIHelpers::$FHQSESSION != NULL && APISecurity::isLogged()) {
			return isset(APIHelpers::$FHQSESSION['user']['nick']) ? $FHQSESSION['user']['nick'] : '';
		}
		return (APISecurity::isLogged()) ? $_SESSION['user']['nick'] : ''; 
	}
	
	static function email() {
		if (APIHelpers::$FHQSESSION != NULL && APISecurity::isLogged()) {
			return isset(APIHelpers::$FHQSESSION['user']['email']) ? $FHQSESSION['user']['email'] : '';
		}
		return (APISecurity::isLogged()) ? strtolower($_SESSION['user']['email']) : '';
	}
  
	static function setNick($nick) {
		// TODO $FHQSESSION
		if(APISecurity::isLogged())
			$_SESSION['user']['nick'] = $nick;
	}

	static function userid() {
		$userid = 0;
		if (APIHelpers::$FHQSESSION != NULL && APISecurity::isLogged() && isset(APIHelpers::$FHQSESSION['user']['id'])) {
			$userid = intval(APIHelpers::$FHQSESSION['user']['id']);
		} else {
			$userid = (APISecurity::isLogged() && isset($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : intval('');
		}
		if (intval($userid) == 0) {
			session_destroy();
			APIHelpers::showerror(1317, 'Please relogon');
		}
		return $userid;
	}

	static function insertLastIp($conn, $client) {
		try {
			$query = 'INSERT INTO users_ips (userid, ip, country, city, browser, client, date_sign_in) VALUES(?,?,?,?,?,?,NOW())';
			$ip = $_SERVER['REMOTE_ADDR'];
			$country = '';
			$city = '';
			if ($ip == '127.0.0.1')
			{
				$country = 'home';
				$city = 'localhost';
			}

			$params = array(
				APISecurity::userid(),
				$_SERVER['REMOTE_ADDR'],
				$country,
				$city,
				$_SERVER['HTTP_USER_AGENT'],
				$client,
			);
			$stmt = $conn->prepare($query);
			$stmt->execute($params);

			$stmt_dls = $conn->prepare('UPDATE users SET dt_last_login = NOW() WHERE id = ?');
			$stmt_dls->execute(array(APISecurity::userid()));
		} catch(PDOException $e) {
			APIHelpers::showerror(1198, $e->getMessage());
		}
	}
	
	static function saveByToken($conn, $token) { 
		try {
			$query = 'INSERT INTO users_tokens (userid, token, status, data, start_date, end_date) VALUES(?, ?, ?, ?, NOW(), NOW() + INTERVAL 1 DAY)';
			$params = array(
				APISecurity::userid(),
				$token,
				'active',
				json_encode($_SESSION)
			);
			$stmt = $conn->prepare($query);
			$stmt->execute($params);
		} catch(PDOException $e) {
			APIHelpers::showerror(1196, $e->getMessage());
		}
	}

	static function loadByToken($conn, $token) { 
		try {
			$query = 'SELECT data FROM users_tokens WHERE token = ? AND status = ? AND end_date > NOW()';
			$params = array(
				$token,
				'active'
			);
			$stmt = $conn->prepare($query);
			$stmt->execute($params);
			if ($row = $stmt->fetch())
				$_SESSION = json_decode($row['data'],true);
		} catch(PDOException $e) {
			APIHelpers::showerror(1197, $e->getMessage());
		}
	}
	
	static function updateByToken($conn, $token) { 
		// try {
			
			$query = 'UPDATE users_tokens SET data = ?, end_date = DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE token = ?';
			$params = array(
				json_encode($_SESSION),
				$token,
			);
			$stmt = $conn->prepare($query);
			$stmt->execute($params);
		// } catch(PDOException $e) {
//			APIHelpers::showerror(1200, $e->getMessage());
		//}
	}
	
	static function removeByToken($conn, $token) { 
		try {
			$query = 'DELETE FROM users_tokens WHERE token = ?';
			$params = array(
				$token,
			);
			$stmt = $conn->prepare($query);
			$stmt->execute($params);
		} catch(PDOException $e) {
			APIHelpers::showerror(1199, $e->getMessage());
		}
	}
}
