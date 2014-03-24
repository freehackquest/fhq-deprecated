<?
$curdir = dirname(__FILE__);
include_once "$curdir/fhq_class_security.php";

function echo_users()
{
	$security = new fhq_security();
	$db = new fhq_database();

	if(!$security->isAdmin())
	{
		echo '<font color="#ff0000">Not found page</font>';
		exit;
	}

	$records_on_page = 6;

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
	
	echo "<br>
				<table cellspacing=2 cellpadding=10 class='alt' id='customers'>
					<tr class='alt'>
						<th width='100'>ID</th>
						<th>E-Mail</th>
						<th>Nick</th>
						<th>Score</th>
						<th>Role</th>
						<th>Last IP</th>
						<th>Last Date Signup</th>
						<th>Admin Functions</th>
					</tr>
			";
	$bClass = false;

	$result = $db->query( $query );
	
	while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
	{  

		$iduser = $row['iduser'];
		$username = strtolower(base64_decode($row['username']));
		$nick = $row['nick'];
		$score = $row['score'];
		$role = $row['role'];
		$password = $row['password'];
		$date_last_signup = $row['date_last_signup'];
		$last_ip = $row['last_ip'];
		$idelem = 'user'.$iduser;

		
		$strclass = ''; 
		if ($bClass) 
			$strclass = " class='alt' ";
		$bClass = !$bClass;
		
		
		$admin_funcs = '';
		$admin_funcs .= '<pre id="'.$idelem.'">';
		
		if(substr($password , 0, 12) == 'notactivated')
		{
			$admin_funcs .= '<br><br/>url for activate account: <br/><b>http://fhq.keva.su/registration.php?foractivate='.substr($password , 12, 32).'</b><br/><br/>';
			$admin_funcs .= '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
				load_content_page(\'send_mail_again\', 
					{
						page : '.$page.',
						iduser : '.$iduser.',
						email: \''.$username.'\'
					}
				);
			">Send mail again</a> ';
			
			$admin_funcs .= ' <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
			
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
			if($iduser != $security->iduser())
			{
				$admin_funcs .= '<br>';
				$roles;
				$roles['admin'] = 'Administrator';
				$roles['tester'] = 'Tester';
				$roles['user'] = 'User';
				$roles['god'] = 'God';
				$admin_funcs .= '<select id="'.$idelem.'_select_new_role">';

				
				foreach ($roles as $roleid => $rolename) {
					$admin_funcs .= '<option '.($role == $roleid ? 'selected' : '').' value="'.$roleid.'">'.$rolename.'</option>';
				}
				$admin_funcs .= '</select>';

				$admin_funcs .= '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
				
					var role = document.getElementById(\''.$idelem.'_select_new_role\').value;
					load_content_page(\'user_set_new_role\', 
						{ 
							page : \''.$page.'\',
							iduser : '.$iduser.',
							role : document.getElementById(\''.$idelem.'_select_new_role\').value
						}
					);
				">Set new role</a>';
			}
			$admin_funcs .= '<br>';
			
			$admin_funcs .= '<input id="'.$idelem.'_edit_new_nick" type="text" value="'.$nick.'"/>';

			$admin_funcs .= '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
				load_content_page(\'user_set_new_nick\', 
					{
						page : '.$page.',
						iduser : '.$iduser.',
						nick : document.getElementById(\''.$idelem.'_edit_new_nick\').value
					}
				);
			">Set new nick</a><br>';
		}
		$admin_funcs .= '</pre>';
		
		echo "<tr $strclass>
					<td>$iduser</td>
					<td>$username</td>
					<td>$nick</td>
					<td>$score</td>
					<td>$role</td>
					<td>$last_ip</td>
					<td>$date_last_signup</td>
					<td>$admin_funcs</td>					
		</tr>";
	}
	mysql_free_result($result);
	echo "</table>";

	exit;
};

?>

