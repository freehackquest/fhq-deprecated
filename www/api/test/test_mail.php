<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

exit;

// include_once "../../config/config.php";
$curdir_test_mail = dirname(__FILE__);
include_once ($curdir_test_mail."/../api.lib/api.mail.php");
include_once ($curdir_test_mail."/../../config/config.php");

if(!isset($_GET['email']))
{
  echo "not found parametr ?email=";
  exit;
};


$email = $_GET['email'];
echo "To: ".$email."<br>";
$subject = 'Test Mail';
echo "Subject: ".$subject."<br>";

$body = 'Test Message';
echo 'Body: '.$body.'<br>';

$error = "";
echo "try send email<br>";

// send($config, $to_, $cc_, $bcc_, $subject, $body, &$errormsg)

APIMail::send($config, $email,'','',$subject, $body, $error);
echo "Sended<br>";
echo "Error: ".$error.'<br>';
