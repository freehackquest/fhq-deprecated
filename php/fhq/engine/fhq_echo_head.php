<?

function echo_head($page)
{
	echo "
	<head>
	<title> ".$page->title()." </title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf8\">

	<link rel='stylesheet' type='text/css' href='styles/body.css' />

	<style>

		textarea.full_text
		{	
			margin: 0pt; 
			width: 300px; 
			height: 200px;
		}

	</style>


	<SCRIPT language=\"JavaScript\">
	function view_quest(idquest) 
	{
		window.showModalDialog(\"quest.php?idquest=\"+idquest, \"\", \"dialogWidth:500px;dialogHeight:500px;status:no;edge:sunken;\");
			window.location.reload(false);
	};
	</SCRIPT>
	</head>
	";
};
?>
