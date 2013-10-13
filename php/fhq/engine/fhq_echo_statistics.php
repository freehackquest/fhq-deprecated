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

/*
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
*/

	$query = '
SELECT
	idquest, 
	name,
	min_score,
	score
FROM 
	quest
WHERE
	for_person = 0
ORDER BY
	min_score, score
';
	
	$result = $db->query( $query );

	echo '<table id="customers">
	<tr class="alt">
		<th>Quest (id, Name)</th>
		<th>Score(Min Score)</th>
		<th>Attempts(Yes)</th>
		<th>Attempts(No)</th>
		<th>Users who passed</th>
	</tr>';

	function getSpec_SQL($table, $idquest, $passed)
	{
		return '
			select 
				count(*) as cnt 
			from 
				'.$table.' t0
			inner join user t1 on t0.iduser = t1.iduser
			where 
				t0.idquest = '.$idquest.' 
				and t0.passed = \''.$passed.'\'
				and t1.role = \'user\'
				;';
	}


	$class = true;
	while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
	{   
		$class = !$class;
		$idquest = $row['idquest'];
		$name = base64_decode($row['name']);
		$min_score = $row['min_score'];
		$score = $row['score'];
		// $count = $row['cnt'];
		
		$plus = 0;
		$minus = 0;
		$users = '';
		{
			$rs = $db->query( getSpec_SQL('tryanswer', $idquest, 'Yes') );
			$plus += mysql_result($rs, 0, 'cnt');
			mysql_free_result($rs);
			$rs = $db->query( getSpec_SQL('tryanswer_backup', $idquest, 'Yes') );
			$plus += mysql_result($rs, 0, 'cnt');
			mysql_free_result($rs);
		}
		
		{
			$rs = $db->query( getSpec_SQL('tryanswer', $idquest, 'No') );
			$minus += mysql_result($rs, 0, 'cnt');
			mysql_free_result($rs);
			$rs = $db->query( getSpec_SQL('tryanswer_backup', $idquest, 'No') );
			$minus += mysql_result($rs, 0, 'cnt');
			mysql_free_result($rs);
		}

		{
			$rs = $db->query( '
				select 
					t0.iduser, 
					t0.nick, 
					t0.username 
				from 
					user t0
				inner join userquest t1 on t0.iduser = t1.iduser 
				where
					t0.role = \'user\'
					and t1.idquest = '.$idquest.'
					and t1.stopdate <> \'0000-00-00 00:00:00\''
			);
			while ($row2 = mysql_fetch_row($rs, MYSQL_ASSOC)) // Data
			{   
				//$class = !$class;
				//$username = base64_decode($row2['username']);
				$nick = $row2['nick'];
				//$iduser = $row2['iduser'];
				$users .= '['.$nick.'] ';
			}
			mysql_free_result($rs);
		}
		
		echo ' 
			<tr '.($class ? 'class="alt"' : '').' >
				<td><a href=main.php?content_page=view_quest&id='.$idquest.'>'.$idquest.', '.$name.'</a></td>
				<td>+'.$score.' ( >='.$min_score.') </td>
				<td>'.$plus.'</td>
				<td>'.$minus.'</td>
				<td>'.$users.'</td>
			</tr>';
		echo '</pre>';
	}
	mysql_free_result($result);
	echo '</table>';
	exit;
};

?>

