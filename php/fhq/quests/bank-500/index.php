You have signed bank check on $1: <br />
<?php
$key = "banks3cr3tk3y";
$bank = "bankofhackers";
$sum = 100;
echo "<h3>";
echo $b64 = base64_encode($bank."|".$sum);
echo "|".sha1($key.base64_decode($b64))."</h3>";
?>
You must checkout from bank $1 000 000 by one transaction.<br />
Please  input in this form your check, please:
<form method = "post">
<input type = "text" size = "100" name = "check">
<input type = "submit" value = "Checkout money">
</form>
<?php
if (isset($_POST['check'])) {          
    $parts = explode("|", $_POST['check']);
    $check = base64_decode($parts[0]);
    $delim = strrpos($check, "|");
    $sum = (int)substr($check, $delim+1, strlen($check)); 
    //echo "sum = $sum<br />";
    $name = substr($check, 0, $delim);
    //echo "name = $name<br />";
    if (sha1($key.$check) == $parts[1]) {
	$sum = $sum / 100;
	echo "You successfully checkout $$sum to ".htmlentities($name)."!";
	if ($sum == 1000000) {
	    echo "<h2>Also, you got a flag: 8a4b3ce5b55d5084593ecc51f82a5213</h2>";
	}
    } else echo "Sorry, check is not valid!";
}
?>

<!-- hint: key = 13 by length -->
<!-- hint2: key... check... -->
