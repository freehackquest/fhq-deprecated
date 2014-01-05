<?
	$flag_was_not_accepted = "FLAG WAS NOT ACCEPTED";
    $dblink = mysql_connect("localhost","jury","jury");
    if(!$dblink)
		die($flag_was_not_accepted." (1 - it don't connected to database)");

	$db_selected = mysql_select_db("jury");
	
	if(!$db_selected)
		die($flag_was_not_accepted." (2 - it don't selected database)");
	
	
	$flag = "";
	$team = "";
	if(!isset($_GET['your_team']) && !isset($_GET['flag']))
		die($flag_was_not_accepted);
	
	$team = strtoupper($_GET['your_team']);
	$flag = strtoupper($_GET['flag']);
	
	if (!ereg("^[A-Z0-9]*$", $team))
		die($flag_was_not_accepted." (3 - name of team has wrong format )");

	
	$result = mysql_query("select count(*) as con from teams where UPPER(name) = '".$team."'");
	$count = mysql_fetch_array( $result )['con'];
	if($count == 0)
		die($flag_was_not_accepted." (4 - team don't found)");

	$result = mysql_query("select id from teams where UPPER(name) = '".$team."'");
	$id_team = mysql_fetch_array( $result )['id'];
	
	// 6a331fd2-133a-4713-9587-12652d34666d

	if (!ereg("^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}$", $flag))
		die($flag_was_not_accepted." (5 - flag has wrong format)");
	
	// check on exists
	$result = mysql_query("select count(*) as con from flags where UPPER(flag) = '".$flag."'");
	$count = mysql_fetch_array( $result )['con'];
	if($count == 0)
		die($flag_was_not_accepted." (6 - flag don't found)");

	// check 
	$result = mysql_query("select count(*) as con from flags 
		where UPPER(flag) = '".$flag."'
		and id_team_passed = 0
	");
	$count = mysql_fetch_array( $result )['con'];
	if($count == 0)
		die($flag_was_not_accepted." (7 - flag is already passed)");
	
	// 
	$result = mysql_query("select count(*) as con from flags 
		where UPPER(flag) = '".$flag."'
		and id_team_owner <> ".$id_team."
	");
	$count = mysql_fetch_array( $result )['con'];
	if($count == 0)
		die($flag_was_not_accepted." (8 - it is your flag)");
	
	//
	$now = date("Y-m-d H:i:s");
	$query = "select count(*) as con from flags
		where UPPER(flag) = '".$flag."'
		and dt_start < '".$now."'
		and '".$now."' < dt_end
	";
	$result = mysql_query($query);
	$count = mysql_fetch_array( $result )['con'];
	if($count == 0)
		die($flag_was_not_accepted." (9 - flag is old)");

	
	// update
	$query = "update flags set id_team_passed = ".$id_team."
		where 
			UPPER(flag) = '".$flag."'
			and dt_start < '".$now."'
			and '".$now."' < dt_end
			and id_team_owner <> ".$id_team."
			and id_team_passed = 0
	";

	$result = mysql_query($query);
	if($result == 1)
		echo "FLAG ACCEPTED".$query;
	else
		die($flag_was_not_accepted." (10 - I don't now)");

	mysql_close($dblink);
?>
