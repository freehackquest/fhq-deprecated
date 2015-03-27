<?php

$curdir = dirname(__FILE__);

include_once "$curdir/fhq_base.php";
include_once "$curdir/fhq_class_database.php";

class fhq_security
{
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

?>
