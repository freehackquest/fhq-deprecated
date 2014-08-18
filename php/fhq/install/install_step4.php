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
	
	if (!isset($config['installation']['step3'])) {
		header ("Location: install_step3.php");
		exit;
	}
	
	if (isset($config['installation']['step4'])) {
		header ("Location: install_step5.php");
		exit;
	}
	
	if (
		isset($_GET['secret0'])
		&& isset($_GET['secret1'])
		&& isset($_GET['secret2'])
		&& isset($_GET['secret3'])
		&& isset($_GET['secret4'])
	)
	{
		$config['secrets'] = array (
			0 => $_GET['secret0'],
			1 => $_GET['secret1'],
			2 => $_GET['secret2'],
			3 => $_GET['secret3'],
			4 => $_GET['secret4']
		);

		// $results = print_r($arr, true); // $results now contains output from print_r
		$config['installation']['step4'] = 'ok';
		file_put_contents('config.php', '<? $config = '.var_export($config, true).'; ?>');
		header ("Location: install_step5.php");
		exit;	
	}
?>
<h1> Install (step4) </h1>

Configure secrets: <br>
<form>
	Secret 0: <input type='text' name='secret0'
		value='<? echo isset($_GET['secret0']) ? $_GET['secret0'] : 'sol1'; ?>'/> <br>

	Secret 1: <input type='text' name='secret1'
		value='<? echo isset($_GET['secret1']) ? $_GET['secret1'] : '....'; ?>'/> <br>
		
	Secret 2: <input type='text' name='secret2'
		value='<? echo isset($_GET['secret2']) ? $_GET['secret2'] : '++++'; ?>'/> <br>
		
	Secret 3: <input type='text' name='secret3'
		value='<? echo isset($_GET['secret3']) ? $_GET['secret3'] : '----'; ?>'/> <br>

	Secret 4: <input type='text' name='secret4'
		value='<? echo isset($_GET['secret4']) ? $_GET['secret4'] : '===='; ?>'/> <br>

	<input type='submit' name='' value='Save & go to next step'/>
</form>
