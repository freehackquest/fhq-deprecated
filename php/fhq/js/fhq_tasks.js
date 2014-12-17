
function createTaskFilters() {
	return '\n\n<div class="fhq_task_filters"> <div>Filter by status tasks: \n'
	+ '<input id="filter_open" class="fhq_task_checkbox" type="checkbox" onclick="reloadTasks();" checked />\n'
	+ '<label class="fhq_task_label lite_green_check" for="filter_open">open (<font id="filter_open_count"></font>) </label> \n'
	+ '<input id="filter_current" class="fhq_task_checkbox" type="checkbox" onclick="reloadTasks();" checked/> \n'
	+ '<label class="fhq_task_label lite_green_check" for="filter_current">current (<font id="filter_current_count"></font>) </label> \n'
	+ '<input id="filter_completed" class="fhq_task_checkbox" type="checkbox" onclick="reloadTasks();" />\n'
	+ '<label class="fhq_task_label lite_green_check" for="filter_completed">completed (<font id="filter_completed_count"></font>) </label> \n'
	+ '</div>\n'
	+ '<div id="filter_by_subject"></div> \n'
	+ '</div> \n'
	+ '<div id="tasks"></div> \n';
}

function reloadTasks()
{
	var tasks = document.getElementById("tasks");
	tasks.innerHTML = "Please wait...";
	
	var params = {};
	params.filter_open = document.getElementById("filter_open").checked;
	params.filter_current = document.getElementById("filter_current").checked;
	params.filter_completed = document.getElementById("filter_completed").checked;

	// filter
	var arr = []
	var elems = document.getElementsByName("filter_subjects");
	for (var i = 0; i < elems.length; i++) {
		if (elems[i].checked)
			arr.push(elems[i].getAttribute("subject"));
	}
	params.filter_subjects = arr.join(",");
	
	// alert(createUrlFromObj(params));
	send_request_post(
		'api/tasks/list.php',
		createUrlFromObj(params),
		function (obj) {
			// var current_game = obj.current_game;
			document.getElementById("filter_open_count").innerHTML = obj.status.open;
			document.getElementById("filter_current_count").innerHTML = obj.status.current;
			document.getElementById("filter_completed_count").innerHTML = obj.status.completed;
			var filter_by_subject = document.getElementById('filter_by_subject');
			if (filter_by_subject.innerHTML == "")
			{
				filter_by_subject.innerHTML = 'Filter by subject: \n';
				for (var k in obj.subjects) {
					filter_by_subject.innerHTML += '<input name="filter_subjects" subject="' + k + '" id="filter_subject_' + k + '" type="checkbox" class="fhq_task_checkbox" onclick="reloadTasks();" checked/>'
					+ '<label class="fhq_task_label lite_green_check" for="filter_subject_' + k + '">' + k + ' (' + obj.subjects[k] + ') </label> \n'
				}
			}

			tasks.innerHTML = '';
			var perms = obj['permissions'];
			if (perms['insert'] == true)
				tasks.innerHTML += '<div class="fhq_game_info"><div class="button3 ad" onclick="formCreateTask();">Create Task</div></div><br>';

			for (var k in obj.data) {
				var questid = obj.data[k]['questid'];
				var name = obj.data[k]['name'];
				var score = obj.data[k]['score'];
				var short_text = obj.data[k]['short_text'];
				var subject = obj.data[k]['subject'];
				var status = obj.data[k]['status'];

				var content = '\n\n<div class="fhq_task_info" onclick="showTask(' + questid + ');">\n';
				content += '<font class="fhq_task" size="2">' + questid + ' ' + name + '</font>\n';
				content += '<font class="fhq_task" size="5">+' + score + '</font>\n';
				content += '<font class="fhq_task" size="1">Subject: ' + subject + '</font>\n';
				content += '<font class="fhq_task" size="1">Status: ' + status + '</font>\n';
				content += '</div>\n';
				tasks.innerHTML += content;
			}
		}
	);	
}

function loadTasks()
{
	var el = document.getElementById("content_page");
	el.innerHTML = createTaskFilters();
	reloadTasks();
}

function showTask(id)
{
	alert(id);
	
	/*
	 * var el = document.getElementById("content_page");
	el.innerHTML = createTaskFilters();
	reloadTasks(); 
	* */
}

/*
function createDivRowGame(name, value) {
	return '<div class="user_info_row"> \n'
		+ '\t<div class="user_info_param">' + name + '</div>\n'
		+ '\t<div class="user_info_value">' + value + '</div>\n'
		+ '</div>\n';
}

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
  params["date_stop"] = document.getElementById("newgame_date_stop").value;
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

function formCreateTask() 
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
*/
