
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
			"add_quest" : "Add Quest",
			"edit_quest" : "Edit Quest",
			"view_quest" : "View Quest"
		};
		
		var caption = map[obj.content_page];
		
		var content_page = "'" + obj.content_page + "'";
		var str = "" + JSON.stringify(obj.other_params);
		content += " <font size='1'>" + obj.today.toLocaleTimeString() + "</font>"
			+ " <a class='btn btn-small btn-info' href='javascript:void(0);' onclick=\"load_content_page(" + content_page + ", " + str.replace(/"/g, "\'") + ", true );\">" 
			+ caption + "</a><br><br>\n ";
	};
	document.getElementById("last_pages").innerHTML = content;
	// myArray.splice(0, 2)
};

function load_content_page(content_page, other_params, from_lp)
{
	if(other_params == 'undefined') other_params = {};
   if(from_lp == 'undefined') from_lp = false;
   
	if(
		content_page != 'take_quest' && 
		content_page != 'save_quest' && 
		content_page != 'pass_quest' && 
		content_page != 'delete_quest' && 
		content_page != 'remove_file' && 
		!from_lp
	)
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
	document.getElementById("content_page").innerHTML="<img src='images/Minimap_Loading.gif'/>";

	xmlhttp.open("GET", url ,true);
	xmlhttp.send();
};

function loadPage() {
	window.status = "Страница загружена";
};
	
function delete_quest() {

    if (!confirm("Are you sure you want to delete quest?")) return false;
    else return true;
};
function delete_file() {
    if (!confirm("Are you sure you want to delete file?")) return false;
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
