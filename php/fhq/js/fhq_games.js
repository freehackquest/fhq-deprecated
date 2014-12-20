
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

function updateScore() {
	send_request_post(
		'api/games/update_score.php',
		'',
		function (obj) {
			if(obj.result == "ok") {
				var el1 = document.getElementById('view_score');
				var el2 = document.getElementById('user_score');
				if (el1)
					el1.innerHTML = obj.user.score;
				if (el2)
					el2.innerHTML = obj.user.score;
			}
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
			
			var perms = obj['permissions'];
			if (perms['insert'] == true)
				el.innerHTML += '<div class="fhq_game_info"><div class="button3 ad" onclick="formCreateGame();">Create Game</div></div><br>';
				
			for (var k in obj.data) {
				var content = '<div class="fhq_game_info">' 
				
				content += '<div class="fhq_game_info_table">\n';
				
				if (obj.data.hasOwnProperty(k)) {
					content += createDivRowGame('Logo:', '<img class="fhq_game_img" src="' + obj.data[k]['logo'] + '"/>');
					content += createDivRowGame('Name:', obj.data[k]['title'].trim());
					content += createDivRowGame('Type:', obj.data[k]['type_game'].trim());
					content += createDivRowGame('Date Start:', obj.data[k]['date_start'].trim());
					content += createDivRowGame('Date Stop:', obj.data[k]['date_stop'].trim());
					content += createDivRowGame('Date Restart:', (obj.data[k]['date_restart'] + '').trim());
					content += createDivRowGame('Description:', (obj.data[k]['description'] + '').trim());
					content += createDivRowGame('Owner:', obj.data[k]['nick'].trim());
					
					var btns = '';
					
					if (current_game != obj.data[k]['id'])
						btns += '<div class="button3 ad" onclick="chooseGame(\'' + obj.data[k]['id'] + '\');">Choose</div> ';
					else
						btns += 'Current Game';

					var perms = obj.data[k]['permissions'];
					
					if (perms['delete'] == true)
						btns += '<div class="button3 ad" onclick="formDeleteGame(\'' + obj.data[k]['id'] + '\');">Delete</div>';
						
					if (perms['update'] == true)
						btns += '<div class="button3 ad" onclick="formEditGame(\'' + obj.data[k]['id'] + '\');">Edit</div>';

					content += createDivRowGame(' ', btns);
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
};

function deleteGame(id)
{
	var params = {};
	params["id"] = id;
	params["captcha"] = document.getElementById("captcha_delete_game").value;
	
	send_request_post(
		'api/games/delete.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadGames();
			} else {
				alert(obj.error.message);
				document.getElementById('captcha_delete_game_img').src = 'captcha.php?rid=' + Math.random();
			}
		}
	);
};

function formDeleteGame(id)
{
	var content = '<b>If are you sure that you want to delete game with id=' + id + '.<br> Please fill in the captcha below.</b><br><br><br>';
	content += '<input type="text" id="captcha_delete_game"/><br><br>';
	content += '<img src="captcha.php" id="captcha_delete_game_img"/><br>';
	content += '<a href="javascript:void(0);" onclick="document.getElementById(\'captcha_delete_game_img\').src = \'captcha.php?rid=\' + Math.random();">Refresh captcha</a><br><br>';
	content += '<div class="button3 ad" onclick="deleteGame(\'' + id + '\');">Delete</div><br>';
	showModalDialog(content);
};

function updateGame(id) {
	// alert(id);
	var params = {};
	params["logo"] = document.getElementById("editgame_logo").value;
	params["title"] = document.getElementById("editgame_title").value;
	params["type_game"] = document.getElementById("editgame_type_game").value;
	params["date_start"] = document.getElementById("editgame_date_start").value;
	params["date_stop"] = document.getElementById("editgame_date_stop").value;
	params["date_restart"] = document.getElementById("editgame_date_restart").value;
	params["description"] = document.getElementById("editgame_description").value; // TODO may be innerHTML
	params["id"] = id;
	
	// alert(createUrlFromObj(params));

	send_request_post(
		'api/games/update.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadGames();
			} else {
				alert(obj.error.message);
			}
		}
	);
}


function formEditGame(id)
{
	var params = {};
	params["id"] = id;
	
	send_request_post(
		'api/games/get.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				var content = '<div class="fhq_game_info">';
				content += '<div class="fhq_game_info_table">\n';
				content += createDivRowGame('Logo:', '<input type="text" id="editgame_logo" value="' + obj.data.logo + '"/>');
				content += createDivRowGame('Name:', '<input type="text" id="editgame_title" value="' + obj.data.title + '"/>');
				content += createDivRowGame('Type:', '<input type="text" id="editgame_type_game" value="' + obj.data.type_game + '"/>');
				content += createDivRowGame('Date Start:', '<input type="text" id="editgame_date_start" value="' + obj.data.date_start + '"/>');
				content += createDivRowGame('Date Stop:', '<input type="text" id="editgame_date_stop" value="' + obj.data.date_stop + '"/>');
  			content += createDivRowGame('Date Restart:', '<input type="text" id="editgame_date_restart" value="' + obj.data.date_restart + '"/>');
  			content += createDivRowGame('Description:', '<textarea id="editgame_description">' + obj.data.description + '</textarea>');
				// content += createDivRowGame('Author ID:', '<input type="text" id="newgame_author_id" value=""/>');
				content += createDivRowGame('', '<div class="button3 ad" onclick="updateGame(\'' + id + '\');">Update</div>');
				content += '</div>'; // game_info_table
				content += '</div>\n'; // game_info
				showModalDialog(content);
			} else {
				alert(obj.error.message);
			}
		}
	);
};

function createGame() 
{
	var params = {};
	params["uuid_game"] = document.getElementById("newgame_uuid_game").value;
	params["logo"] = document.getElementById("newgame_logo").value;
	params["title"] = document.getElementById("newgame_title").value;
	params["type_game"] = document.getElementById("newgame_type").value;
	params["date_start"] = document.getElementById("newgame_date_start").value;
	params["date_stop"] = document.getElementById("newgame_date_stop").value;
	params["date_restart"] = document.getElementById("newgame_date_restart").value;
	params["description"] = document.getElementById("newgame_description").value;
	// params["author_id"] = document.getElementById("newgame_author_id").value;
	// alert(createUrlFromObj(params));

	send_request_post(
		'api/games/insert.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadGames();
			} else {
				alert(obj.error.message);
			}
		}
	);
};

function formCreateGame() 
{
	var content = '<div class="fhq_game_info">';
	content += '<div class="fhq_game_info_table">\n';
	content += createDivRowGame('UUID Game:', '<input type="text" id="newgame_uuid_game" value="' + guid() + '"/>');
	content += createDivRowGame('Logo:', '<input type="text" id="newgame_logo" value="http://fhq.keva.su/templates/base/images/minilogo.png"/>');
	content += createDivRowGame('Name:', '<input type="text" id="newgame_title"/>');
	content += createDivRowGame('Type:', '<select id="newgame_type"> <option value="jeopardy">Jeopardy</option><option value="attack-defence">Attack-Defence</option></select>');
	content += createDivRowGame('Date Start:', '<input type="text" id="newgame_date_start" value="0000-00-00 00:00:00"/>');
	content += createDivRowGame('Date Stop:', '<input type="text" id="newgame_date_stop" value="0000-00-00 00:00:00"/>');
  content += createDivRowGame('Date Restart:', '<input type="text" id="newgame_date_restart" value="0000-00-00 00:00:00"/>');
  content += createDivRowGame('Description:', '<textarea id="newgame_description"></textarea>');
	// content += createDivRowGame('Author ID:', '<input type="text" id="newgame_author_id" value=""/>');
	content += createDivRowGame('', '<div class="button3 ad" onclick="createGame();">Create</div>');
	content += '</div>'; // game_info_table
	content += '</div>\n'; // game_info
	showModalDialog(content);
}

function loadScoreboard(gameid) {
	var params = {};
	params["gameid"] = gameid;
	
	// document.getElementById("gameid").value;
	
	send_request_post(
		'api/games/scoreboard.php',
		createUrlFromObj(params),
		function (obj) {
			
			var el = document.getElementById("content_page");
			el.innerHTML = '<div id="scoreboard_table" class="fhq_scoreboard_table"></div>';
			var tbl = document.getElementById("scoreboard_table");

			var content = '';
			for (var k in obj.data) {
				content = '<div class="fhq_scoreboard_row">';
				if (obj.data.hasOwnProperty(k)) {
					var place = obj.data[k];
					content += '<div class="fhq_scoreboard_cell">' + k + '</div>';
					var arr = [];
					for (var k2 in place) {
						arr.push(place[k2].nick);
					}
					content += '<div class="fhq_scoreboard_cell">' + place[0].score + '</div>';
					content += '<div class="fhq_scoreboard_cell">' + arr.join(',') + '</div>';
					content += '</div>';
				}
				content += '</div>'; // row
				tbl.innerHTML += content;
			}
			content = '';
		}
	);
}
