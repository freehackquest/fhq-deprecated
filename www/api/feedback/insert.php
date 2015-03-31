<?php
$curdir_feedback_insert = dirname(__FILE__);
include_once ($curdir_feedback_insert."/../api.lib/api.helpers.php");
include_once ($curdir_feedback_insert."/../../config/config.php");
include_once ($curdir_feedback_insert."/../api.lib/api.base.php");

APIHelpers::startpage($config);

// include_once ($curdir_events_insert."/../api.lib/loadtoken.php");
APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if (!APIHelpers::issetParam('type'))
  APIHelpers::showerror(1237, 'not found parameter type');

if (!APIHelpers::issetParam('text'))
  APIHelpers::showerror(1242, 'not found parameter text');

$type = APIHelpers::getParam('type', 'complaint');
$text = APIHelpers::getParam('text', '');

if (strlen($text) <= 3)
  APIHelpers::showerror(1239, 'text must be informative! (more than 3 character)');

$conn = APIHelpers::createConnection($config);

try {
	$stmt = $conn->prepare('INSERT INTO feedback(typeFB, full_text, author, dt) VALUES(?,?,?,NOW());');
	if($stmt->execute(array($type, $text, APISecurity::userid()))) {
		$result['data']['feedback']['id'] = $conn->lastInsertId();
		$result['result'] = 'ok';
	} else {
		APIHelpers::showerror(1240,'Could not insert. PDO: '.$conn->errorInfo());
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1241,$e->getMessage());
}

echo json_encode($result);
