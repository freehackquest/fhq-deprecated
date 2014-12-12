<?
$curdir = dirname(__FILE__);
include_once ($curdir."/api.security.php");

function showerror($code, $message) {
	$result = array(
		'result' => 'fail',
		'data' => array(),
	);
	
 	$result['error']['code'] = $code;
	$result['error']['message'] = $message;
	echo json_encode($result);
	exit;
}

function checkAuth($security)
{
	/*if(!$security->isLogged())
	{
		refreshTo("index.php");
		return;
	};*/

	if(!$security->isLogged()) {
		$result = array(
			'result' => 'fail',
			'data' => array(),
		);
		$result['error']['code'] = 403;
		$result['error']['message'] = 'Error 403: Not authorized request';
		echo json_encode($result);
		exit;
	}
}


function issetParam($name) {
  return isset($_GET[$name]) || isset($_POST[$name]);
}

function getParam($name, $defaultValue = "") {
  return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
}

class FHQHelpers {
	static function checkAuth()
	{
		if(!FHQSecurity::isLogged()) {
			$result = array(
				'result' => 'fail',
				'data' => array(),
			);
			$result['error']['code'] = 403;
			$result['error']['message'] = 'Error 403: Not authorized request';
			echo json_encode($result);
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
}
