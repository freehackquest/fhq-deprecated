<?
	exit;
	include_once "config/config.php";
	include_once "engine/fhq.php";
	$db = new fhq_database();
	$db->connect($config);
	
	// print($result);
	
	{
		$answer_list = new fhq_answer_list();
		
		$result = $db->query("select iduser, idquest from tryanswer where passed = 'Yes'");
		while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
		{   
			$iduser = $row['iduser'];
			$idquest = $row['idquest'];
			echo $iduser." ".$idquest."<br>";
			$answer_list->movedToBackup($iduser, $idquest);
		}
		mysql_free_result($result);
	}
?>
