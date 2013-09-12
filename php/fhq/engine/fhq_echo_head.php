<?

function echo_head($page)
{
	echo '
<head>
	<title> Free-Hack-Quests </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">

	<link rel="stylesheet" type="text/css" href="styles/body.css" />
	<style>

		textarea.full_text
		{	
			margin: 0pt; 
			width: 300px; 
			height: 200px;
		}

	</style>
	<script language="JavaScript">
	function view_quest(idquest) 
	{
		window.showModalDialog("quest.php?idquest="+idquest, "", "dialogWidth:500px;dialogHeight:500px;status:no;edge:sunken;");
			window.location.reload(false);
	};	
	function load_content_page(content_page, other_params)
	{
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		};  
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				if(xmlhttp.responseText == "")
					document.getElementById("content_page").innerHTML = "content page don\'t found";
				else
					document.getElementById("content_page").innerHTML=xmlhttp.responseText;
			}
		}
	  
		var url = "content_page.php?content_page=" + content_page;
		
		for(var key in other_params) {
			url = url + "&" + key + "=" + encodeURIComponent(other_params[key]);
		}
		
		document.getElementById("debug_info").innerHTML=url;

		xmlhttp.open("GET", url ,true);
		xmlhttp.send();
	};
	
	function loadPage() {
		window.status = "Страница загружена";
	};
	
	function exit()
	{
	  if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
		 xmlhttp=new XMLHttpRequest();
	  };  
	  xmlhttp.onreadystatechange=function()
	  {
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if(xmlhttp.responseText == "OK")
			{
				window.location.href = \'index.php\';				
			}
		}
	  }
	  xmlhttp.open("GET","index.php?exit",true);
	  xmlhttp.send();
	};
	</script>
</head>
	';
};
?>
