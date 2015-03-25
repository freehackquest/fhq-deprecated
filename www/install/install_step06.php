<?php
	if (file_exists("../config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}
	
	$current_step = 6;
	include_once("install_base.php");
	
	if (
		isset($_GET['type'])
		&& isset($_GET['allow'])
	)
	{
		$config['registration'] = array(
			'type' => $_GET['type'],
			'allow' => $_GET['allow'],
		);

		include_once("install_gotonextstep.php");	
	}
?>
<h1> Install (step6) </h1>

Configure registration: <br>
<form>
	<br>
	Allow: <select name="allow">
		<option <?php echo isset($_GET['allow']) ? ($_GET['allow'] == 'yes' ? 'selected="selected"' : '') : 'selected="selected"'; ?> value="yes">Yes</option>
		<option <?php echo isset($_GET['allow']) ? ($_GET['allow'] == 'no' ? 'selected="selected"' : '') : ''; ?> value="no">No</option>
	</select>
	<br>
	
	Type registration: <select name="type">
		<option <?php echo isset($_GET['type']) ? ($_GET['type'] == 'email' ? 'selected="selected"' : '') : 'selected="selected"'; ?> value="email">E-mail (confirm by email)</option>
		<option <?php echo isset($_GET['type']) ? ($_GET['type'] == 'simple' ? 'selected="selected"' : '') : ''; ?> value="simple">Simple (without confirm)</option>
	</select>
	<br>

	<input type='submit' name='' value='Save & go to next step'/>
</form>
