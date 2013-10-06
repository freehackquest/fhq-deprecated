<?
   include_once "engine/fhq.php";
   $db = new fhq_database();
   $security = new fhq_security();
	$registration = new fhq_registration();
   $email = "admin";
   $registration->removeEmail($email);
   $username = base64_encode(strtoupper($email));
   $password = "admin";
   $password_hash = $security->tokenByData( array($password, $username, strtoupper($email)));
   $query = "INSERT user( username, password, nick, role, score ) VALUES ('$username','$password_hash','$nickname','admin', 0);";
   $result = $db->query($query);
   mysql_free_result($result);
   echo "complited";

?>
