<?php
	if (file_exists("../config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}
	
	$current_step = 3;
	include_once("install_base.php");
	
	if (isset($_GET['httpname']))
	{
		include_once('config.php');
		$config['httpname'] = $_GET['httpname'];
		
		include_once("install_gotonextstep.php");
	}
?>
<h1> Install (step3) </h1>

Configure host name: <br>
<form>
	Your host name: <input type='text' name='httpname'
		value='<?php echo isset($_GET['httpname']) ? $_GET['httpname'] : 'http://localhost/'; ?>'/> <br>

	<input type='submit' name='' value='Check & go to next step'/>
</form>
