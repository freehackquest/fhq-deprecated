function send_request_post(page, url, callbackf)
{
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	};  
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if(xmlhttp.responseText == "")
				alert("error");
			else
			{
				var obj = JSON.parse(xmlhttp.responseText);
				callbackf(obj);
			}
		}
	}
	xmlhttp.open("POST", page, true);
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlhttp.send(url);
};
