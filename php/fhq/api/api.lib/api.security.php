<?
class FHQSecurity {
	static function isLogged() {
		return isset($_SESSION['user']); 
	}
	
	static function logout() {
		if(FHQSecurity::isLogged()) { unset($_SESSION['user']); unset($_SESSION['game']); }
	}
	
	static function role() { 
		return (FHQSecurity::isLogged()) ? $_SESSION['user']['role'] : ''; 
	}
	
	static function isAdmin() { 
		return (FHQSecurity::isLogged() && $_SESSION['user']['role'] == 'admin' ); 
	}
	
	static function isUser() { 
		return (FHQSecurity::isLogged() && $_SESSION['user']['role'] == 'user' ); 
	}
	
	static function isTester() { 
		return (FHQSecurity::isLogged() && $_SESSION['user']['role'] == 'tester' ); 
	}
	
	static function isGod() { 
		return (FHQSecurity::isLogged() && $_SESSION['user']['role'] == 'god' ); 
	}
	
	static function score() { 
		return (FHQSecurity::isLogged() && is_numeric($_SESSION['user']['score'])) ? $_SESSION['user']['score'] : 0; 
	}
	
	static function nick() { 
		return (FHQSecurity::isLogged()) ? $_SESSION['user']['nick'] : ''; 
	}
	
	static function email() { 
		return (FHQSecurity::isLogged()) ? strtolower(base64_decode($_SESSION['user']['email'])) : ''; 
	}
  
	static function setNick($nick) { 
		if(FHQSecurity::isLogged())
			$_SESSION['user']['nick'] = $nick;
	}
	
	static function iduser() { 
		return (FHQSecurity::isLogged() && is_numeric($_SESSION['user']['iduser'])) ? $_SESSION['user']['iduser'] : ''; 
	}
	
	static function userid() { 
		return (FHQSecurity::isLogged() && is_numeric($_SESSION['user']['iduser'])) ? $_SESSION['user']['iduser'] : ''; 
	}
	
	static function insertLastIp($conn) { 
		try {
			$query = 'INSERT INTO users_ips(userid, ip, country, city, date_of_a_sing_in) VALUES(?,?,?,?,NOW())';
			$params = array(FHQSecurity::iduser(),$_SERVER['REMOTE_ADDR'], '', '');
			$stmt = $conn->prepare($query);
			$stmt->execute($params);
		} catch(PDOException $e) {
			showerror(103, 'Error 103: ' + $e->getMessage());
		}	
	}	
}
