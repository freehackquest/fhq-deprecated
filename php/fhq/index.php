<?

if (!file_exists("config/config.php")) {
	header ("Location: install/install_step01.php");
	exit;
};

include_once "config/config.php";
include_once "engine/fhq.php";

$security = new fhq_security();
		
if( isset( $_GET['exit']) )
{
	$security = new fhq_security();
	$security->logout();
	echo "OK";
	exit;
};

if($security->isLogged())
{
	refreshTo("main.php");
	return;
};




class fhq_logon
{
	function title()
	{
		return "";
	}

	function echo_head()
	{
		echo '';
	}
	
	function echo_onBodyEnd() {
		echo '';
	}
	
	function get_onloadbody() {
		return 'load_content_html(\'indexcontent\', \'pages/index/sign_in.html\');';
	}
	
	function echo_content()
	{
		$error_msg = "";
		if(isset($_SESSION['error_msg']))
		{
			$error_msg = "<br><br> <font color='#ff0000'>".$_SESSION['error_msg']."</font>";
			$_SESSION['error_msg'] = "";
		};

		echo '
			<div class="index_menu">
				<a class="index_menu" href="javascript:void(0);" onclick="load_content_html(\'indexcontent\', \'pages/index/sign_in.html\');"><img src="templates/base/images/index/signin.png"/></a>
				<a class="index_menu" href="javascript:void(0);" onclick="load_content_html(\'indexcontent\', \'pages/index/registration.html\');"><img src="templates/base/images/index/registration.png"/></a>
				<a class="index_menu" href="javascript:void(0);" onclick="load_content_html(\'indexcontent\', \'pages/index/about.html\');"><img src="templates/base/images/index/about.png"/></a>
				<a class="index_menu" href="javascript:void(0);" onclick="load_content_html(\'indexcontent\', \'pages/index/contacts.html\');"><img src="templates/base/images/index/contacts.png"/></a>
				<a class="index_menu" href="javascript:void(0);" onclick="load_content_html(\'indexcontent\', \'pages/index/restore.html\');"><img src="templates/base/images/index/restore.png"/></a>
			</div>
			<br>
			<div class="indexcontent" id="indexcontent">
				Hi man!
			</div>
			<br>
';
	}
};



if(isset($_SESSION['iduser']) && isset($_SESSION['email']))
{
	refreshTo("main.php");
};

$logon = new fhq_logon();
echo_shortpage($logon);

exit;	
?>
