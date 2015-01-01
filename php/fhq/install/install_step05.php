<?php
	if (file_exists("../config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}
	
	$current_step = 5;
	include_once("install_base.php");
	
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

		include_once("install_gotonextstep.php");	
	}
?>
<h1> Install (Step <? echo $current_step; ?>) </h1>

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

	Auth: <select name="auth">
		<option <? echo isset($_GET['auth']) ? ($_GET['auth'] == 'yes' ? 'selected="selected"' : '') : 'selected="selected"'; ?> value="yes">Yes</option>
		<option <? echo isset($_GET['auth']) ? ($_GET['auth'] == 'no' ? 'selected="selected"' : '') : ''; ?> value="no">No</option>
	</select>
	<br>

	Allow: <select name="allow">
		<option <? echo isset($_GET['allow']) ? ($_GET['allow'] == 'yes' ? 'selected="selected"' : '') : 'selected="selected"'; ?> value="yes">Yes</option>
		<option <? echo isset($_GET['allow']) ? ($_GET['allow'] == 'no' ? 'selected="selected"' : '') : ''; ?> value="no">No</option>
	</select>
	<br/>

	<input type='submit' name='' value='Save & go to next step'/>
</form>
