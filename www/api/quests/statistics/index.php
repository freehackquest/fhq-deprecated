<?php
/*
 * API_NAME: Statistics List
 * API_DESCRIPTION: Method will be returned statistics info
 * API_ACCESS: all
 * API_INPUT: questid - integer, quest id
 */

$curdir_statistics_list = dirname(__FILE__);
include_once ($curdir_statistics_list."/../../api.lib/api.base.php");
include_once ($curdir_statistics_list."/../../api.lib/api.security.php");
include_once ($curdir_statistics_list."/../../api.lib/api.helpers.php");
include_once ($curdir_statistics_list."/../../../config/config.php");

$response = APIHelpers::startpage($config);

$response['result'] = 'ok';

$filter_where = array();
$filter_values = array();

// questid
$questid = APIHelpers::getParam('questid', '');
if ($questid != '' && is_numeric($questid)) {
	$filter_where[] = '(idquest = ?)';
	$filter_values[] = intval($questid);
} else if ($questid != '' && !is_numeric($questid)) {
	APIHelpers::showerror(1286, 'Parameter "questid" must be numeric or empty');
}

if (!APISecurity::isAdmin()) {
	$filter_where[] = '(state = ?)';
	$filter_values[] = 'open';
}

$where = implode(' AND ', $filter_where);

$conn = APIHelpers::createConnection($config);

function getCountStatBy($conn, $table, $questid, $passed){
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
				idquest
			FROM 
				quest
			WHERE
				'.$where.'
	');
	$stmt->execute($filter_values);
	
	if ($row = $stmt->fetch()) {
		$questid = $row['idquest'];
		$response['data']['id'] = $questid;
		// subquesry
		// users how solved this quest
		$tries_nosolved = getCountStatBy($conn, 'tryanswer', $questid, 'No');
		$solved = getCountStatBy($conn, 'tryanswer_backup', $questid, 'Yes');
		$tries_solved = getCountStatBy($conn, 'tryanswer_backup', $questid, 'No');

		$response['data']['solved'] = $solved;
		$response['data']['tries_nosolved'] = $tries_nosolved;
		$response['data']['tries_solved'] = $tries_solved;
		$response['data']['users'] = array();

		// how solved this quest
		$stmt_users = $conn->prepare('
			select 
				t0.id, 
				t0.logo,
				t0.nick
			from 
				users t0
			inner join users_quests t1 on t0.id = t1.userid
			where
				t0.role = ?
				and t1.questid = ?
		');
		$stmt_users->execute(array('user',intval($questid)));
	
		while ($row_user = $stmt_users->fetch()) {
			$response['data']['users'][] = array(
				'userid' => $row_user['id'],
				'logo' => $row_user['logo'],
				'nick' => $row_user['nick'],
			);
		}
	}else{
		$response['msg'] = 'not_found';
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1102, $e->getMessage());
}

APIHelpers::endpage($response);


