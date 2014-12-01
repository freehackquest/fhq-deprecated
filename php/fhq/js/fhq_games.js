
function changeGame2() {

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
				showModalDialog(xmlhttp.responseText);
			}
		}
	}

	var url = "content_page.php?content_page=games";
	xmlhttp.open("GET", url ,true);
	xmlhttp.send();	
}


function changeGame() {
	send_request_post(
		'api/games/list.php',
		'',
		function (obj) {
			var current_game = obj.current_game;
			var content = '\n';
			for (var k in obj.data) {
				if (obj.data.hasOwnProperty(k)) {
					if (current_game != obj.data[k]['id']) {
						
						content += '<div style="vertical-align: middle;">';
						// content += '<img width=50px src="' + obj.data[k]['logo'] + '"/>'
						content += '<div class="button3 ad hint--bottom" data-hint="'
						 + obj.data[k]['date_start'].trim() + ' - ' + obj.data[k]['date_stop'].trim()
						// content += ' ( ' + obj.data[k]['nick'].trim() + ') ';
						content += '" onclick="chooseGame(\'' + obj.data[k]['id'] + '\');">'
							+ obj.data[k]['title'].trim() ;
						content += '</div>';
						content += '</div>\n';
					}
				}
			}
			content += '\n';
			showModalDialog(content);
		}
	);
}

function chooseGame(id) {

	send_request_post(
		'api/games/choose.php',
		'id=' + id,
		function (obj) {
			window.location.href = "index.php";
		}
	);
}
