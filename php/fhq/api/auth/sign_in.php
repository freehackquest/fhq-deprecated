<?php
$curdir = dirname(__FILE__);
include ($curdir."/../api.lib/api.helpers.php");
include ($curdir."/../../config/config.php");
include ($curdir."/../../engine/fhq.php");

$security = new fhq_security();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if (isset($_GET['email']) && isset($_GET['password'])) {
	$email = $_GET['email'];
	$password = $_GET['password'];

	if( $security->login($_GET['email'], $_GET['password']) ) {
		$result['result'] = 'ok';

    $conn = createConnection($config);

    /*
     TODO: 
    try {
      $stmt = $conn->prepare("INSERT INTO last_sign_in(date)");
  		$stmt->execute(array(intval($game_id)));
    } catch(PDOException $e) {
      showerror(103, 'Error 103: ' + $e->getMessage());
    }
    */

	} else {
		$result['error']['code'] = '102';
		$result['error']['message'] = 'Error 102: it was not found login or password';
	}
} else {
	$result['error']['code'] = '101';
	$result['error']['message'] = 'Error 101: it was not found login or password';
}

echo json_encode($result);
