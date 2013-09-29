<?

function echo_head($page)
{
	echo '
<head>
	<title> Free-Hack-Quests </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">

	<link rel="stylesheet" type="text/css" href="styles/body.css" />
	<link rel="stylesheet" type="text/css" href="styles/site.css" />
	<!-- script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script -->
	<!-- script src="http://malsup.github.com/jquery.form.js"></script -->	
	<script type="text/javascript" src="js/fhq_echo_head.js"></script>
	
	<style>

		textarea.full_text
		{	
			margin: 0pt; 
			width: 300px; 
			height: 200px;
		}

	</style>
</head>
	';
};
?>
