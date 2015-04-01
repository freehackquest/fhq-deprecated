
function load_content_html(idelem, url) {
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	};  
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById(idelem).innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", url ,true);
	xmlhttp.send();
}

function show_index_element(idelem) {
	var index_elems = [
		'indexcontent_sign_in',
		'indexcontent_registration',
		'indexcontent_restore'
	];

	for (var i = 0; i < index_elems.length; i++) {
		document.getElementById(index_elems[i]).style.display = 'none';
	}
	document.getElementById(idelem).style.display = 'block';

  // refresh captcha
  if (idelem == 'indexcontent_restore')
    document.getElementById('captcha_image_restore').src = 'captcha.php?rid=' + Math.random();
       
  if (idelem == 'indexcontent_registration') 
    document.getElementById('captcha_image_reg').src = 'captcha.php?rid=' + Math.random();
} 

function send_request(url, callbackf) {
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	};  
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var obj = JSON.parse(xmlhttp.responseText);
			callbackf(obj);
		}
	}
	xmlhttp.open("GET",url,true);
	xmlhttp.send();	
}

function restore()
{
	document.getElementById("error_message").innerHTML = "";
	document.getElementById("info_message").innerHTML = "Please wait...";
	
	var email = document.getElementById('email_restore').value;
	var captcha = document.getElementById('captcha_restore').value;
	
	send_request(
		"api/security/restore.php?email="+email + "&captcha=" + captcha,
		function(obj) {
			if (obj.result == "fail") {
				document.getElementById("error_message").innerHTML = "<b>" + obj.error.message + "</b>";
				document.getElementById("info_message").innerHTML = "";
			} else {
				document.getElementById("info_message").innerHTML = "<b>" + obj.data.message + "</b>";
				document.getElementById("captcha_restore").value = "";
				document.getElementById("email_restore").value = "";
			}
			document.getElementById('captcha_image_restore').src = 'captcha.php?rid=' + Math.random();
		}
	);
}

function registration()
{
	document.getElementById("error_message").innerHTML = "";
	document.getElementById("info_message").innerHTML = "Please wait...";
	
	var email = document.getElementById('email_reg').value;
	var captcha = document.getElementById('captcha_reg').value;
	
	send_request(
		"api/security/registration.php?email="+email + "&captcha=" + captcha,
		function(obj) {
			if (obj.result == "fail") {
				document.getElementById("error_message").innerHTML = "<b>" + obj.error.message + "</b>";
				document.getElementById("info_message").innerHTML = "";
			} else {
				document.getElementById("info_message").innerHTML = "<b>" + obj.data.message + "</b>";
				document.getElementById("captcha_reg").value = "";
				document.getElementById("email_reg").value = "";
			}
			document.getElementById('captcha_image_reg').src = 'captcha.php?rid=' + Math.random();
		}
	);
}

function loadCities() {
	var params = {};
	send_request(
		'api/settings/public_info.php',
		function (obj) {
			if (obj.result == "fail") {
				// el.innerHTML = obj.error.message;
			} else {
				var c = document.getElementById('cities');
				
				c.innerHTML += "<br><font size=1><b>Квестов в системе:</b></font><br>";
				c.innerHTML += "<font size=1>" + obj.data.quests.count + "</font><br>";
				
				c.innerHTML += "<br><font size=1><b>Количество попыток:</b></font><br>";
				c.innerHTML += "<font size=1>" + obj.data.quests.attempts + "</font><br>";
				
				c.innerHTML += "<br><font size=1><b>Количество решений:</b></font><br>";
				c.innerHTML += "<font size=1>" + obj.data.quests.solved + "</font><br>";
				
				c.innerHTML += "<br><font size=1><b>С нами играют из городов:</b></font><br>";
				for (var k in obj.data.cities) {
					c.innerHTML += '<font size=1>[' + obj.data.cities[k].city + ' (' + obj.data.cities[k].cnt + ')]</font><br>';
				}
			}
		}
	);
}


