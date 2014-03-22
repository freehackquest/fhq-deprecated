<?
   exit;
   if (isset($_GET['login']) && isset($_GET['pass']) && isset($_GET['role']))
   {
		include_once "engine/fhq.php";
		$db = new fhq_database();
		$security = new fhq_security();
		$registration = new fhq_registration();
		$email = $_GET['login'];
		$nickname = $_GET['nick'];
		// $registration->removeEmail($email);
		$username = base64_encode(strtoupper($email));
		$query = "select count(*) as cnt from user where username='$username'";
		// echo "Query: ".$query."<br>";
		$result = $db->query($query);
		if ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
		{
				$cnt = $row['cnt'];
				// print_r($row);
				// echo "[cnt = $cnt]";
				if ($cnt == 0) {
					$password = $_GET['pass'];
					$role = $_GET['role'];
					$password_hash = $security->tokenByData( array($password, $username, strtoupper($email)));
					$query = "INSERT user( username, password, nick, role, score ) VALUES ('$username','$password_hash','$nickname','$role', 0);";
					$result2 = $db->query($query);
					echo "complited<br>";
				}
				else
				{
					echo "user already exists<br>";
				}
		}
		mysql_free_result($result);
	}
?>

Add user: <br>
<form>
	Login: <input type='text' name='login' value='admin'/> <br>
	Pass: <input type='text' name='pass' value='admin'/> <br>
	Nick: <input type='text' name='nick' value='admin'/> <br>
	Role: <input type='text' name='role' value='admin'/> <br>
	<input type='submit' name='add_user' value='insert'/> <br>
</form>
