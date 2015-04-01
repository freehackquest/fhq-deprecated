<?php
$statistics_list_start = microtime(true);
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir_statistics_list = dirname(__FILE__);
include_once ($curdir_statistics_list."/../api.lib/api.base.php");
include_once ($curdir_statistics_list."/../api.lib/api.security.php");
include_once ($curdir_statistics_list."/../api.lib/api.helpers.php");
include_once ($curdir_statistics_list."/../api.lib/api.game.php");
include_once ($curdir_statistics_list."/../../config/config.php");

include_once ($curdir_statistics_list."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$gameid = APIHelpers::getParam("gameid", 0);

if (!is_numeric($gameid))
	APIHelpers::showerror(1077, 'parameter "gameid" must be numeric');

if ($gameid == 0)
	APIHelpers::showerror(1076, "Game was not selected.");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

$filter_where = [];
$filter_values = [];

$filter_values[] = 0;
$filter_values[] = intval($gameid);

// page
$page = APIHelpers::getParam('page', 0);
if (!is_numeric($page))
	APIHelpers::showerror(1072, 'Parameter "page" must be numeric');
$result['data']['page'] = intval($page);

// onpage
$onpage = APIHelpers::getParam('onpage', 25);
if (!is_numeric($onpage))
	APIHelpers::showerror(1073, 'parameter "onpage" must be numeric');
$result['data']['onpage'] = intval($onpage);

// questid
$questid = APIHelpers::getParam('questid', '');
if ($questid != '' && is_numeric($questid)) {
	$filter_where[] = '(idquest = ?)';
	$filter_values[] = intval($questid);
} else if ($questid != '' && !is_numeric($questid)) {
	APIHelpers::showerror(1073, 'Parameter "questid" must be numeric or empty');
}

// questname
$questname = APIHelpers::getParam('questname', '');
if ($questname != '') {
	$filter_where[] = '(name like ?)';
	$filter_values[] = '%'.$questname.'%';
}

// questsubject
$questsubject = APIHelpers::getParam('questsubject', '');
if ($questsubject != '') {
	$filter_where[] = 'subject = ?';
	$filter_values[] = $questsubject;
}

if (!APISecurity::isAdmin()) {
	$filter_where[] = 'state = ?';
	$filter_values[] = 'open';
}

$where = implode(' AND ', $filter_where);
if ($where != '') {
	$where = ' AND '.$where;
}

$conn = APIHelpers::createConnection($config);

$result['data']['gameid'] = $gameid;

// count quests
try {
	$stmt = $conn->prepare('
			SELECT
				count(*) as cnt
			FROM 
				quest
			WHERE
				for_person = ?
				AND gameid = ?
				'.$where.'
	');
	$stmt->execute($filter_values);
	if($row = $stmt->fetch()) {
		$result['data']['count'] = $row['cnt'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1078, $e->getMessage());
}


function getCountStatBy($conn, $table, $questid, $passed)
{
	$result = 0;
	try {
		$stmt = $conn->prepare('
				select 
					count(id) as cnt 
				from 
					'.$table.' t0
				inner join user t1 on t0.iduser = t1.iduser
				where 
					t0.idquest = ?
					and t0.passed = ?
					and t1.role = ?
		');
		$stmt->execute(array(intval($questid), $passed, 'user'));
		if($row = $stmt->fetch()) {
			$result = $row['cnt'];
		}
	} catch(PDOException $e) {
		APIHelpers::showerror(1079, $e->getMessage());
	}
	return $result;
}


try {
	$stmt = $conn->prepare('
			SELECT
				idquest, 
				name,
				subject,
				min_score,
				score
			FROM 
				quest
			WHERE
				for_person = ?
				AND gameid = ?
				'.$where.'
			ORDER BY
				subject, score ASC, min_score
			LIMIT '.($page*$onpage).','.$onpage.'
	');
	$stmt->execute($filter_values);
	$result['data']['quests'] = array();
	$id = -1;
	while ($row = $stmt->fetch()) {
		$id++;
		$questid = $row['idquest'];
		$result['data']['quests'][$id] = array(
			'id' => $row['idquest'],
			'name' => $row['name'],
			'subject' => $row['subject'],
			'min_score' => $row['min_score'],
			'score' => $row['score'],
		);
		// subquesry
		// users how solved this quest
		
		$solved = 0;
		$tries = 0;
		
		$solved += getCountStatBy($conn, 'tryanswer', $questid, 'Yes');
		$tries += getCountStatBy($conn, 'tryanswer', $questid, 'No');
		$solved += getCountStatBy($conn, 'tryanswer_backup', $questid, 'Yes');
		$tries += getCountStatBy($conn, 'tryanswer_backup', $questid, 'No');

		$result['data']['quests'][$id]['solved'] = $solved;
		$result['data']['quests'][$id]['tries'] = $tries;
		$result['data']['quests'][$id]['users'] = array();
		// how solved this quest
		
		$stmt_users = $conn->prepare('
				select 
				t0.iduser, 
				t0.nick, 
				t0.username 
			from 
				user t0
			inner join userquest t1 on t0.iduser = t1.iduser 
			where
				t0.role = ?
				and t1.idquest = ?
				and t1.stopdate <> ?
		');
		$stmt_users->execute(array('user',intval($questid), '0000-00-00 00:00:00'));
	
		while ($row_user = $stmt_users->fetch()) {
			$result['data']['quests'][$id]['users'][] = array(
				'userid' => $row_user['iduser'],
				'nick' => $row_user['nick'],
			);
		}
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1102, $e->getMessage());
}

// not needed here
// include_once ($curdir."/../api.lib/savetoken.php");

$result['lead_time_sec'] = microtime(true) - $statistics_list_start;
echo json_encode($result);
