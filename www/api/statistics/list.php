<?php
/*
 * API_NAME: Statistics List
 * API_DESCRIPTION: Method will be returned statistics info
 * API_ACCESS: authorized users
 * API_INPUT: token - guid, token
 * API_INPUT: page - integer, number of page - need for pagging
 * API_INPUT: onpage - integer, records on page - need for pagging
 * API_INPUT: questid - integer, quest id
 * API_INPUT: questname - string, search by name or substring from quest name
 * API_INPUT: questsubject - string, look in types
 */

$curdir_statistics_list = dirname(__FILE__);
include_once ($curdir_statistics_list."/../api.lib/api.base.php");
include_once ($curdir_statistics_list."/../api.lib/api.security.php");
include_once ($curdir_statistics_list."/../api.lib/api.helpers.php");
include_once ($curdir_statistics_list."/../api.lib/api.game.php");
include_once ($curdir_statistics_list."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

if (!APIHelpers::issetParam('gameid'))
	APIHelpers::showerror(1330, 'Parameter "gameid" does not found');
	
$gameid = APIHelpers::getParam('gameid', 0);

if (!is_numeric($gameid))
	APIHelpers::showerror(1077, 'Parameter "gameid" must be numeric');

if ($gameid == 0)
	APIHelpers::showerror(1076, "Parameter gameid must be not 0.");

$response['result'] = 'ok';

$filter_where = array();
$filter_values = array();

$filter_values[] = 0;
$filter_values[] = intval($gameid);

// page
$page = APIHelpers::getParam('page', 0);
if (!is_numeric($page))
	APIHelpers::showerror(1284, 'Parameter "page" must be numeric');
$response['data']['page'] = intval($page);

// onpage
$onpage = APIHelpers::getParam('onpage', 25);
if (!is_numeric($onpage))
	APIHelpers::showerror(1285, 'parameter "onpage" must be numeric');
$response['data']['onpage'] = intval($onpage);

// questid
$questid = APIHelpers::getParam('questid', '');
if ($questid != '' && is_numeric($questid)) {
	$filter_where[] = '(idquest = ?)';
	$filter_values[] = intval($questid);
} else if ($questid != '' && !is_numeric($questid)) {
	APIHelpers::showerror(1286, 'Parameter "questid" must be numeric or empty');
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

$response['data']['gameid'] = $gameid;

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
		$response['data']['count'] = $row['cnt'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1078, $e->getMessage());
}


function getCountStatBy($conn, $table, $questid, $passed)
{
	$res = 0;
	try {
		$stmt = $conn->prepare('
				select 
					count(t0.id) as cnt 
				from 
					'.$table.' t0
				inner join users t1 on t1.id = t0.iduser
				where 
					t0.idquest = ?
					and t0.passed = ?
					and t1.role = ?
		');
		$stmt->execute(array(intval($questid), $passed, 'user'));
		if($row = $stmt->fetch()) {
			$res = $row['cnt'];
		}
	} catch(PDOException $e) {
		APIHelpers::showerror(1079, $e->getMessage());
	}
	return $res;
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
	$response['data']['quests'] = array();
	$id = -1;
	while ($row = $stmt->fetch()) {
		$id++;
		$questid = $row['idquest'];
		$response['data']['quests'][$id] = array(
			'id' => $row['idquest'],
			'name' => $row['name'],
			'subject' => $row['subject'],
			'min_score' => $row['min_score'],
			'score' => $row['score'],
		);
		// subquesry
		// users how solved this quest
		$tries_nosolved = getCountStatBy($conn, 'tryanswer', $questid, 'No');
		$solved = getCountStatBy($conn, 'tryanswer_backup', $questid, 'Yes');
		$tries_solved = getCountStatBy($conn, 'tryanswer_backup', $questid, 'No');

		$response['data']['quests'][$id]['solved'] = $solved;
		$response['data']['quests'][$id]['tries_nosolved'] = $tries_nosolved;
		$response['data']['quests'][$id]['tries_solved'] = $tries_solved;
		$response['data']['quests'][$id]['users'] = array();

		// how solved this quest
		$stmt_users = $conn->prepare('
			select 
				t0.id, 
				t0.logo,
				t0.nick
			from 
				users t0
			inner join userquest t1 on t0.id = t1.iduser 
			where
				t0.role = ?
				and t1.idquest = ?
				and t1.stopdate <> ?
		');
		$stmt_users->execute(array('user',intval($questid), '0000-00-00 00:00:00'));
	
		while ($row_user = $stmt_users->fetch()) {
			$response['data']['quests'][$id]['users'][] = array(
				'userid' => $row_user['id'],
				'logo' => $row_user['logo'],
				'nick' => $row_user['nick'],
			);
		}
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1102, $e->getMessage());
}

APIHelpers::endpage($response);


