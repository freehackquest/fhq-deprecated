<?php
$curdir_events = dirname(__FILE__);
include_once ($curdir_events."/api.security.php");

class APIEvents {
	static function addPublicEvents($conn, $type, $message)
	{
		$stmt = $conn->prepare('
			INSERT INTO public_events(type, message, dt) VALUES(?,?,NOW());
		');
		$stmt->execute(array($type, $message));
	}
}

