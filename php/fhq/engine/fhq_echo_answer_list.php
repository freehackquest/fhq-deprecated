<?

include_once "fhq_class_security.php";

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
		tryanswer ta
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
			
		//echo '<pre id="'.$idelem.'">ID: <b>'.$iduser.'</b>; Email: <b>'.$username.'</b>; Nick: '.$nick.'; Score: '.$score.'; Role: <b>'.$role.'</b>; ';
		/*if(substr($password , 0, 12) == 'notactivated')
		{
			echo '<br><br/>url for activate account: <br/><b>http://fhq.keva.su/registration.php?foractivate='.substr($password , 12, 32).'</b><br/><br/>';
			echo '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
				load_content_page(\'send_mail_again\', 
					{
						page : '.$page.',
						iduser : '.$iduser.',
						email: \''.$username.'\'
					}
				);
			">Send mail again</a> ';
			
			echo ' <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
			
				if(delete_user())
				{
					load_content_page(\'remove_user\', 
						{
							page : '.$page.',
							iduser : '.$iduser.',
							email: \''.$username.'\'
						}
					);
				};
			">Remove user</a>';
		}
		else
		{
			echo '<br>';
			$roles;
			$roles['admin'] = 'Administrator';
			$roles['tester'] = 'Tester';
			$roles['user'] = 'User';
			$roles['god'] = 'God';
			echo '<select id="'.$idelem.'_select_new_role">';

			foreach ($roles as $roleid => $rolename) {
				echo '<option '.($role == $roleid ? 'selected' : '').' value="'.$roleid.'">'.$rolename.'</option>';
			}
			echo '</select>';

			echo '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
			
				var role = document.getElementById(\''.$idelem.'_select_new_role\').value;
				load_content_page(\'user_set_new_role\', 
					{ 
						page : \''.$page.'\',
						iduser : '.$iduser.',
						role : document.getElementById(\''.$idelem.'_select_new_role\').value
					}
				);
			">Set new role</a><br>';
			
			echo '<input id="'.$idelem.'_edit_new_nick" type="text" value="'.$nick.'"/>';

			echo '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
				load_content_page(\'user_set_new_nick\', 
					{
						page : '.$page.',
						iduser : '.$iduser.',
						nick : document.getElementById(\''.$idelem.'_edit_new_nick\').value
					}
				);
			">Set new nick</a><br>';
		}
		* */
		echo '</pre>';
	}
	mysql_free_result($result);
	echo '</table>';
	exit;
};

?>

