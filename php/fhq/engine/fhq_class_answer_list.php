<?
	$curdir = dirname(__FILE__);

	include_once "$curdir/../config/config.php";
	include_once "$curdir/fhq_class_security.php";
	include_once "$curdir/fhq_class_database.php";

	class fhq_answer_list
	{
		function addTryAnswer($iduser, $idquest, $answer_try, $answer_real, $passed)
		{
			$db = new fhq_database();
			$answer_try = base64_encode(htmlspecialchars($answer_try));
			$answer_real = base64_encode(htmlspecialchars($answer_real));
			$nowdate = date('Y-m-d H:i:s');
			$query = "INSERT INTO tryanswer(iduser, idquest, answer_try, answer_real, passed, datetime_try) VALUES ($iduser, $idquest, '$answer_try', '$answer_real', '$passed', '$nowdate');";
			$result = $db->query($query);
		}

		function movedToBackup($iduser, $idquest)
		{
			$db = new fhq_database();
			$query = "INSERT INTO tryanswer_backup (iduser, idquest, answer_try, answer_real, passed, datetime_try) 
				SELECT iduser, idquest, answer_try, answer_real, passed, datetime_try FROM tryanswer WHERE iduser = $iduser and idquest = $idquest";
			$result = $db->query($query);

			$query = "DELETE FROM tryanswer WHERE iduser = $iduser and idquest = $idquest";
			$result = $db->query($query);
		}
	}
	//---------------------------------------------------------------------
?>
