<?
include_once "fhq_class_security.php";
include_once "fhq_class_database.php";


include_once "fhq_base.php";
include_once "fhq_class_database.php";

class fhq_score
{
	function recalculate_score($bSec = true)
	{
		$security = new fhq_security();
		
		if(!$security->isLogged())
			return;
		
		$db = new fhq_database();
	
		// echo 1;

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
		
		/*$query = '
			select
				ifnull(sum(q.score),0) as sum_score
			from 
				quest q
			where 
				q.idquest = (
					select 
						u.idquest 
					from 
						userquest u  
					where
						u.iduser = '.$security->iduser().'
						and 
						u.stopdate <> \'0000-00-00 00:00:00\'
						and 
						u.idquest = q.idquest
				)
		';*/
		// echo $query;
		$result = $db->query( $query );
		$new_score = mysql_result($result, 0, 'sum_score');
		$_SESSION['user']['score'] = $new_score;
		
		$query = 'UPDATE user SET score = '.$new_score.' WHERE iduser = '.$security->iduser();
		$result = $db->query( $query );
		return $new_score;
	}
};


?>
