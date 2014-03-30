<?
	if (!isset($_GET['flag']) || !isset($_GET['idteam']))
	{
		echo '
			<form>
				<table>
					<tr>
						<td>Flag:</td>
						<td><input type="text" name="flag" value=""/></td>
					</tr>
					<tr>
						<td>ID Team:</td>
						<td><input type="text" name="idteam" value=""/></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="Send"/></td>
					</tr>
				</table>
			</form>
		';
		exit;
	}
	session_start();
	include_once "engine/fhq_base.php";
	$flag_was_not_accepted = "[FLAG WAS NOT ACCEPTED]";
	
	if(!isset($_GET['idteam']) && !isset($_GET['flag'])) {
		$db->close();
		die($flag_was_not_accepted);
	}
	
	$idteam = $_GET['idteam'];
	$flag = strtoupper($_GET['flag']);
	
	if (!is_numeric($idteam)) {
		$db->close();
		die($flag_was_not_accepted." (4 - id team must be number)");
	}
	$idteam = (int)$idteam;
	
	// check team
	$result = $db->query("select count(*) as cnt from user where iduser = $idteam");
	$row = mysql_fetch_array( $result );
	mysql_free_result($result);
	if ($row['cnt'] == 0) {
		$db->close();
		die($flag_was_not_accepted." (5 - idteam not found)");
	}

	// 6a331fd2-133a-4713-9587-12652d34666d
	if (!preg_match("/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}$/", $flag))
		die($flag_was_not_accepted." (6 - flag has wrong format)");

	// update flag
	$db->query("update flags_live set user_passed = $idteam where flag = '$flag' and date_end > NOW() and owner <> $idteam and user_passed = 0");

	$result = $db->query("select *, date_end - NOW() as ti from flags_live where flag = '$flag'");
	$row = mysql_fetch_array( $result );
	mysql_free_result($result);
	if (!$row) {
		$db->close();
		die($flag_was_not_accepted." (7 - flag is not exists or very old)");
	} else {
		if ($row['ti'] < 0) {
			$db->close();
			die($flag_was_not_accepted." (8 - flag is old)");
		}
		if ($row['owner'] == $idteam) {
			$db->close();
			die($flag_was_not_accepted." (9 - it is your flag)");
		}
		if ($row['user_passed'] == $idteam) {
			$db->close();
			die("FLAG ACCEPTED");
		}
		if ($row['user_passed'] != $idteam) {
			$db->close();
			die($flag_was_not_accepted." (10 - flag is already passed)");
		}
	}
	$db->close();
	die($flag_was_not_accepted." (10 - I don't know)");
?>
