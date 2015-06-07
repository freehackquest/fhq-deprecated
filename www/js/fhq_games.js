
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
						content += obj.data[k]['title'].trim();
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
			window.location.href = "main.php?" + Math.random();
		}
	);
}

// todo deprecated
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

function loadGames() {
	fhqgui.setFilter('games');
	
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
				el.innerHTML += '<div class="fhqinfo"><div class="fhqbtn" onclick="formCreateGame();">Create Game</div></div><br>';

			for (var k in obj.data) {
				if (obj.data.hasOwnProperty(k)) {
					el.innerHTML += fhqgui.gameView(obj.data[k], current_game);
				}
			}
			el.innerHTML += '';
		}
	);	
};

function deleteGame(id)
{
	var params = {};
	params["id"] = id;
	
	send_request_post(
		'api/games/delete.php',
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

function formDeleteGame(id)
{
	var content = '<b>If are you sure that you want to delete game with id=' + id + '.</b><br><br><br>';
	content += '<div class="fhqbtn" onclick="deleteGame(\'' + id + '\');">Delete</div><br>';
	showModalDialog(content);
};

function updateGameLogo(gameid) {
	var files = document.getElementById('editgame_new_logo').files;
	if (files.length == 0) {
		alert("Please select file");
		return;
	}
	/*for(i = 0; i < files.length; i++)
		alert(files[i].name);*/
	
	send_request_post_files(
		files,
		'api/games/upload_logo.php',
		createUrlFromObj({"gameid": gameid}),
		function (obj) {
			if (obj.result == "fail") {
				alert(obj.error.message);
				return;
			}
			document.getElementById('editgame_logo').src = obj.data.logo + '?' + new Date().getTime();
			// showModalDialog('updated');
			loadGames();
		}
	);
}

function updateGame(id) {
	// alert(id);
	var params = {};
	params["title"] = document.getElementById("editgame_title").value;
	params["state"] = document.getElementById("editgame_state").value;
	params["form"] = document.getElementById("editgame_form").value;
	params["type_game"] = document.getElementById("editgame_type_game").value;
	params["date_start"] = document.getElementById("editgame_date_start").value;
	params["date_stop"] = document.getElementById("editgame_date_stop").value;
	params["date_restart"] = document.getElementById("editgame_date_restart").value;
	params["description"] = document.getElementById("editgame_description").value; // TODO may be innerHTML
	params["organizators"] = document.getElementById("editgame_organizators").value; // TODO may be innerHTML
	params["id"] = id;

	// alert(createUrlFromObj(params));

	send_request_post(
		'api/games/update.php',
		createUrlFromObj(params),
		function (obj) {
			// alert(1);
			if (obj.result == "ok") {
				// alert(2);
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
	params.gameid = id;
	
	send_request_post(
		'api/games/get.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				
				var pt = new FHQParamTable();
				pt.row('', '<img class="fhq_game_img" id="editgame_logo" src="' + obj.data.logo + '"/>');
				pt.row('Update logo:', 'PNG: <input id="editgame_new_logo" type="file" accept="image/png" required/>');
				pt.row('', '<div class="fhqbtn" onclick="updateGameLogo(' + id + ');">Upload</div>');
				pt.skip();
				pt.row('Name (Type):',
					'<input type="text" id="editgame_title" value="' + obj.data.title + '"/> '
					+ fhqgui.combobox('editgame_type_game', obj.data.type_game, fhq.getGameTypes())
				);
				pt.row('Form/State:', 
					fhqgui.combobox('editgame_form', obj.data.form, fhq.getGameForms()) + ' / '
					+ fhqgui.combobox('editgame_state', obj.data.state, fhq.getGameStates())
				);
				pt.row('Date Start/Stop:',
					'<input type="text" id="editgame_date_start" value="' + obj.data.date_start + '"/> / '
					+ '<input type="text" id="editgame_date_stop" value="' + obj.data.date_stop + '"/>'
				);
				pt.row('Date Restart:', '<input type="text" id="editgame_date_restart" value="' + obj.data.date_restart + '"/>');
				pt.row('Description:', '<textarea id="editgame_description"></textarea>');
				pt.row('Organizators:', '<input type="text" id="editgame_organizators" value="' + obj.data.organizators + '"/>');
				pt.row('', '<div class="fhqbtn" onclick="updateGame(' + id + ');">Update</div>');
				
				showModalDialog(pt.render());
				document.getElementById('editgame_description').innerHTML = obj.data.description;
				
				$('#editgame_date_start').datetimepicker({
					format:'Y-m-d H:i:s',
					inline:false
				});

				$('#editgame_date_stop').datetimepicker({
					format:'Y-m-d H:i:s',
					inline:false
				});

				$('#editgame_date_restart').datetimepicker({
					format:'Y-m-d H:i:s',
					inline:false
				});
				
			} else {
				alert(obj.error.message);
			}
		}
	);
};

function createGame() 
{
	var params = {};
	params["uuid"] = document.getElementById("newgame_uuid").value;
	params["logo"] = document.getElementById("newgame_logo").value;
	params["title"] = document.getElementById("newgame_title").value;
	params["state"] = document.getElementById("newgame_state").value;
	params["form"] = document.getElementById("newgame_form").value;
	params["type_game"] = document.getElementById("newgame_type").value;
	params["date_start"] = document.getElementById("newgame_date_start").value;
	params["date_stop"] = document.getElementById("newgame_date_stop").value;
	params["date_restart"] = document.getElementById("newgame_date_restart").value;
	params["description"] = document.getElementById("newgame_description").value;
	params["organizators"] = document.getElementById("newgame_organizators").value;
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
	var pt = new FHQParamTable();
	pt.row('UUID Game:', '<input type="text" id="newgame_uuid" value="' + guid() + '"/>');
	pt.row('Logo:', '<input type="text" id="newgame_logo" value="http://fhq.keva.su/templates/base/images/minilogo.png"/>');
	pt.row('Name:', '<input type="text" id="newgame_title"/>');
	pt.row('State:', fhqgui.combobox('newgame_state', 'original', fhq.getGameStates()));
	pt.row('Form:', fhqgui.combobox('newgame_form', 'online', fhq.getGameForms()));
	pt.row('Type:', fhqgui.combobox('newgame_type', 'jeopardy', fhq.getGameTypes()));
	pt.row('Date Start:', '<input type="text" id="newgame_date_start" value="0000-00-00 00:00:00"/>');
	pt.row('Date Stop:', '<input type="text" id="newgame_date_stop" value="0000-00-00 00:00:00"/>');
	pt.row('Date Restart:', '<input type="text" id="newgame_date_restart" value="0000-00-00 00:00:00"/>');
	pt.row('Description:', '<textarea id="newgame_description"></textarea>');
	pt.row('Organizators:', '<input type="text" id="newgame_organizators" value=""/>');
	// pt.row('Author ID:', '<input type="text" id="newgame_author_id" value=""/>');
	pt.row('', '<div class="fhqbtn" onclick="createGame();">Create</div>');
	showModalDialog(pt.render());
	
	$('#newgame_date_start').datetimepicker({
		format:'Y-m-d H:i:s',
		inline:false
	});
	
	$('#newgame_date_stop').datetimepicker({
		format:'Y-m-d H:i:s',
		inline:false
	});
	
	$('#newgame_date_restart').datetimepicker({
		format:'Y-m-d H:i:s',
		inline:false
	});
				
}

function loadScoreboard(gameid) {
	fhqgui.setFilter('scoreboard');
	
	var params = {};
	params["gameid"] = gameid;
	
	// document.getElementById("gameid").value;
	
	send_request_post(
		'api/games/scoreboard.php',
		createUrlFromObj(params),
		function (obj) {
			
			var el = document.getElementById("content_page");
			el.innerHTML = '';
			el.innerHTML += '<a href="scoreboard.php?gameid=' + gameid + '" target="_ablank">auto refresh scoreboard</a>';
			el.innerHTML += '<div id="scoreboard_table" class="fhq_scoreboard_table"></div>';
			var tbl = document.getElementById("scoreboard_table");

			var content = '';
			for (var k in obj.data) {
				content = '<div class="fhq_scoreboard_row">';
				if (obj.data.hasOwnProperty(k)) {
					var place = obj.data[k];
					content += '<div class="fhq_scoreboard_cell">' + k + '</div>';
					var arr = [];
					for (var k2 in place) {
						arr.push(fhqgui.userIcon(place[k2].userid, place[k2].logo, place[k2].nick));
					}
					content += '<div class="fhq_scoreboard_cell">' + place[0].score + '</div>';
					content += '<div class="fhq_scoreboard_cell"><div>' + arr.join('</div><div>') + '</div></div>';
					content += '</div>';
				}
				content += '</div>'; // row
				tbl.innerHTML += content;
			}
			content = '';
		}
	);
}
