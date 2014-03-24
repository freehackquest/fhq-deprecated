<?
$curdir = dirname(__FILE__);

include_once "$curdir/fhq_class_security.php";

function echo_answer_list()
{
	$security = new fhq_security();
	$db = new fhq_database();

	if(!$security->isAdmin() && !$security->isTester())
	{
		echo '<font color="#ff0000">Not found page</font>';
		exit;
	}

	$records_on_page = 25;

	$table = 'tryanswer';
	$forjson = '';
	if(isset($_GET['backup']))
	{
		$table = 'tryanswer_backup';
		$forjson = ', backup : \'\'';
	}

	$query = 'SELECT count(*) cnt FROM '.$table;
	$result = $db->query( $query );
	$allanswers = mysql_result($result, 0, 'cnt');
	mysql_free_result($result);
	
	echo 'Answers ( '.$allanswers.' ) :<br><br>';
	
	$page = 0;
	if(isset($_GET['page']) && is_numeric($_GET['page']))
		$page = $_GET['page'];
		
	if($allanswers / $records_on_page > 0)
	{
		$count_pages = $allanswers / $records_on_page;
		
		for($i = 0; $i < $count_pages; $i++)
		{
			if($i == $page)
				echo ' [ '.($i+1).' ] ';
			else
				echo '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'answer_list\', { page : \''.$i.'\' '.$forjson.' } );">'.($i+1).'</a>';
		}
	}
	echo '<br><br>';
	
	$start_record = $page*$records_on_page;
	
	$query = '
	SELECT 
		ta.datetime_try,
		ta.passed,
		ta.idquest,
		ta.iduser,
		ta.answer_try,
		ta.answer_real,
		u.nick,
		u.username,
		q.name
	FROM 
		'.$table.' ta
	INNER JOIN user u ON u.iduser = ta.iduser
	INNER JOIN quest q ON q.idquest = ta.idquest
		
	ORDER BY 
		datetime_try DESC

	LIMIT '.$start_record.','.$records_on_page;
	
	$result = $db->query( $query );

	echo '<table id="customers">
	<tr class="alt">
		<th>Date Time</th>
		<th>Passed</th>
		<th>User (id, Nick, Email)</th>
		<th>Quest (id, Name)</th>
		<th>Answer Try</th>
		<th>Answer Real</th>
		
	</tr>';

	$class = true;
	while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
	{   
		$class = !$class;   
		$iduser = $row['iduser'];
		$nick = $row['nick'];
		$email = strtolower(base64_decode($row['username']));
		$idquest = $row['idquest'];
		$name = base64_decode($row['name']);
		$answer_try = base64_decode($row['answer_try']);
		$answer_real = base64_decode($row['answer_real']);
		$passed = $row['passed'];
		$datetime_try = $row['datetime_try'];
		
		$strclass = ($class ? 'class="alt"' : '');
		if($passed == 'Yes')
			$strclass = 'class="alt2"';
		
		echo ' 
			<tr '.$strclass.'>
				<td>'.$datetime_try.'</td>
				<td>'.$passed.'</td>
				<td>('.$iduser.', '.$nick.', '.$email.')</td>
				<td><a href=main.php?content_page=view_quest&id='.$idquest.'>'.$idquest.', '.$name.'</a></td>
				<td>'.$answer_try.'</td>
				<td>'.$answer_real.'</td>
			</tr>';
		echo '</pre>';
	}
	mysql_free_result($result);
	echo '</table>';
	exit;
};

?>

