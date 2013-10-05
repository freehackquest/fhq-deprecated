<?

include_once "fhq_class_security.php";

function echo_users()
{
	$security = new fhq_security();
	$db = new fhq_database();
	
	if(!$security->isAdmin())
	{
		echo '<font color="#ff0000">Not found quest with id = '.$id.'</font>';
		exit;
	}
		
	$records_on_page = 4;

	$query = 'SELECT count(*) cnt FROM user';
	$result = $db->query( $query );
	$allusers = mysql_result($result, 0, 'cnt');
	mysql_free_result($result);
	
	echo 'Users ( '.$allusers.' ) :<br><br>';
	
	$page = 0;
	if(isset($_GET['page']) && is_numeric($_GET['page']))
		$page = $_GET['page'];
	
	/*$find = "";
	if(isset($_GET['find']) && is_numeric($_GET['page']))
		$page = $_GET['find'];*/
		
	if($allusers / $records_on_page > 0)
	{
		$count_pages = $allusers / $records_on_page;
		
		for($i = 0; $i < $count_pages; $i++)
		{
			if($i == $page)
				echo ' [ '.($i+1).' ] ';
			else
				echo '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'users\', { page : \''.$i.'\'} );">'.($i+1).'</a>';
		}
	}
	echo '<br><br>';
	
	$start_record = $page*$records_on_page;
	
	$query = 'SELECT * FROM user LIMIT '.$start_record.','.$records_on_page; //.(*$onpage).','.$onpage;
	
	$result = $db->query( $query );
	
	while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
	{      
		$iduser = $row['iduser'];
		$username = strtolower(base64_decode($row['username']));
		$nick = $row['nick'];
		$score = $row['score'];
		$role = $row['role'];
		$idelem = 'user'.$iduser;

		echo '<pre id="'.$idelem.'">ID: <b>'.$iduser.'</b>; Email: <b>'.$username.'</b>; Nick: '.$nick.'; Score: '.$score.'; Role: <b>'.$role.'</b>; ';
		
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
		echo '</pre>';
	}
	mysql_free_result($result);

	exit;
};

?>

