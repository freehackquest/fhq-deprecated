<?php
/*
 * API_NAME: Quest List
 * API_DESCRIPTION: Method will be returned quest list
 * API_ACCESS: authorized users
 * API_INPUT: token - string, token
 * API_INPUT: open - boolean, filter by open quests (it not taked)
 * API_INPUT: completed - boolean, filter by completed quest (finished quests)
 * API_INPUT: subjects - string, filter by subjects quests (for example: "hashes,trivia" and etc. also look types)
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../../../api.lib/api.base.php");
include_once ($curdir."/../../../api.lib/api.helpers.php");

$response = APIHelpers::startpage();

if(!APIHelpers::is_json_input()){
	APIHelpers::showerror2(2001, 400, "Expected application/json on input POST");
}

$conn = APIHelpers::createConnection();

$request = APIHelpers::read_json_input();

$response['result'] = 'ok';
$response['data'] = array();

// calculate count summary
try {
	$stmt = $conn->prepare('SELECT subject, COUNT(*) as cnt FROM `quest` WHERE quest.state = "open" GROUP BY subject');
	$stmt->execute();
	while($row = $stmt->fetch()){
		$response['data'][] = array(
			"subject" => $row['subject'],
			"count" => $row['cnt']
		);
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1096, $e->getMessage());
}

APIHelpers::endpage($response);

