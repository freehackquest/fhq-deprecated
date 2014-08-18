<?
	if (file_exists("../config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}

	if (!file_exists("config.php")) {
		file_put_contents('config.php', '<? $config = array( installation => array()); ?>');
		if (!file_exists('config.php')) {
			echo 'you have access problem with create files';
			exit;
		}
	}
	
	include_once('config.php');
	
	if (isset($config['installation']['step1'])) {
		header ("Location: install_step2.php");
		exit;
	}
	
	if( 
		isset($_GET['host_db'])
		&& isset($_GET['name_db'])
		&& isset($_GET['user_db'])
		&& isset($_GET['pass_db'])
	)
	{
		$user = $_GET['user_db'];
		$pass = $_GET['pass_db'];
		$dbname = $_GET['name_db'];
		$dbhost = $_GET['host_db'];
		
		
		$bCorrect = true;
		try{
			$dbh = new pdo('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8',
							$user,
							$pass,
							array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			$bCorrect = true;
		}
		catch(PDOException $ex){
			echo '<font color="FF0000">Not correct connection, try again</font>';
			$bCorrect = false;
		}
		
		if ($bCorrect) {
			// echo "saved to file: test.php";
			// include_once('config.php');
			
			$config['db'] = array (
				'host' => $dbhost, 
				'username' => $user, 
				'userpass' => $pass,
				'dbname' => $dbname
			);

			$config['installation']['step1'] = 'ok';

			file_put_contents('config.php', '<? $config = '.var_export($config, true).'; ?>');
			header ("Location: install_step2.php");
			exit;
		}
	}
?>
<h1> Install (step1) </h1>

Configure connection to database: <br>
<form>
	Database Host: <input type='text' name='host_db'
		value='<? echo isset($_GET['host_db']) ? $_GET['host_db'] : ''; ?>'/> <br>
		
	Database Name: <input type='text' name='name_db' 
		value='<? echo isset($_GET['name_db']) ? $_GET['name_db'] : ''; ?>'/> <br>
		
	Database User: <input type='text' name='user_db' 
		value='<? echo isset($_GET['user_db']) ? $_GET['user_db'] : ''; ?>'/> <br>
		
	Database Pass: <input type='text' name='pass_db'
		value='<? echo isset($_GET['pass_db']) ? $_GET['pass_db'] : ''; ?>'/> <br>

	<input type='submit' name='' value='Check & go to next step'/>
</form>
