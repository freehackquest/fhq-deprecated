<?

function echo_head($page)
{
	echo "
	<head>
	<title> ".$page->title()." </title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf8\">

	<link rel='stylesheet' type='text/css' href='styles/body.css' />

	<style type=\"text/css\">
	   A.allow {
		background: url(images/allow.gif); /* Путь к файлу с исходным рисунком  */
		display: block; /* Рисунок как блочный элемент */
		width: 150px; /* Ширина рисунка */
		height: 50px; /* Высота рисунка */
	   }
	   A.allow:hover {
		background: url(images/allow_go.gif); /* Путь к файлу с заменяемым рисунком  */
	   }

	   A.process {
		background: url(images/process.gif); /* Путь к файлу с исходным рисунком  */
		display: block; /* Рисунок как блочный элемент */
		width: 150px; /* Ширина рисунка */
		height: 50px; /* Высота рисунка */
	   }
	   A.process:hover {
		background: url(images/process_go.gif); /* Путь к файлу с заменяемым рисунком  */
	   }

	   A.completed {
		background: url(images/completed.gif); /* Путь к файлу с исходным рисунком  */
		display: block; /* Рисунок как блочный элемент */
		width: 150px; /* Ширина рисунка */
		height: 50px; /* Высота рисунка */
	   }
	   A.completed:hover {
		background: url(images/completed_go.gif); /* Путь к файлу с заменяемым рисунком  */
	   }

	   A.top100 {
		background: url(images/top100.gif); /* Путь к файлу с исходным рисунком  */
		display: block; /* Рисунок как блочный элемент */
		width: 150px; /* Ширина рисунка */
		height: 50px; /* Высота рисунка */
	   }
	   A.top100:hover {
		background: url(images/top100_go.gif); /* Путь к файлу с заменяемым рисунком  */
	   }

	   A.feedback {
		background: url(images/feedback.gif); /* Путь к файлу с исходным рисунком  */
		display: block; /* Рисунок как блочный элемент */
		width: 150px; /* Ширина рисунка */
		height: 50px; /* Высота рисунка */
	   }
	   A.feedback:hover {
		background: url(images/feedback_go.gif); /* Путь к файлу с заменяемым рисунком  */
	   }
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
