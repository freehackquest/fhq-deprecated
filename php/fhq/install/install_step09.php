<?php
	if (file_exists("../config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}
	
	$current_step = 9;
	include_once("install_base.php");
	
	if (
		isset($_GET['uuid_game'])
		&& isset($_GET['title'])
		&& isset($_GET['type_game'])
	)
	{
		$uuid_game = $_GET['uuid_game'];
		$title = $_GET['title'];
		$type_game = $_GET['type_game'];
		
		$user = $config['db']['username'];
		$pass = $config['db']['userpass'];
		$dbname = $config['db']['dbname'];
		$dbhost = $config['db']['host'];

		$conn = new pdo('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', $user, $pass);
		
		
		$conn->query(
			"DELETE FROM games WHERE uuid_game='$uuid_game'"
		);
		
		
		$conn->query(
			"INSERT INTO games (
				id, uuid_game, title, logo, type_game, date_create, 
				date_start, date_stop, date_change, json_data,
				json_security_data, owner, rating) VALUES
			(
				1, '$uuid_game', '$title', 'images/mainlogo.png',
				'$type_game', '2014-04-01 10:00:00', '2014-04-01 10:00:00',
				'2014-04-01 12:00:00', '2014-03-29 00:00:00', NULL, NULL, ".$config['owner'].", NULL
			)" );
		
		include_once("install_gotonextstep.php");
	}
?>
<h1> Install (step <?php echo $current_step; ?>) </h1>

Add First Game: <br>
<form>
	<br>
	Uuid Game: <input size=50 type='text' name='uuid_game'
		value='<?php echo isset($_GET['uuid_game']) ? $_GET['uuid_game'] : '7ea13d6b-1eea-4010-b2ad-60dfd8d48b52'; ?>'/> <br>

	Title: <input type='text' name='title'
		value='<?php echo isset($_GET['title']) ? $_GET['title'] : 'My First Game'; ?>'/> <br>
	
	Type Of Game: <select name="type_game">
		<option <?php echo isset($_GET['type_game']) ? ($_GET['type_game'] == 'jeopardy' ? 'selected="selected"' : '') : 'selected="selected"'; ?> value="jeopardy">jeopardy</option>
		<option <?php echo isset($_GET['type_game']) ? ($_GET['type_game'] == 'attack-defence' ? 'selected="selected"' : '') : ''; ?> value="attack-defence">attack-defence</option>
	</select><br>

	<input type='submit' name='' value='Save & go to next step'/>
</form>
