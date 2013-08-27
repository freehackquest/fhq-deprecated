<center><h3>FBI admin panel</h3></center>
<form method = "POST">
password: <input type = "password" name="password">
<!-- classic auth bypass. Please, keep it simple -->
</form>
<?php
if (isset($_POST['password']))
    if ($_POST['password'] == "' or '1'='1" or $_POST['password'] == "' or '1'='1' --" or $_POST['password'] == "' or '1'='1' -- -"
	    or $_POST['password'] == "' or 1=1 --" or $_POST['password'] == "' or 1=1 -- -" or $_POST['password'] == "' or '1'='1' -- '"
	    or $_POST['password'] == "' or '1'='1' ({ '" or $_POST['password'] == "' or '1'='1' /* '")
		echo "flag: 639940ab0e04231361969fad716850c8";
    else {
	    $stream = fopen ("log", "a");
	    fwrite($stream, $_POST['password']."\n");
	    echo "Access denied!";
	 }