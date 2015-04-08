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

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1068, 'access denie. you must be admin.');

// $gameid = APIHelpers::getParam("gameid", APIGame::id());

// todo check integer value

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

/*if ($gameid == 0)
	APIHelpers::showerror(1069, "Game was not selected.");

if (!is_numeric($gameid))
	APIHelpers::showerror(1070, 'parameter "gameid" must be numeric');*/

// $result['data']['gameid'] = $gameid;

// table
$table = APIHelpers::getParam('table', 'active');
if ($table != 'active' && $table != 'backup')
	APIHelpers::showerror(1071, 'parameter "active" must be "current" or "backup"');
$result['data']['table'] = $table;
$table = $table == 'active' ? 'tryanswer' : 'tryanswer_backup';

// page
$page = APIHelpers::getParam('page', 0);
if (!is_numeric($page))
	APIHelpers::showerror(1072, 'parameter "page" must be numeric');
$result['data']['page'] = intval($page);

// onpage
$onpage = APIHelpers::getParam('onpage', 25);
if (!is_numeric($onpage))
	APIHelpers::showerror(1073, 'parameter "onpage" must be numeric');
$result['data']['onpage'] = intval($onpage);


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
		$result['data']['count'] = intval($row['cnt']);
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
			u.nick,
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
	$result['data']['answers'] = array();
	$id = -1;
	while ($row = $stmt->fetch()) {
		$id++;
		$result['data']['answers'][] = array(
			'datetime_try' => $row['datetime_try'],
			'gameid' => $row['gameid'],
			'gametitle' => $row['title'],
			'questid' => $row['idquest'],
			'questname' => $row['name'],
			'questscore' => $row['score'],
			'questsubject' => $row['subject'],
			'questsolved' => $row['count_user_solved'],
			'userid' => $row['iduser'],
			'usernick' => $row['nick'],
			'email' => $row['email'],
			'answer_try' => htmlspecialchars($row['answer_try']),
			'answer_real' => htmlspecialchars($row['answer_real']),
			'passed' => $row['passed'],
		);
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1075, $e->getMessage());
}

// not needed here
// include_once ($curdir."/../api.lib/savetoken.php");

$result['lead_time_sec'] = microtime(true) - $statistics_list_start;
echo json_encode($result);
