<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">
</head>
<script>
setTimeout(function(){
	// if(autorefresh.checked == 1)
	window.location.reload(1);
}, 120000);
</script>
<body>

<?
	echo "<h1>Scoreboard</h1>";
	echo "Refresh every 2 minutes!<br><br>";
    $dblink = mysql_connect("localhost","jury","jury");
    if(!$dblink)
		die("Error connect: ".mysql_error());

	$db_selected = mysql_select_db("jury");
	
	if(!$db_selected)
		die("Error select: ".mysql_error());
	
	mysql_query("SET NAMES 'utf8'");

	echo "<table>";
    
    $color = "#F2F2F2";
    
    $result1 = mysql_query("SELECT * FROM services");
    echo "<tr> <td>Team\Services</td> <td>Defence</td> <td>Offence</td> <td>Score</td>";
    $services[] = array();
    $i = 0;
    // headers
    while($row = mysql_fetch_array( $result1 )) {
		echo "<td><b>".$row['name']."</b></td>";
		$services[$i] = $row;
		$i++;
	}
	echo "</tr>";

    $result = mysql_query("SELECT * FROM teams");
	while($row = mysql_fetch_array( $result )) {
		
		if($row['id'] % 2 == 0)
			$color = "#F2F2F2";
		else
			$color = "#E6E6E6";
		
		echo "<tr bgcolor='".$color."'><td>".$row['id'].") <i>".$row['comment']."</i><br>
		<b>".$row['name']."</b><br>".$row['ip_address']."<br>
		<a href='http://".$row['comment2']."'>http://".$row['comment2']."</a>
		</td>";
			
		$query_def = "select count(*) as con from flags where 
			id_team_owner = ".$row['id']." and 
			id_team_passed = 0 and dt_end < '".date("Y-m-d H:i:s")."'
		";

		$result_1 = mysql_query($query_def);
		$count_1 = mysql_fetch_array( $result_1 )['con'];

		echo "<td align='center'>".$count_1."</td>";

		$query_off = "select count(*) as con from flags where
			id_team_passed = ".$row['id']."
			and id_team_passed != id_team_owner";
		
		$result_2 = mysql_query($query_off);
		$count_2 = mysql_fetch_array( $result_2 )['con'];
		echo "<td align='center'>".$count_2."</td>";
		echo "<td align='center'>".($count_1 + $count_2)."</td>";
		 
		for($i = 0; $i < count($services); $i++)
		{
			$query_status = "select id_team_passed as st from flags 
				where 
				id_team_owner = ".$row['id']." and
				id_service = ".$services[$i]['id']."
				and dt_end = (select MAX(dt_end) from flags where id_team_owner = ".$row['id']." and id_service = ".$services[$i]['id'].")
			";

			$result_3 = mysql_query($query_status);
			$st = mysql_fetch_array( $result_3 )['st'];
		
			$color2 = ($st < 0) ?  "#FE642E" : "#66FF33";
			
			$st = ($st < 0) ? "corrupted" : "worked";
			echo "<td bgcolor='".$color2."' valign='middle' align='center'>".$st."</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
	
	$your_team = "";
	if(isset($_GET['your_team']))
		$your_team = $_GET['your_team'];
		
	echo "<br><br>
		<!-- <input id='autorefresh' name='autorefresh' type='checkbox' value='auto refresh'/> -->
		
		<form action='send_flag.php'>
			<table>
				<tr>
					<td align='right' >Your Team:</td>
					<td><input name='your_team' type='text' value='".$your_team."'/><br></td>
				</tr>
				<tr>
					<td align='right'>Flag:</td>
					<td><input name='flag' type='text' value=''/><br></td>
				</tr>
				<tr>
					<td align='right'></td>
					<td><input type='submit' value='Send'/><br></td>
				</tr>
			</table>
		</form>
";
	mysql_close($dblink);
?>

</body>
</html>
