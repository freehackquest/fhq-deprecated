<?php
class APISecurity {
	static function isLogged() {
		return isset($_SESSION['user']);
	}
	
	static function login($conn, $email, $hash_password) {
		// try {
			$query = 'SELECT * FROM user WHERE email = ? AND pass = ?';
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
				$_SESSION['user']['iduser'] = $row['iduser'];
				$_SESSION['user']['email'] = $row['email'];
				$_SESSION['user']['nick'] = $row['nick'];
				$_SESSION['user']['role'] = $row['role'];

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
		}
	}
	
	static function role() { 
		return (APISecurity::isLogged()) ? $_SESSION['user']['role'] : ''; 
	}
	
	static function isAdmin() { 
		return (APISecurity::isLogged() && $_SESSION['user']['role'] == 'admin' ); 
	}
	
	static function generatePassword($config, $email, $password) {
		$username = base64_encode(strtoupper($email));
		$data = "";
		$arr = array($password, $username, strtoupper($email));
		for($i = 0; $i < count($arr); $i++)
			$data .= $arr[$i].$config['secrets'][$i];
		return md5($data);
	}
	
	static function generatePassword2($email, $password) {
		return sha1(strtoupper($email).$password);
	}
	
	static function isUser() { 
		return (APISecurity::isLogged() && $_SESSION['user']['role'] == 'user' ); 
	}
	
	static function isTester() { 
		return (APISecurity::isLogged() && $_SESSION['user']['role'] == 'tester' ); 
	}
	
	static function isGod() { 
		return (APISecurity::isLogged() && $_SESSION['user']['role'] == 'god' ); 
	}
	
	static function score() { 
		return (APISecurity::isLogged() && is_numeric($_SESSION['user']['score'])) ? $_SESSION['user']['score'] : 0; 
	}
	
	static function nick() { 
		return (APISecurity::isLogged()) ? $_SESSION['user']['nick'] : ''; 
	}
	
	static function email() { 
		return (APISecurity::isLogged()) ? strtolower($_SESSION['user']['email']) : '';
	}
  
	static function setNick($nick) { 
		if(APISecurity::isLogged())
			$_SESSION['user']['nick'] = $nick;
	}
	
	static function iduser() { 
		return (APISecurity::isLogged() && is_numeric($_SESSION['user']['iduser'])) ? $_SESSION['user']['iduser'] : ''; 
	}
	
	static function userid() { 
		return (APISecurity::isLogged() && is_numeric($_SESSION['user']['iduser'])) ? $_SESSION['user']['iduser'] : ''; 
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
				APISecurity::iduser(),
				$_SERVER['REMOTE_ADDR'],
				$country,
				$city,
				$_SERVER['HTTP_USER_AGENT'],
				$client,
			);
			$stmt = $conn->prepare($query);
			$stmt->execute($params);

			$stmt_dls = $conn->prepare('UPDATE user SET date_last_signup = NOW() WHERE iduser = ?');
			$stmt_dls->execute(array(APISecurity::iduser()));
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
