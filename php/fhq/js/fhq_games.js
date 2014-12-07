
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
						
						content += '<div class="fhq_game_line" onclick="chooseGame(\'' + obj.data[k]['id'] + '\');">\n';
						content += '\t<img class="fhq_game_img" src="' + obj.data[k]['logo'] + '" /> '
						content += '\t<div class="fhq_game_text">\n';
						// content += ' ( ' + obj.data[k]['nick'].trim() + ') ';
						content += obj.data[k]['title'].trim() ;
						content += '\t</div>\n';
						content += '<br><div class="fhq_game_text">' + obj.data[k]['date_start'].trim() + ' - ' + obj.data[k]['date_stop'].trim() + '</div><br>\n';
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
