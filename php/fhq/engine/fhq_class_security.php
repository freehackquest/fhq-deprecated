<?
include_once "fhq_base.php";
include_once "fhq_database.php";

class fhq_security
{
	function login($email, $password)
	{
		// echo 1;
		
		unset($_SESSION['user']);
		
		if(!$this->isLogged())
		{
			$username = base64_encode($email);
			$pass_hash = md5($password);
		
			$db = new fhq_database();			
			$query = "select * from user where username = '$username' and password = '$pass_hash';";
			$result = $db->query($query);
			if( $db->count( $result ) == 1 )
			{
				$_SESSION['user'] = array();
				$_SESSION['user']['iduser'] = mysql_result($result, 0, 'iduser');
				$_SESSION['user']['email'] = mysql_result($result, 0, 'username');
				$_SESSION['user']['nick'] = mysql_result($result, 0, 'nick');
				$_SESSION['user']['score'] = mysql_result($result, 0, 'score');
				$_SESSION['user']['role'] = mysql_result($result, 0, 'role');
				return true;
			}
		}
		return false;
	}
	
	/*function checkUser($privateKey)
	{
		$query = "select * from whc_users where private_key='$privateKey'";
		$result = mysql_query( $query );
		//  or die("incorrect sql query");
		$rows = mysql_num_rows($result);
		return ($rows == 1);
	}
	
	function checkCurrentUser($privateKey)
	{
		if(!$this->isLogged())
			return false;
		$id = $_SESSION['user']['id'];
		
		$query = "select * from whc_users where private_key='$privateKey' and id = $id";
		$result = mysql_query( $query );
		//  or die("incorrect sql query");
		$rows = mysql_num_rows($result);
		return ($rows == 1);
	}
	*/
	
	function logout() { 
		if($this->isLogged()) { unset($_SESSION['user']); } 
	}
	function isLogged() { 
		return (isset($_SESSION['user'])); 
	}
	
	function role() { 
		return ($this->isLogged()) ? $_SESSION['user']['role'] : ''; 
	}
	function isAdmin() { 
		return ($this->isLogged() && $_SESSION['user']['score'] == 'admin' ); 
	}
	function isUser() { 
		return ($this->isLogged() && $_SESSION['user']['score'] == 'user' ); 
	}
	function isTester() { 
		return ($this->isLogged() && $_SESSION['user']['score'] == 'tester' ); 
	}
	function isGod() { 
		return ($this->isLogged() && $_SESSION['user']['score'] == 'god' ); 
	}
	function score() { 
		return ($this->isLogged() && is_numeric($_SESSION['user']['score'])) ? $_SESSION['user']['score'] : 0; 
	}
	function nick() { 
		return ($this->isLogged()) ? $_SESSION['user']['nick'] : ''; 
	}	
	function iduser() { 
		return ($this->isLogged() && is_numeric($_SESSION['user']['iduser'])) ? $_SESSION['user']['iduser'] : ''; 
	}

	function generatePrivateKey($email, $password)
	{
		return md5($password.strtoupper($email));
	}
	
	function echo_form_login()
	{
		$comeback = $_SERVER['REQUEST_URI'];
		
		if(!$this->isLogged())
		{
			echo "
			<form method='POST' action='../engine/whc_security.php?login'>
				".EMAIL.":<br>
				<input type='text' name='email' size='5' value=''/><br>
				".PASSWORD.":<br>
				<input type='password' name='password' size='5' value=''/> <br>
				<input type='hidden' name='comeback' value='$comeback'/> 
				<input type='submit' value='".LOGIN."'/><br>
			</form>";
		}	
		else
		{
			echo "
				".HELLO.", ".$_SESSION['user']['username']."!<br>
				<br>
				<a href='../account/index.php'>".MYACCOUNT."</a><br>
				<br>
				<form method='POST' action='../engine/whc_security.php?logout'>
					<input type='submit' value='".LOGOUT."'/>
					<input type='hidden' name='comeback' value='$comeback'/>
				</form>					
			";
		};
	}
}	

/*
if(isset($_GET['login']))
{
	$security = new fhq_security();
	$security->login();
	if(isset($_POST['comeback']))
		refreshTo($_POST['comeback']);
};

if(isset($_GET['logout']))
{
	$security = new whc_security();
	$security->logout();
	if(isset($_POST['comeback']))
		refreshTo($_POST['comeback']);
};
*/
?>
