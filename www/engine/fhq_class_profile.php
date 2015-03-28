<?php
class fhq_profile
{
	private $quest_name, $full_text, $score, $min_score, $subject, $answer, $reply_answer, $idquest, $for_person;
	private $fields;

	function get_user_profile($user_id = 0)
	{
		$db = new fhq_database();
		$user_id = intval($user_id);

		$query = 'SELECT nick from user WHERE iduser="'.$user_id.'"';
		$result = $db->query($query);

		if (!$result)
		{
			echo "Users doesn't found";
			exit();
		}
		else
		{
			echo '<pre>';
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$nick = htmlspecialchars($row['nick']);
			echo 'Username: <a href="javascript:void(0);" onclick="load_content_page(\'profile\',{user_id:\''.$user_id.'\'});">'.$nick.'</a><br>';

			$query = 'SELECT userquest.idquest, quest.tema, quest.name, quest.score FROM userquest LEFT JOIN quest ON quest.idquest=userquest.idquest WHERE iduser="'.$user_id.'"';
			$result = $db->query($query);

			echo '<hr> <h4>Solved quests:</h4><br>';
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				echo htmlspecialchars(base64_decode($row['name'])) .
					' ( ' . htmlspecialchars(base64_decode($row['tema'])).' + '
					. $row['score'] . ')' . '<br>';
			}
			echo '</pre>';
		}
		
		return;
	}
};
?>
