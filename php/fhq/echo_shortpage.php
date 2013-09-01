<?

function echo_shortpage($page)
{	
	echo '
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> free-hack-quest - Registration </title>
<link rel="stylesheet" type="text/css" href="styles/style.css" />
</head>
<body class="main">
<center>

<table width="100%" height="100%">
	<tr>
		<td align="center" valign="middle">
			<table>				
				<tr>
					<td> <img src="images/minilogo.jpg"> </td>
					<td > <h2>free-hack-quest:<br>'.$page->getTitle().'</h2>
					<center>
						'.$page->getContent().'
					</center>
					</td>
				</tr>
				<tr>
					<td align = "center">
						<?
                                                        //gd_info();	
							//phpinfo();

						?>
					</td>
					<td></td>
				</tr>
			</table>
			
			
			
		</td>
	</tr>
</table>

</center>

</body>
</html>';


}

?>
