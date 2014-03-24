<?
	$curdir = dirname(__FILE__);
	include_once "$curdir/../config/config.php";
	include_once "$curdir/fhq_class_security.php";
	include_once "$curdir/fhq_class_database.php";
	
	class fhq_user_info
	{
		function echo_info()
		{
			$db = new fhq_database();
			$security = new fhq_security();

			

			echo '<pre>
				<a href="javascript:void(0);" id="reload_content" onclick="
					document.getElementById(\'btn_user_info\').innerHTML = \''.mysql_real_escape_string(htmlspecialchars($security->nick())).'\';
				"></a>
				Your name: '.$security->nick().'
				Your score: '.$security->score().'
				Role: '.$security->role().'
				Your place: '.$this->getPlace().' or look <a href=\'scoreboard.php\'>Scoreboard</a><br>
				<input id="edit_new_nick" type="text" value="'.$security->nick().'"/>';
				echo '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
					load_content_page(\'user_set_new_my_nick\', 
						{
							nick : document.getElementById(\'edit_new_nick\').value
						}
					);
				">Set new nick</a><br></pre>';
		}
		
		function setNewMyNick($nick)
		{
			// pass: 672f88b
			$db = new fhq_database();
			$security = new fhq_security();
			$nick = htmlspecialchars($nick);
			$nick = substr($nick, 0, 40);
			$query = 'UPDATE user SET nick = \''.mysql_real_escape_string($nick).'\' WHERE iduser = '.$security->iduser();
			$security->setNick($nick);
			$result = $db->query( $query );
		}
		
		function getPlace()
		{
			$db = new fhq_database();
			$security = new fhq_security();

			$place = "";
			$query_w1 = "";
			$query_w2 = "";
			
			if($security->isUser())
			{
				$query_w1 = " WHERE role='user' ";
				$query_w2 = " role='user' AND ";
			}

			{
				$query = "SELECT count(iduser) as cnt FROM user WHERE $query_w2 score > (select score from user where iduser = ".$security->iduser().") ORDER BY score DESC";
				$result = $db->query( $query );
				$place = mysql_result($result, 0, 'cnt') + 1;
			}
			
			{
				$query = "SELECT count(iduser) as cnt FROM user $query_w1 ORDER BY score DESC";
				$result = $db->query( $query );
				$place .= " / ".mysql_result($result, 0, 'cnt');
			}
			return $place;
		}
	}
	//---------------------------------------------------------------------
?>
