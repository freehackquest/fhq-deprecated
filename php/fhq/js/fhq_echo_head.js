
// fhq_echo_head

var last_pages = Array();

function echo_last_pages()
{
	if(last_pages.length > 10)
	{
		last_pages.splice(0, last_pages.length-10);
	}
	var content = "History Pages:<br><br>\n ";
	for(var i = last_pages.length - 1; i >= 0 ; i--)
	{
		var obj = last_pages[i];
		
		var map = {
			"quests_allow" : "Allow",
			"quests_completed" : "Completed",
			"quests_process" : "Process",
			"top100" : "Top 100",
			"user_info" : "Info",
			"feedback_add" : "Add feedback",
			"feedback_my" : "Feedback",
			"dr_zoyberg" : "Dr Zoyberg",
			"feedbacks" : "Feedbacks",
			"add_quest" : "Add Quest"
		};
		
		var caption = map[obj.content_page];
		
		var content_page = "'" + obj.content_page + "'";
		content += " <font size='1'>" + obj.today.toLocaleTimeString() + "</font>"
			+ " <a class='btn btn-small btn-info' href='javascript:void(0);' onclick='load_content_page(" + content_page + ", " + JSON.stringify(obj.other_params) + " );'>" 
			+ caption + "</a><br><br>\n ";
	};
	document.getElementById("last_pages").innerHTML = content;
	// myArray.splice(0, 2)
};

function load_content_page(content_page, other_params)
{
	last_pages.push({ content_page: content_page, other_params: other_params, today : new Date() });
	
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	};  
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if(xmlhttp.responseText == "")
				document.getElementById("content_page").innerHTML = "content page don't found";
			else
			{
				echo_last_pages();
				document.getElementById("content_page").innerHTML=xmlhttp.responseText;
				document.getElementById("reload_content").onclick();
			}
		}
	}
  
	var url = "content_page.php?content_page=" + content_page;
	
	for(var key in other_params) {
		url = url + "&" + key + "=" + encodeURIComponent(other_params[key]);
	}

	// document.getElementById("debug_info").innerHTML=url;

	xmlhttp.open("GET", url ,true);
	xmlhttp.send();
};

function loadPage() {
	window.status = "Страница загружена";
};
	
	// btn btn-large btn-primary
	// btn btn-small btn-info
	
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
			window.location.href = "index.php";
		}
	}
  }
  xmlhttp.open("GET","index.php?exit",true);
  xmlhttp.send();
};

// fhq_echo_menu

function dr_zoyberg()
{
  document.getElementById("content_page").innerHTML="<img width=100% src=\"http://fc03.deviantart.net/fs70/f/2012/119/b/7/zoidberg_trace_by_deepfry3-d4y0wlc.png\"/>";
};

var myTimer;

function recalculate_score()
{
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	};
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			var text = xmlhttp.responseText;
			
			document.getElementById("view_score").innerHTML=text;
			
			var value = parseInt(text,10);
			if(isNaN(value))
			{
				if(!myTimer) myTimer = setInterval(recalculate_score,1000);
			}
			else
			{
				clearInterval(myTimer);
				myTimer = undefined;
			}
		}
	}
	
	xmlhttp.open("GET", "content_page.php?content_page=recalculate_score",true);
	xmlhttp.send();
};
