<?
$curdir = dirname(__FILE__);

include_once "$curdir/fhq_class_security.php";

function echo_shortpage($page)
{	
	echo '
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title> Free-Hack-Quest </title>
		<link rel="stylesheet" type="text/css" href="templates/base/styles/site.css" />
		<script type="text/javascript" src="js/index.js"></script>
	';

$page->echo_head();

echo '
		<link rel="stylesheet" type="text/css" href="templates/base/styles/body.css" />
	</head>';
	
if (method_exists($page, 'get_onloadbody')) {
	echo '<body class="main" onload="'.$page->get_onloadbody().'">';
} else {
	echo '<body class="main">';
}

echo ' <center>
			<table width="100%" height="100%">
				<tr>
					<td align="center" valign="middle">
						<table>				
							<tr>
								<td><center><br>
								<img src="templates/base/images/mainlogo.png"/></center></td>
							</tr>
							<tr>
								<td > 
								<center>
									'.$page->title().'
									';
				$page->echo_content();
				echo '
								</center>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>';
				
				include('copyright.php');
					
		echo '	
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
