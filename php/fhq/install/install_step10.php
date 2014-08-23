<?
	if (file_exists("../config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}
	
	$current_step = 10;
	include_once("install_base.php");
	
	if (
		isset($_GET['finish'])
	)
	{
		unset($config['installation']);
		
		$config['nfs_share'] = '/var/www/test';
		$config['targetDate'] = array();
		$config['targetDate']['day'] = 7;
		$config['targetDate']['month'] = 10;
		$config['targetDate']['year'] = 2013;
		$config['targetDate']['hour'] = 10;
		$config['targetDate']['minute'] = 0;
		$config['targetDate']['second'] = 0;

		$config['finishDate'] = array();
		$config['finishDate']['day'] = 18;
		$config['finishDate']['month'] = 10;
		$config['finishDate']['year'] = 2030;
		$config['finishDate']['hour'] = 21;
		$config['finishDate']['minute'] = 0;
		$config['finishDate']['second'] = 0;
		
		
		file_put_contents('../config/config.php', '<? $config = '.var_export($config, true).'; ?>');
		if (file_exists('../config/config.php')) {
			unlink('config.php');
			header ('Location: ../index.php');
		} else {
			echo 'please copy install/config.php to config/config.php';
		}
		exit;
	}
?>
<h1> Install (step <? echo $current_step; ?>) </h1>

Finish: <br>
<form>
	<br>
	<input type='submit' name='finish' value='Save & go to main page'/>
</form>
