<?php
	if (file_exists("../config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}
	
	$current_step = 8;
	include_once("install_base.php");
	
	if (
		isset($_GET['login'])
		&& isset($_GET['nickname'])
		&& isset($_GET['password'])
	)
	{
		$login = $_GET['login'];
		$password = $_GET['password'];
		$nickname = $_GET['nickname'];
		
		// echo $login."; ".$password;
		
		function tokenByData($config, $arr)
		{
			$data = "";
			for($i = 0; $i < count($arr); $i++)
			{
				$data .= $arr[$i].$config['secrets'][$i];
			}
			return md5($data);
		}
		
		$username = base64_encode(strtoupper($login));
		$pass_hash = tokenByData($config, array($password, $username, strtoupper($login)));
		
		$user = $config['db']['username'];
		$pass = $config['db']['userpass'];
		$dbname = $config['db']['dbname'];
		$dbhost = $config['db']['host'];

		$conn = new pdo('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', $user, $pass);
		
		
		
		$conn->query("DELETE FROM user WHERE username='$username'");
		$conn->query("INSERT INTO users( email, password, nick, role, score )
			VALUES ('$username','$pass_hash','$nickname','admin', 0);");

		$config['owner'] = $conn->lastInsertId();
		
		include_once("install_gotonextstep.php");
	}
?>
<h1> Install (step8) </h1>

Configure admin user: <br>
<form>
	<br>
	Login: <input type='text' name='login'
		value='<?php echo isset($_GET['login']) ? $_GET['login'] : 'admin'; ?>'/> <br>
		
	Password: <input type='text' name='password'
		value='<?php echo isset($_GET['password']) ? $_GET['password'] : 'admin'; ?>'/> <br>
		
	Nickname: <input type='text' name='nickname'
		value='<?php echo isset($_GET['nickname']) ? $_GET['nickname'] : 'admin'; ?>'/> <br>

	<br>
	<input type='submit' name='' value='Save & go to next step'/>
</form>
