
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
						content += obj.data[k]['title'].trim() + ' (' + obj.data[k]['type_game'] + ')';
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

function createDivRowGame(name, value) {
	return '<div class="user_info_row"> \n'
		+ '\t<div class="user_info_param">' + name + '</div>\n'
		+ '\t<div class="user_info_value">' + value + '</div>\n'
		+ '</div>\n';
}

function loadGames() {
	var el = document.getElementById("content_page");
	el.innerHTML = "Please wait...";
	
	send_request_post(
		'api/games/list.php',
		'',
		function (obj) {
			var current_game = obj.current_game;

			el.innerHTML = '';
			for (var k in obj.data) {
				var content = '<div class="fhq_game_info">' 
				
				content += '<div class="fhq_game_info_table">\n';
				
				if (obj.data.hasOwnProperty(k)) {
					content += createDivRowGame('Logo:', '<img class="fhq_game_img" src="' + obj.data[k]['logo'] + '"/>');
					content += createDivRowGame('Name:', obj.data[k]['title'].trim());
					content += createDivRowGame('Type:', obj.data[k]['type_game'].trim());
					content += createDivRowGame('Date Start:', obj.data[k]['date_start'].trim());
					content += createDivRowGame('Date Stop:', obj.data[k]['date_stop'].trim());
					content += createDivRowGame('Owner:', obj.data[k]['nick'].trim());
					if (current_game != obj.data[k]['id'])
						content += createDivRowGame('', '<div class="button3 ad" onclick="chooseGame(\'' + obj.data[k]['id'] + '\');">Choose</div>');
					else
						content += createDivRowGame(' ', 'Current Game');
				}
				content += '\n';
				content += '<div class="user_info_row_skip">';
				
				content += '</div>'; // game_info_table
				content += '</div>\n'; // game_info
				el.innerHTML += content;
			}
			el.innerHTML += '';
		}
	);	
}

