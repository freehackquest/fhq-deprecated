<?php
$curdir = dirname(__FILE__);
include ($curdir."/../api.lib/api.helpers.php");
include ($curdir."/../../config/config.php");
include ($curdir."/../../engine/fhq.php");

$security = new fhq_security();
checkAuth($security);

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = FHQHelpers::createConnection($config);

if($security->isAdmin())
  showerror(886, 'Error 886: access denie. you must be admin.');

if (issetParam('id'))
  showerror(889, 'Error 889: not found parameter "id"');

$user_id = getParam('id', 0);

if (!is_numeric($user_id))
  showerror(885, 'Error 885: incorrect id');
		
$query = 'DELETE FROM user WHERE iduser = ?';

try {
 	$stmt = $conn->prepare($query);
 	$stmt->execute(array(intval($user_id)));
 	$result['result'] = 'ok';
} catch(PDOException $e) {
 	showerror(882, 'Error 882: ' + $e->getMessage());
}

echo json_encode($result);
