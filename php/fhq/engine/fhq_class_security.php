<?php

$curdir = dirname(__FILE__);

include_once "$curdir/fhq_base.php";
include_once "$curdir/fhq_class_database.php";

class fhq_security
{
	function login($email, $password)
	{
		unset($_SESSION['user']);

		if(!$this->isLogged())
		{
			$username = base64_encode(strtoupper($email));
			$pass_hash = $this->tokenByData(array($password, $username, strtoupper($email)));

			// echo "pass_hash = $pass_hash, username = $username <br>";
			$db = new fhq_database();
			$query = "select * from user where username = '$username' and password = '$pass_hash';";
			$result = $db->query($query);
			if ($db->count( $result ) == 1)
			{
				$_SESSION['user'] = array();
				$_SESSION['user']['iduser'] = mysql_result($result, 0, 'iduser');
				$_SESSION['user']['email'] = mysql_result($result, 0, 'username');
				$_SESSION['user']['nick'] = mysql_result($result, 0, 'nick');
				$_SESSION['user']['role'] = mysql_result($result, 0, 'role');
				return true;
			}
		}
		return false;
	}
	
	function logout() {
		if($this->isLogged()) { unset($_SESSION['user']); unset($_SESSION['game']); }
	}
	function isLogged() { 
		return (isset($_SESSION['user'])); 
	}
	
	function role() { 
		return ($this->isLogged()) ? $_SESSION['user']['role'] : ''; 
	}
	function isAdmin() { 
		return ($this->isLogged() && $_SESSION['user']['role'] == 'admin' ); 
	}
	function isUser() { 
		return ($this->isLogged() && $_SESSION['user']['role'] == 'user' ); 
	}
	function isTester() { 
		return ($this->isLogged() && $_SESSION['user']['role'] == 'tester' ); 
	}
	function isGod() { 
		return ($this->isLogged() && $_SESSION['user']['role'] == 'god' ); 
	}
	function score() { 
		return ($this->isLogged() && isset($_SESSION['user']['score']) && is_numeric($_SESSION['user']['score'])) ? $_SESSION['user']['score'] : 0; 
	}
	function nick() { 
		return ($this->isLogged()) ? $_SESSION['user']['nick'] : ''; 
	}
  function email() { 
		return ($this->isLogged()) ? strtolower(base64_decode($_SESSION['user']['email'])) : ''; 
	}
  
	function setNick($nick) { 
		if($this->isLogged())
			$_SESSION['user']['nick'] = $nick;
	}
	function iduser() { 
		return ($this->isLogged() && is_numeric($_SESSION['user']['iduser'])) ? $_SESSION['user']['iduser'] : ''; 
	}
	function userid() { 
		return ($this->isLogged() && is_numeric($_SESSION['user']['iduser'])) ? $_SESSION['user']['iduser'] : ''; 
	}

	function tokenByData($arr)
	{
		$curdir = dirname(__FILE__);
		include $curdir."/../config/config.php";
	
		$data = "";
		for($i = 0; $i < count($arr); $i++)
		{
			$data .= $arr[$i].$config['secrets'][$i];
		}
		return md5($data);
	}
};

/*
if(isset($_GET['login']))
{
	$security = new fhq_security();
	$security->login();
	if(isset($_POST['comeback']))
		refreshTo($_POST['comeback']);
};

if(isset($_GET['logout']))
{
	$security = new whc_security();
	$security->logout();
	if(isset($_POST['comeback']))
		refreshTo($_POST['comeback']);
};
*/
?>
