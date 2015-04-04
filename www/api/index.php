<?

$curdir = dirname(__FILE__);

$doc = array();

include_once $curdir."/tex.php";

$doc['security'] = array(
	'name' => 'Security',
	'description' => 'Methods for login, logout, registration and restore password.',
	'methods' => array(),
);

$doc['security']['methods'][] = json_decode(file_get_contents('security/login.json'), true);
$doc['security']['methods'][] = json_decode(file_get_contents('security/logout.json'), true);
$doc['security']['methods'][] = json_decode(file_get_contents('security/registration.json'), true);
$doc['security']['methods'][] = json_decode(file_get_contents('security/restore.json'), true);

// include_once $curdir."/security/index.php";
/*include_once $curdir."/users/index.php";
include_once $curdir."/games/index.php";
include_once $curdir."/quests/index.php";
include_once $curdir."/updates/index.php";
include_once $curdir."/events/index.php";
*/

print_doc($doc);
