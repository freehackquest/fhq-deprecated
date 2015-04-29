
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
    document.getElementById('captcha_image_restore').src = 'api/captcha.php?rid=' + Math.random();
       
  if (idelem == 'indexcontent_registration') 
    document.getElementById('captcha_image_reg').src = 'api/captcha.php?rid=' + Math.random();
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
			document.getElementById('captcha_image_restore').src = 'api/captcha.php?rid=' + Math.random();
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
			document.getElementById('captcha_image_reg').src = 'api/captcha.php?rid=' + Math.random();
		}
	);
}

function loadCities() {
	var params = {};
	send_request(
		'api/public/info.php',
		function (obj) {
			if (obj.result == "fail") {
				// el.innerHTML = obj.error.message;
			} else {
				var c = document.getElementById('cities');
				
				c.innerHTML += "<br><b>Quests:</b><br>" + obj.data.quests.count + "<br>";
				c.innerHTML += "<b>All attempts:</b><br>" + obj.data.quests.attempts + "<br>";
				c.innerHTML += "<b>Already solved:</b><br>" + obj.data.quests.solved + "<br><br>";

				c.innerHTML += "<h2>Playing with us</h2>";
				
				var cities = [];
				for (var k in obj.data.cities) {
					if (obj.data.cities[k].cnt > 1) {
						cities.push(obj.data.cities[k].city + ' (' + obj.data.cities[k].cnt + ')');
					}
				}
				c.innerHTML += cities.join(", ");

				var a = document.getElementById('about');
				var content = '<center>';
				for (var k in obj.data.winners) {

					content += '<br><b>Winner(s) of ' + k + ':</b><br>';
					var us = []
					for (var k1 in obj.data.winners[k]) {
						us.push(obj.data.winners[k][k1].user + ' with +' + obj.data.winners[k][k1].score);
					}
					content +=  us.join(', ');
					content += '<br>';
				}
				a.innerHTML += content;
			}
		}
	);
}


