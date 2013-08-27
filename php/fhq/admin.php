<?
	include_once "basepage.php";

	if( !isset($_SESSION['iduser']) && !isset($_SESSION['nickname']))
	{
		refreshTo("index.php");
		return;
	};

	$exp = $_SESSION['score'];

	$db = new database;
	$db->connect();


	$content = "";
	$title = "";
	$action = "";
	if(isset($_GET['action'])) $action = $_GET['action'];


	$score = $_SESSION['score'];
	$iduser = $_SESSION['iduser'];
	$userid = $_SESSION['iduser'];


	if( $action == "feedback" )
	{
		$title = "Feedback \ Messages";
		$check = "";
		$feedback = new feedback();
		
		if( $feedback->recvAnswer($db, $check, $userid) )
		{
		    if(strlen($check) > 0)
		    {
		      $content .= $check;
		    }
		    else
		    {
			refreshTo("?action=feedback");
		    };
		}
		//else
		{
		    $content .= $feedback->getList( $db, "yes", $userid );
		}
		$content .= $check;
	}
	else if( $action == "add" )
	{


	}
	else if( $action == "edit" )
	{

	}
	else
	{
		refreshTo("main.php?action=allow");
	};

	print_main_page("Free Hack Quest - ".$title, $content );
	exit;
?>
