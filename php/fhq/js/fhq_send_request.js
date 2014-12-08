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
