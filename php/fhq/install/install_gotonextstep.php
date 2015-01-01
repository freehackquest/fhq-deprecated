<?php
	$config['installation']['step'.$curr_step] = 'ok';
	file_put_contents('config.php', '<? $config = '.var_export($config, true).'; ?>');
	header ('Location: install_step'.$next_step.'.php');
	exit;
