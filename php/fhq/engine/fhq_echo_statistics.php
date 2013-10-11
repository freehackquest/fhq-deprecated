<?

include_once "fhq_class_security.php";

function echo_statistics()
{
	$security = new fhq_security();
	$db = new fhq_database();

	if(!$security->isAdmin() && !$security->isTester())
	{
		echo '<font color="#ff0000">Not found page</font>';
		exit;
	}

	$query = 'SELECT
  userquest.idquest, 
  quest.name,
  count(*) as cnt
FROM `userquest` 
  INNER JOIN quest ON userquest.idquest = quest.idquest 
WHERE 
	stopdate <> \'00:00:00 00-00-0000\' 
	and 
	quest.for_person = 0
GROUP BY 
	userquest.idquest
ORDER BY 
	cnt DESC
    
';
	
	
	$result = $db->query( $query );

	echo '<table id="customers">
	<tr class="alt">
		<th>Quest (id, Name)</th>
		<th>Users which passed this quest</th>
		<th>Successful Attempts</th>
		<th>No Successful Attempts</th>
	</tr>';

	$class = true;
	while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
	{   
		$class = !$class;
		$idquest = $row['idquest'];
		$name = base64_decode($row['name']);
		$count = $row['cnt'];
		
		$plus = 0;
		$minus = 0;
		
		{
			$rs = $db->query( 'select count(*) as cnt from tryanswer where idquest = '.$idquest.' and passed = \'Yes\';' );
			$plus += mysql_result($rs, 0, 'cnt');
			mysql_free_result($rs);
			$rs = $db->query( 'select count(*) as cnt from tryanswer_backup where idquest = '.$idquest.' and passed = \'Yes\';' );
			$plus += mysql_result($rs, 0, 'cnt');
			mysql_free_result($rs);
		}
		
		{
			$rs = $db->query( 'select count(*) as cnt from tryanswer where idquest = '.$idquest.' and passed = \'No\';' );
			$minus += mysql_result($rs, 0, 'cnt');
			mysql_free_result($rs);
			$rs = $db->query( 'select count(*) as cnt from tryanswer_backup where idquest = '.$idquest.' and passed = \'No\';' );			
			$minus += mysql_result($rs, 0, 'cnt');
			mysql_free_result($rs);
		}

		echo ' 
			<tr '.($class ? 'class="alt"' : '').' >
				<td><a href=main.php?content_page=view_quest&id='.$idquest.'>'.$idquest.', '.$name.'</a></td>
				<td>'.$count.'</td>
				<td>'.$plus.'</td>
				<td>'.$minus.'</td>
			</tr>';
		echo '</pre>';
	}
	mysql_free_result($result);
	echo '</table>';
	exit;
};

?>

