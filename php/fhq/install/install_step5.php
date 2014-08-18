<?
	if (file_exists("../config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}
	
	if (!file_exists('config.php')) {
		header ("Location: install_step1.php");
		exit;
	}
	
	include_once('config.php');
	
	if (!isset($config['installation']['step4'])) {
		header ("Location: install_step4.php");
		exit;
	}
	
	if (isset($config['installation']['step5'])) {
		header ("Location: install_step6.php");
		exit;
	}
	
	if (
		isset($_GET['username'])
		&& isset($_GET['password'])
		&& isset($_GET['host'])
		&& isset($_GET['port'])
		&& isset($_GET['from'])
		&& isset($_GET['auth'])
		&& isset($_GET['allow'])
	)
	{
		$config['mail'] = array(
			'username' => $_GET['username'],
			'password' => $_GET['password'],
			'host' => $_GET['host'],
			'port' => $_GET['port'],
			'from' => $_GET['from'],
			'auth' => $_GET['auth'] == 'yes' ? true : false,
			'allow' => $_GET['allow'],
		);

		$config['installation']['step5'] = 'ok';
		file_put_contents('config.php', '<? $config = '.var_export($config, true).'; ?>');
		header ("Location: install_step6.php");
		exit;	
	}
?>
<h1> Install (step5) </h1>

Configure mail (google): <br>
<form>
	E-mail: <input type='text' name='username'
		value='<? echo isset($_GET['username']) ? $_GET['username'] : 'test@gmail.com'; ?>'/> <br>

	Password: <input type='text' name='password'
		value='<? echo isset($_GET['password']) ? $_GET['password'] : 'test'; ?>'/> <br>
		
	Host: <input type='text' name='host'
		value='<? echo isset($_GET['host']) ? $_GET['host'] : 'ssl://smtp.gmail.com'; ?>'/> <br>

	Port: <input type='text' name='port'
		value='<? echo isset($_GET['port']) ? $_GET['port'] : '465'; ?>'/> <br>
		
	From: <input type='text' name='from'
		value='<? echo isset($_GET['from']) ? $_GET['from'] : 'test@gmail.com'; ?>'/> <br>
		
	Auth: <input type='text' name='auth'
		value='<? echo isset($_GET['auth']) ? $_GET['auth'] : 'yes'; ?>'/> <br>
		
	Allow: <input type='text' name='allow'
		value='<? echo isset($_GET['allow']) ? $_GET['allow'] : 'yes'; ?>'/> <br>

	<input type='submit' name='' value='Save & go to next step'/>
</form>
