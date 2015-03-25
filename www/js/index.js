
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

function sign_in()
{
	document.getElementById("error_message").innerHTML = "";
	document.getElementById("info_message").innerHTML = "Please wait...";
	
	var email = document.getElementById('email').value;
	var password = document.getElementById('password').value;
	
	send_request(
		"api/auth/sign_in.php?email=" + email + "&password=" + password + "&client=web-fhq2014",
		function(obj) {
			if (obj.result == "fail") {
				document.getElementById("error_message").innerHTML = "<b>" + obj.error.message + "</b>";
				document.getElementById("info_message").innerHTML = "";
			} else {
				var date = new Date( new Date().getTime() + 60*1000 ); // cookie on hour
				document.cookie = "token=" + encodeURIComponent(obj.token) + "; path=/; expires="+date.toUTCString();
				window.location.href = "main.php";
			}
		}
	);
}

function restore()
{
	document.getElementById("error_message").innerHTML = "";
	document.getElementById("info_message").innerHTML = "Please wait...";
	
	var email = document.getElementById('email_restore').value;
	var captcha = document.getElementById('captcha_restore').value;
	
	send_request(
		"api/auth/restore.php?email="+email + "&captcha=" + captcha,
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
		"api/auth/registration.php?email="+email + "&captcha=" + captcha,
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



