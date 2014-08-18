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
	
	if (!isset($config['installation']['step2'])) {
		header ("Location: install_step2.php");
		exit;
	}
	
	if (isset($config['installation']['step3'])) {
		header ("Location: install_step4.php");
		exit;
	}
	
	if (isset($_GET['httpname']))
	{
		include_once('config.php');
		$config['httpname'] = $_GET['httpname'];
		$config['installation']['step3'] = 'ok';
		file_put_contents('config.php', '<? $config = '.var_export($config, true).'; ?>');
		header ("Location: install_step4.php");
		exit;
	}
?>
<h1> Install (step3) </h1>

Configure host name: <br>
<form>
	Your host name: <input type='text' name='httpname'
		value='<? echo isset($_GET['httpname']) ? $_GET['httpname'] : 'http://localhost/'; ?>'/> <br>

	<input type='submit' name='' value='Check & go to next step'/>
</form>
