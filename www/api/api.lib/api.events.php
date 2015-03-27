<?php
$curdir_events = dirname(__FILE__);
include_once ($curdir_helpers."/api.security.php");

class APIEvents {
	static function addPublicEvents($conn, $type, $message)
	{
		$stmt = $conn->prepare('
			INSERT INTO public_events VALUES(type, massage, dt) VALUES(?,?,NOW());
		');
		$stmt->execute(array($type, $message));
	}
}

