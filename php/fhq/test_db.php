<?
	include("basepage.php");
	include("config.php");
	$db = new database();
	if($db->connect()) echo "connect is true";
	$result = $db->query("select * from user");
	echo $result;
?>
