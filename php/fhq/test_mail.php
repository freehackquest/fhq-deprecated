<?

$email = $_GET['email'];

$subject = "Test Mail";

$message = "
<html>
<head>
  <title>$subject</title>
</head>
<body>
Test Mail from Free-Hack-Quest!<br>
."</a>
</body>
</html>
";
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: noreply@fhq.keva.su'."\r\n";
			$headers .= 'Reply-To: noreply@fhq.keva.su'."\r\n";
			$headers .= 'X-Mailer: PHP/'.phpversion();
			
			mail($email, $subject, $message, $headers);
?>