function createUrlFromObj(obj) {
	var str = "";
	for (k in obj) {
		if (str.length > 0)
			str += "&";
		str += encodeURIComponent(k) + "=" + encodeURIComponent(obj[k]);
	}
	return str;
}

function send_request_post(page, url, callbackf)
{
	var tmpXMLhttp = null;
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		tmpXMLhttp=new XMLHttpRequest();
	};  
	tmpXMLhttp.onreadystatechange=function() {
		if (tmpXMLhttp.readyState==4 && tmpXMLhttp.status==200) {
			if(tmpXMLhttp.responseText == "")
				alert("error");
			else
			{
				try {
					var obj = JSON.parse(tmpXMLhttp.responseText);
					callbackf(obj);
				} catch(e) {
					alert("Error in js " + e.name + " on request to " + page + "\nResponse:\n" + tmpXMLhttp.responseText);
				}
				tmpXMLhttp = null;
			}
		}
	}
	tmpXMLhttp.open("POST", page, true);
	tmpXMLhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	tmpXMLhttp.send(url);
};

function send_request_post_html(page, url, callbackf)
{
	var tmpXMLhttp = null;
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		tmpXMLhttp=new XMLHttpRequest();
	};  
	tmpXMLhttp.onreadystatechange=function() {
		if (tmpXMLhttp.readyState==4 && tmpXMLhttp.status==200) {
			if(tmpXMLhttp.responseText == "")
				alert("error");
			else
			{
				callbackf(tmpXMLhttp.responseText);
				tmpXMLhttp = null;
			}
		}
	}
	tmpXMLhttp.open("POST", page, true);
	tmpXMLhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	tmpXMLhttp.send(url);
};

function send_request_post_files(files, page, url, callbackf) {
	
	var formData = new FormData();
	for(i = 0; i < files.length; i++)
		formData.append(files[i].name, files[i]);
	var tmpXMLhttp = null;
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		tmpXMLhttp=new XMLHttpRequest();
	};
	tmpXMLhttp.onreadystatechange=function() {
		if (tmpXMLhttp.readyState==4 && tmpXMLhttp.status==200) {
			if(tmpXMLhttp.responseText == "")
				alert("error");
			else
			{
				try {
					var obj = JSON.parse(tmpXMLhttp.responseText);
					callbackf(obj);
				} catch(e) {
					alert(tmpXMLhttp.responseText + ' ' + e.name);
				}
				tmpXMLhttp = null;
			}
		}
	}
	tmpXMLhttp.open("POST", page + "?" + url, true);
	// tmpXMLhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	tmpXMLhttp.send(formData);
}

var guid = (function() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
               .toString(16)
               .substring(1);
  }
  return function() {
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
           s4() + '-' + s4() + s4() + s4();
  };
})();
