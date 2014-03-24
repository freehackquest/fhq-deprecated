<?
	$rootdir = dirname(__FILE__);

	include_once "$rootdir/../config/config.php";
	include_once "$rootdir/fhq_class_database.php";

	$db = new fhq_database();
	$db->connect($config);
?>
