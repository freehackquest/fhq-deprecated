<?php
	if (file_exists("../config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}

	$current_step = 7;
	include_once("install_base.php");
	
	if (
		isset($_GET['change_nick'])
	)
	{
		$config['profile'] = array(
			'change_nick' => $_GET['change_nick'],
		);

		include_once("install_gotonextstep.php");	
	}
?>
<h1> Install (Step <? echo $current_step; ?>) </h1>

Configure profile: <br>
<form>
	<br>
	Allow Change Nick: <select name="change_nick">
		<option <? echo isset($_GET['change_nick']) ? ($_GET['change_nick'] == 'yes' ? 'selected="selected"' : '') : 'selected="selected"'; ?> value="yes">Yes</option>
		<option <? echo isset($_GET['change_nick']) ? ($_GET['change_nick'] == 'no' ? 'selected="selected"' : '') : ''; ?> value="no">No</option>
	</select>
	<br>
	<br>
	<input type='submit' name='' value='Save & go to next step'/>
</form>
