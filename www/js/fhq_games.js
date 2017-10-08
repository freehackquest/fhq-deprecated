
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
				fhqgui.loadGames();
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
			fhqgui.loadGames();
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
				fhqgui.loadGames();
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
