<?
	if (!file_exists('config.php')) {
		header ("Location: install_step01.php");
		exit;
	}
