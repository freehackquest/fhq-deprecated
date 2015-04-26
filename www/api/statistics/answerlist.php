<?php
/*
 * API_NAME: Answer List
 * API_DESCRIPTION: Method will be returned answer list for monitoring by users
 * API_ACCESS: admin only
 * API_INPUT: page - integer, number of page - need for pagging
 * API_INPUT: onpage - integer, records on page - need for pagging
 * API_INPUT: table - string, (active or backup)
 * API_INPUT: userid - string, filter by user id or empty
 * API_INPUT: user - string, filter by user nick or email or empty
 * API_INPUT: gameid - string, filter by game id or empty
 * API_INPUT: gamename - string, filter by gamename or empty
 * API_INPUT: questid - string, filter by questid or empty
 * API_INPUT: questname - string, filter by questname or empty
 * API_INPUT: questsubject - string, filter by questsubject or empty
 * API_INPUT: passed - string, filter by passed or empty
 */

$curdir_statistics_list = dirname(__FILE__);
include_once ($curdir_statistics_list."/../api.lib/api.base.php");
include_once ($curdir_statistics_list."/../api.lib/api.security.php");
include_once ($curdir_statistics_list."/../api.lib/api.helpers.php");
include_once ($curdir_statistics_list."/../api.lib/api.game.php");
include_once ($curdir_statistics_list."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1068, 'access denie. you must be admin.');

$response['result'] = 'ok';

// table
$table = APIHelpers::getParam('table', 'active');
if ($table != 'active' && $table != 'backup')
	APIHelpers::showerror(1071, 'parameter "table" must be "active" or "backup"');
$response['data']['table'] = $table;
$table = $table == 'active' ? 'tryanswer' : 'tryanswer_backup';

// page
$page = APIHelpers::getParam('page', 0);
if (!is_numeric($page))
	APIHelpers::showerror(1072, 'parameter "page" must be numeric');
$response['data']['page'] = intval($page);

// onpage
$onpage = APIHelpers::getParam('onpage', 10);
if (!is_numeric($onpage))
	APIHelpers::showerror(1073, 'parameter "onpage" must be numeric');
$response['data']['onpage'] = intval($onpage);


$filter_where = [];
$filter_values = [];

// userid
$userid = APIHelpers::getParam('userid', '');
if (is_numeric($userid)) {
	$filter_where[] = 'u.iduser = ?';
	$filter_values[] = intval($userid);
}

// user
$user = APIHelpers::getParam('user', '');
if ($user != '') {
	$filter_where[] = '(u.email like ? OR u.nick like ?)';
	$filter_values[] = '%'.$user.'%';
	$filter_values[] = '%'.$user.'%';
}

// gameid
$gameid = APIHelpers::getParam('gameid', '');
if (is_numeric($gameid)) {
	$filter_where[] = '(g.id = ?)';
	$filter_values[] = intval($gameid);
}

// gamename
$gamename = APIHelpers::getParam('gamename', '');
if ($gamename != '') {
	$filter_where[] = '(g.title like ?)';
	$filter_values[] = '%'.$gamename.'%';
}

// questid
$questid = APIHelpers::getParam('questid', '');
if (is_numeric($questid)) {
	$filter_where[] = '(q.idquest = ?)';
	$filter_values[] = intval($questid);
}

// questname
$questname = APIHelpers::getParam('questname', '');
if ($questname != '') {
	$filter_where[] = '(q.name like ?)';
	$filter_values[] = '%'.$questname.'%';
}

// questsubject
$questsubject = APIHelpers::getParam('questsubject', '');
if ($questsubject != '') {
	$filter_where[] = 'q.subject = ?';
	$filter_values[] = $questsubject;
}

// passed
$passed = APIHelpers::getParam('passed', '');
if ($passed != '') {
	$filter_where[] = 'ta.passed = ?';
	$filter_values[] = $passed;
}

$where = implode(' AND ', $filter_where);
if ($where != '') {
	$where = ' WHERE '.$where;
}

$conn = APIHelpers::createConnection($config);

// count quests
try {
	$stmt = $conn->prepare('
		SELECT 
			count(*) as cnt
		FROM 
			'.$table.' ta
		INNER JOIN users u ON u.id = ta.iduser
		INNER JOIN quest q ON q.idquest = ta.idquest
		INNER JOIN games g ON g.id = q.gameid
		'.$where.'
	');
	$stmt->execute($filter_values);
	if($row = $stmt->fetch()) {
		$response['data']['count'] = intval($row['cnt']);
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1074, $e->getMessage());
}

try {
	$stmt = $conn->prepare('
		SELECT 
			ta.datetime_try,
			ta.passed,
			ta.idquest,
			ta.iduser,
			ta.answer_try,
			ta.answer_real,
			ta.levenshtein,
			u.nick,
			u.logo,
			u.email,
			q.name,
			q.subject,
			q.score,
			q.count_user_solved,
			q.gameid,
			g.title
		FROM 
			'.$table.' ta
		INNER JOIN users u ON u.id = ta.iduser
		INNER JOIN quest q ON q.idquest = ta.idquest
		INNER JOIN games g ON g.id = q.gameid
		'.$where.'
		ORDER BY 
			datetime_try DESC
		LIMIT '.($page*$onpage).','.$onpage.'
	');
	$stmt->execute($filter_values);
	$response['data']['answers'] = array();
	$id = -1;
	while ($row = $stmt->fetch()) {
		$id++;
		$response['data']['answers'][] = array(
			'dt' => $row['datetime_try'],
			'answer_try' => htmlspecialchars($row['answer_try']),
			'answer_real' => htmlspecialchars($row['answer_real']),
			'levenshtein' => $row['levenshtein'],
			'passed' => $row['passed'],
			'game' => array(
				'id' => $row['gameid'],
				'title' => $row['title'],
			),
			'quest' => array(
				'id' => $row['idquest'],
				'name' => $row['name'],
				'score' => $row['score'],
				'subject' => $row['subject'],
				'solved' => $row['count_user_solved'],
			),
			'user' => array(
				'id' => $row['iduser'],
				'logo' => $row['logo'],
				'nick' => $row['nick'],
				'email' => $row['email'],
			),
		);
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1075, $e->getMessage());
}

APIHelpers::endpage($response);
