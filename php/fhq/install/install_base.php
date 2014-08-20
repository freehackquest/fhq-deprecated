<?
	if (!file_exists('config.php')) {
		header ("Location: install_step01.php");
		exit;
	}

	include_once('config.php');
	$curr_step = "".($current_step);
	$prev_step = "".($current_step - 1);
	$next_step = "".($current_step + 1);
	
	if (strlen($curr_step) == 1) {
		$curr_step = '0'.$curr_step;
	}
	
	if (strlen($prev_step) == 1) {
		$prev_step = '0'.$prev_step;
	}
	
	if (strlen($next_step) == 1) {
		$next_step = '0'.$next_step;
	}
	
	
	if (!isset($config['installation']['step'.$prev_step]) && $current_step > 1) {
		header ('Location: install_step'.$prev_step.'.php');
		exit;
	}

	if (isset($config['installation']['step'.$curr_step])) {
		header ('Location: install_step'.$next_step.'.php');
		exit;
	}
