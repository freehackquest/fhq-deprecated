<?
$curdir = dirname(__FILE__);

include_once "$curdir/fhq_class_security.php";
include_once "$curdir/fhq_class_database.php";
include_once "$curdir/fhq_base.php";
include_once "$curdir/fhq_class_database.php";

class fhq_score
{
	function recalculate_score($bSec = true)
	{
		$security = new fhq_security();
		if(!$security->isLogged())
			return;
		
		$db = new fhq_database();
		if($bSec && isset($_SESSION['recalculate_score_last_time']))
		{
				$oldtime = $_SESSION['recalculate_score_last_time'];
				$secs = time() - $oldtime;
				if($secs < 60) 
					return 'wait '.(60 - $secs).' seconds';
		}
		
		$_SESSION['recalculate_score_last_time'] = time();
		
		$query = '
			SELECT 
				ifnull(SUM(quest.score),0) as sum_score 
			FROM 
				userquest 
			INNER JOIN 
				quest ON quest.idquest = userquest.idquest 
			WHERE 
				(userquest.iduser = '.$security->iduser().') 
				AND ( userquest.stopdate <> \'0000-00-00 00:00:00\' );
		';

		$result = $db->query( $query );
		$new_score = mysql_result($result, 0, 'sum_score');
		$_SESSION['user']['score'] = $new_score;
		
		$idgame = 0;
		if (isset($_SESSION['game']))
			$idgame = $_SESSION['game']['id'];

		$this->update_score('Quests', $idgame, $security->iduser(), $new_score);
		
		$query = 'UPDATE user SET score = '.$new_score.' WHERE iduser = '.$security->iduser();
		$result = $db->query( $query );
		return $new_score;
	}
	
	function update_sum_score($idgame, $owner) {
		$security = new fhq_security();
		$db = new fhq_database();
		
		$query = 'select ifnull(sum(score),0) as sm from scoreboard where name <> "Summary" and idgame = '.$idgame.' and owner = '.$owner.';';
		$result = $db->query($query);
		$row = mysql_fetch_array( $result, MYSQL_ASSOC);
		$summ = $row['sm'];
		mysql_free_result($result);
		$this->update_score('Summary', $idgame, $owner, $summ);
	}
	
	function update_score($name, $idgame, $owner, $score) {
		$security = new fhq_security();
		$db = new fhq_database();
		
		$query = 'select count(*) as cnt from scoreboard where name = "'.$name.'" and idgame = '.$idgame.' and owner = '.$owner.';';
		$result = $db->query($query);
		$row = mysql_fetch_array( $result, MYSQL_ASSOC);
		if ($row['cnt'] == 0) {
			$query = "insert into scoreboard(idgame, name, owner, score, date_change) values($idgame,'".mysql_real_escape_string($name)."',$owner,$score,NOW());";
			$db->query($query);
		} else {
			$query = "update scoreboard set score = $score, date_change = NOW() where idgame = $idgame and name = '".mysql_real_escape_string($name)."' and owner = $owner;";
			$db->query($query);
		}
		if ($name != 'Summary')
			$this->update_sum_score($idgame, $owner);
			
		mysql_free_result($result);
	}
	
	function getScores($idgame, $owner) {
		$db_scores = array();
		$db = new fhq_database();
		$query = 'select * from scoreboard where idgame = '.$idgame.' and owner = '.$owner.';';
		$result = $db->query($query);
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC)) {
			$db_scores[$row['name']] = $row['score'];
		}
		mysql_free_result($result);
		return $db_scores;
	}
	
	function init_scoreboard() {
		$db = new fhq_database();
		
		$idgame = 0;
		$type_game = 0;
		if (isset($_SESSION['game'])) {
			$idgame = $_SESSION['game']['id'];
			$type_game = $_SESSION['game']['type_game'];
		}

		$result = $db->query("SELECT iduser FROM user");
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC)) {
			$owner = $row['iduser'];
			
			if ($type_game == 'jeopardy') {
				// in feature must be deprecated
				$query = '
				SELECT 
					ifnull(SUM(quest.score),0) as sum_score 
				FROM 
					userquest 
				INNER JOIN 
					quest ON quest.idquest = userquest.idquest 
				WHERE 
					(userquest.iduser = '.$owner.') 
					AND ( userquest.stopdate <> \'0000-00-00 00:00:00\' );
				';
				$result2 = $db->query($query);
				$new_score = mysql_result($result2, 0, 'sum_score');
				mysql_free_result($result2);
				$this->update_score('Quests', $idgame, $owner, $new_score);
			}
			else 
			{
				$result2 = $db->query("SELECT name FROM services WHERE idgame = $idgame");
				while($row2 = mysql_fetch_array( $result2, MYSQL_ASSOC)) {
					$name = $row2["name"];
					$this->update_score($name, $idgame, $owner, 0);
				}
				$this->update_score("Defence", $idgame, $owner, 0);
				$db->query("update scoreboard set date_change = NOW(), score = (select count(*) from flags where owner = $owner and user_passed = 0)  where owner = $owner and name = 'Defence' and idgame = $idgame;");
				
				$this->update_score("Offence", $idgame, $owner, 0);
				$db->query("update scoreboard set date_change = NOW(), score = (select count(*) from flags where owner <> $owner and user_passed = $owner)  where owner = $owner and name = 'Offence' and idgame = $idgame;");
				
				$this->update_score("Advisers", $idgame, $owner, 0);
				$db->query("update scoreboard set date_change = NOW(), score = (select ifnull(SUM(mark),0) as sm from advisers where idgame = $idgame and owner = $owner)  where owner = $owner and name = 'Advisers' and idgame = $idgame;");
				
				mysql_free_result($result2);
				$this->update_sum_score($idgame, $owner);
			}
		}
		mysql_free_result($result);
		echo "updated!";
	}
	
	function echo_scoreboard($view_all = false) {
		$security = new fhq_security();
		$db = new fhq_database();
		
		$columns = "";
		
		$game_type = "";
		$idgame = 0;
		if (isset($_SESSION['game']))
			$game_type = $_SESSION['game']['type_game'];

		if (isset($_SESSION['game']))
			$idgame = $_SESSION['game']['id'];
		
		$result = $db->query("SELECT * FROM services WHERE idgame = ".$idgame);
		$services = array();
		$i = 0;
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC)) {
			$services[$i++] = $row;
			$columns .= '<th><h2>'.$row['name'].'</h2></th>';
		}
		mysql_free_result($result);
		
		$scores = array();
		$i = 0;
			
		if ($game_type == 'jeopardy') {
			$scores[$i++] = 'Quests';
		}
		
		if ($game_type == 'attack-defence') {
			$scores[$i++] = 'Defence';
			$scores[$i++] = 'Offence';
			$scores[$i++] = 'Advisers';
			$scores[$i++] = 'Summary';
		}
		
		foreach($scores as $key => $value) {
			if ($value != 'Quests')
				$columns .= '<th><h2>'.$value.'</h2></th>';
			else
				$columns .= '<th><h2>Score</h2></th>';
		}

		echo '<center>Scoreboard</center>
			<br>
			<table cellspacing=2 cellpadding=10 class="alt" id="customers">
				<tr class="alt">
					<td><h2>Place</h2></td>
					<td width="100"><h2>User</h2></td>
					'.$columns.'
				</tr>
		';

		$role = 'and role = "user"';
		
		if (($security->isAdmin() || $security->isTester()) && !$view_all)
			$role = '';

		$query = 'SELECT 
				*, scoreboard.score as score2
			FROM `scoreboard` 
			INNER JOIN user ON scoreboard.owner = user.iduser
			WHERE
				idgame = '.$idgame.' 
				and name = "Summary"
				'.$role.'
			ORDER BY score2 DESC
		';
		// echo $query;
		$result = $db->query($query);
		$bClass = false;
		$i = 0;
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC)) {
			$owner = $row['iduser'];
			$logo = $row['logo'];
			$nick = $row['nick'];
			$owner = $row['owner'];
			
			$score2 = $row['score2'];
			if ($logo != "") $logo = '<img width=110px src="'.$logo.'"/>';
			
			$strclass = '';
			if ($bClass)
				$strclass = " class='alt' ";
			$bClass = !$bClass;

			$db_scores = $this->getScores($idgame, $owner);
			
			$rows = '';
			foreach($services as $key => $value) {
				$status = (isset($db_scores[$value['name']]) ? $db_scores[$value['name']] : 0);
				$color = '#000000';
				if ($status == 0) {
					$status = '<img src="images/corrupt.png"/>';
					$color = '#FF0000';
				}
				if ($status == 1)
					$status = '<img src="images/work.png" />';
				
				$rows .= '<td><font size=3 color='.$color.'>'.$status.'</font></td>';
			}
			foreach($scores as $key => $value) {
				$rows .= '<td><h1>'.(isset($db_scores[$value]) ? $db_scores[$value] : 0).'</h1></td>';
			}

			echo '
			<tr '.$strclass.'>
				<td width=50px><h1>'.(++$i).'</h1></td>
				<td><center>'.$logo.'<h4>
				  <a href="javascript:void(0);" onclick="load_content_page(\'profile\',{user_id:\''.$owner.'\'});">'.htmlspecialchars($nick).'</a></h4></center></td>
				'.$rows.'
			</tr>';
		}
		// echo 'Username: <br>';
		echo '</table>';
	}
};


?>
