<?

$curdir = dirname(__FILE__);

$doc = array();

$doc['captcha'] = array(
  'name' => 'Captcha',
	'description' => 'For some methods you need set captcha. Please use this url for get captcha-image.',
  'uri' => 'captcha.php',
	'methods' => array(),  
);

include_once $curdir."/tex.php";
include_once $curdir."/security/index.php";
include_once $curdir."/users/index.php";
include_once $curdir."/games/index.php";
include_once $curdir."/quests/index.php";
include_once $curdir."/updates/index.php";
include_once $curdir."/events/index.php";

print_doc($doc);
