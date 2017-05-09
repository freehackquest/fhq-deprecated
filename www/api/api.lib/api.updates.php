<?php

class APIUpdates {
	static function getVersion($conn) {
		$version = 'u0000';
		$id = NULL;
		try {
			$stmt = $conn->prepare('
					SELECT MAX(id) as max FROM updates
			');
			$stmt->execute();
			if ($row = $stmt->fetch())
				$id = $row['max'];
		} catch(PDOException $e) {
			APIHelpers::error(500, $e->getMessage());
		}

		if ($id == NULL)
			return $version;

		try {
			$stmt = $conn->prepare('
					SELECT * FROM updates WHERE id = ?
			');
			$stmt->execute(array($id));
			if ($row = $stmt->fetch())
				$version = $row['version'];
		} catch(PDOException $e) {
			APIHelpers::error(500, $e->getMessage());
		}
		return $version;
	}
	
	static function insertUpdateInfo($conn, $old_version, $new_version, $name, $description, $userid) {
		try {
			$stmt = $conn->prepare('
					INSERT INTO updates(from_version, version, name, result, description, userid, datetime_update) 
					VALUES(?,?,?,?,?,?,NOW())
			');
			$stmt->execute(array($old_version, $new_version, $name, 'updated', $description, $userid));
		} catch(PDOException $e) {
			APIHelpers::error(500, $e->getMessage());
		}
	}
}
