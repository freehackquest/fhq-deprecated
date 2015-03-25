<?php

exit;

include_once "config/config.php";
include_once "engine/fhq_class_security.php";
include_once "engine/fhq_class_database.php";
include_once "engine/fhq_class_mail.php";

if(!isset($_GET['email']))
{
  echo "not found parametr ?email=";
  exit;
};


$email = $_GET['email'];
echo "send to mail: ".$email."<br>";
$security = new fhq_security();
$db = new fhq_database();
$mail = new fhq_mail();
echo "mail created <br>";

$error = "";
echo "try send email<br>";
$mail->send($email,'','','Test Mail', 'Test messages', $error);
echo "sended";
echo $error;
?>
