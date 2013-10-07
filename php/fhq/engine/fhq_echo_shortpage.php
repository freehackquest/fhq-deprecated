<?
include_once "fhq_class_security.php";

function echo_shortpage($page)
{	
	echo '
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title> Free-Hack-Quest </title>
		<link rel="stylesheet" type="text/css" href="styles/site.css" />
	';

$page->echo_head();

echo '
		<link rel="stylesheet" type="text/css" href="styles/body.css" />
	</head>
	<body class="main">
		<center>
			<table width="100%" height="100%">
				<tr>
					<td align="center" valign="middle">
						<table>				
							<tr>
								<td> <img src="images/logo2.png"> </td>
								<td > <font size=5>free-hack-quest:</font><br>'.$page->title().'
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
		';
		$page->echo_onBodyEnd();
echo '
	</body>
</html>';
};

?>
