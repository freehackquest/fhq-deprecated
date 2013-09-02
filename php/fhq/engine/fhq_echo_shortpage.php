<?
include_once "fhq_security.php";

function echo_shortpage($page)
{	
	echo '
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> fhq - '.$page->title().' </title>
<link rel="stylesheet" type="text/css" href="styles/body.css" />
</head>
<body class="main">
<center>

<table width="100%" height="100%">
	<tr>
		<td align="center" valign="middle">
			<table>				
				<tr>
					<td> <img src="images/minilogo.jpg"> </td>
					<td > <h2>free-hack-quest:<br>'.$page->title().'</h2>
					<center>
						';
	$page->echo_content();
	echo '
					</center>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

</center>

</body>
</html>';
};

?>
