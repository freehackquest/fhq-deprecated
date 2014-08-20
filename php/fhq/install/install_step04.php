<?
	if (file_exists("../config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}
	
	$current_step = 4;
	include_once("install_base.php");
	
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
		include_once("install_gotonextstep.php");	
	}
	
	$secret0 = isset($_GET['secret0']) ? $_GET['secret0'] : 'sol1';
	$secret1 = isset($_GET['secret1']) ? $_GET['secret1'] : '....';
	$secret2 = isset($_GET['secret2']) ? $_GET['secret2'] : '++++';
	$secret3 = isset($_GET['secret3']) ? $_GET['secret3'] : '----';
	$secret4 = isset($_GET['secret4']) ? $_GET['secret4'] : '====';
	
	if (isset($config['secrets'])) {
		$secret0 = isset($config['secrets'][0]) ? $config['secrets'][0] : $secret0;
		$secret1 = isset($config['secrets'][1]) ? $config['secrets'][1] : $secret1;
		$secret2 = isset($config['secrets'][2]) ? $config['secrets'][2] : $secret2;
		$secret3 = isset($config['secrets'][3]) ? $config['secrets'][3] : $secret3;
		$secret4 = isset($config['secrets'][4]) ? $config['secrets'][4] : $secret4;
	}
?>
<h1> Install (step4) </h1>

Configure secrets: <br>
<form>
	Secret 0: <input type='text' name='secret0'
		value='<? echo $secret0; ?>'/> <br>

	Secret 1: <input type='text' name='secret1'
		value='<? echo $secret1; ?>'/> <br>
		
	Secret 2: <input type='text' name='secret2'
		value='<? echo $secret2; ?>'/> <br>
		
	Secret 3: <input type='text' name='secret3'
		value='<? echo $secret3; ?>'/> <br>

	Secret 4: <input type='text' name='secret4'
		value='<? echo $secret4; ?>'/> <br>

	<input type='submit' name='' value='Save & go to next step'/>
</form>
