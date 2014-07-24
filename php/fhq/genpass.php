<?
	echo "Please uncomment 'exit'";
	exit;


   if (isset($_GET['login']) && isset($_GET['pass']))
   {
		include_once "engine/fhq.php";
		$db = new fhq_database();
		$security = new fhq_security();
		$registration = new fhq_registration();
		$email = $_GET['login'];
		$username = base64_encode(strtoupper($email));

		$password_hash = $security->tokenByData( array($password, $username, strtoupper($email)));
		
		echo "New password: ".$password_hash." <br>";
	}
?>

Add user: <br>
<form>
	Login: <input type='text' name='login' value='admin'/> <br>
	Pass: <input type='text' name='pass' value='admin'/> <br>
	<input type='submit' name='add_user' value='generate pass'/> <br>
</form>


?>
