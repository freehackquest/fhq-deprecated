

function reload_news()
{
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	};  
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if(xmlhttp.responseText == "")
				document.getElementById("news").innerHTML = "not found";
			else
			{
				document.getElementById("news").innerHTML=xmlhttp.responseText;
			}
		}
	}
	// document.getElementById("news").innerHTML="<img src='images/ajax-loader.gif'/>";

	var url = "content_page.php?content_page=news";
	xmlhttp.open("GET", url ,true);
	xmlhttp.send();
  
};

// var myTimerNews;
// if(!myTimerNews) myTimerNews = setInterval(reload_news,10000);

function load_content_page(content_page, other_params)
{
	if(other_params == 'undefined') other_params = {};
	
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
				document.getElementById("content_page").innerHTML=xmlhttp.responseText;
				document.getElementById("reload_content").onclick();
				//reload_news();
			}
		}
	}
  
	var url = "content_page.php?content_page=" + content_page;
	
	for(var key in other_params) {
		url = url + "&" + key + "=" + encodeURIComponent(other_params[key]);
	}

	// document.getElementById("debug_info").innerHTML=url;
	document.getElementById("content_page").innerHTML="<img src='images/Minimap_Loading.gif'/>";

	xmlhttp.open("GET", url ,true);
	xmlhttp.send();
};

function load_content_page2(content_page, other_params)
{
	if(other_params == 'undefined') other_params = {};
	
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
				document.getElementById("content_page").innerHTML=xmlhttp.responseText;
				document.getElementById("reload_content").onclick();
				//reload_news();
			}
		}
	}
  
	var daten = "content_page=" + encodeURIComponent(content_page);
	
	for(var key in other_params) {
		daten = daten + "&" + encodeURIComponent(key) + "=" + encodeURIComponent(other_params[key]);
	}

	// document.getElementById("debug_info").innerHTML=daten;
	document.getElementById("content_page").innerHTML = "<img src='images/Minimap_Loading.gif'/>";
	xmlhttp.open("POST","content_page.php", true);
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlhttp.send(daten);
};				

function loadPage() {
	window.status = "Страница загружена";
};
	
function delete_quest() {

    if (!confirm("Are you sure want to delete quest?")) return false;
    else return true;
};

function delete_user() {

    if (!confirm("Are you sure want to delete user?")) return false;
    else return true;
};

function delete_file() {
    if (!confirm("Are you sure want to delete file?")) return false;
    else return true;
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

function load_content_page_files(files, content_page, other_params) {
		
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	};
  
	// alert(file);
  
    // Create form data
    var formData = new FormData();
    for(i = 0; i < files.length; i++)
		formData.append(files[i].name, files[i]);

	var url = "content_page.php?content_page=" + content_page;
	
	for(var key in other_params) {
		url = url + "&" + key + "=" + encodeURIComponent(other_params[key]);
	}
	// Open
	xmlhttp.open('POST', url, true);

    /*
    // Set headers
    xmlhttp.setRequestHeader("Cache-Control", "no-cache");
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xmlhttp.setRequestHeader("Content-Type", "multipart/form-data");
    xmlhttp.setRequestHeader("X-File-Name", file.name);
    xmlhttp.setRequestHeader("X-File-Size", file.size);
    xmlhttp.setRequestHeader("X-File-Type", "application/octet-stream");
	*/
	
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
	
	// Send
    xmlhttp.send(formData);
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
