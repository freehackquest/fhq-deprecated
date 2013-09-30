<?
  exit;
	include_once "config/config.php";
	$db = new fhq_database();
	$db->connect($config);
	$result = $db->query("select * from user");
	print($result);
?>
