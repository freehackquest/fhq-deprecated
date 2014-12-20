
function createTaskFilters() {
	return '\n\n<div class="fhq_task_filters"> <div>Filter by status tasks: \n'
	+ '<input id="filter_open" class="fhq_task_checkbox" type="checkbox" onclick="reloadTasks();" checked />\n'
	+ '<label class="fhq_task_label lite_green_check" for="filter_open">open (<font id="filter_open_count">0</font>) </label> \n'
	+ '<input id="filter_current" class="fhq_task_checkbox" type="checkbox" onclick="reloadTasks();" checked/> \n'
	+ '<label class="fhq_task_label lite_green_check" for="filter_current">in progress (<font id="filter_current_count">0</font>) </label> \n'
	+ '<input id="filter_completed" class="fhq_task_checkbox" type="checkbox" onclick="reloadTasks();" />\n'
	+ '<label class="fhq_task_label lite_green_check" for="filter_completed">completed (<font id="filter_completed_count">0</font>) </label> \n'
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
			if (obj.result == 'fail')
			{
				tasks.innerHTML = obj.error.message;
				return;
			}
			
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
				tasks.innerHTML += '<div class="fhq_game_info"><div class="button3 ad" onclick="formCreateQuest();">Create Quest</div></div><br>';

			if (params.filter_current && obj.status.current > 0)
				tasks.innerHTML += '<hr>In progress:<br><div id="current_tasks"></div>';

			if (params.filter_open && obj.status.open > 0)
				tasks.innerHTML += '<hr>Open Tasks:<br><div id="open_tasks"></div>'

			if (params.filter_completed && obj.status.completed > 0)
				tasks.innerHTML += '<hr>Completed Tasks:<br><div id="completed_tasks"></div>';

			var open_tasks = document.getElementById("open_tasks");
			var current_tasks = document.getElementById("current_tasks");
			var completed_tasks = document.getElementById("completed_tasks");
			
			for (var k in obj.data) {
				var questid = obj.data[k]['questid'];
				var name = obj.data[k]['name'];
				var score = obj.data[k]['score'];
				var short_text = obj.data[k]['short_text'];
				var subject = obj.data[k]['subject'];
				var status = obj.data[k]['status'];

				var content = '\n\n<div class="fhq_task_info" onclick="showTask(' + questid + ');">\n';
				content += '<font class="fhq_task" size="2">' + questid + ' ' + name + '</font>\n';
				content += '<font class="fhq_task" size="5">' + subject + ' +' + score + '</font>\n';
				// content += '<font class="fhq_task" size="1">Status: ' + status + '</font>\n';
				content += '</div>\n';
				
				if (status == 'current')
					current_tasks.innerHTML += content;

				if (status == 'open')
					open_tasks.innerHTML += content;

				if (status == 'completed')
					completed_tasks.innerHTML += content;
				
				// tasks.innerHTML += content;
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

function createQuestRow(name, value)
{
	return '<div class="quest_info_row">\n'
	+ '\t<div class="quest_info_param">' + name + '</div>\n'
	+ '\t<div class="quest_info_value">' + value + '</div>\n'
	+ '</div>\n';
}

function takeQuest(id)
{
	var params = {};
	params.questid = id;
	document.getElementById("quest_error").innerHTML = "";
	send_request_post(
		'api/tasks/take.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				reloadTasks();
				showTask(id);
			} else {
				document.getElementById("quest_error").innerHTML = obj.error.message;
			}
		}
	);
}

function passQuest(id)
{
	var params = {};
	params.questid = id;
	params.answer = document.getElementById('quest_answer').value;
	document.getElementById("quest_error").innerHTML = "";
	send_request_post(
		'api/tasks/pass.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				reloadTasks();
				if (obj.new_user_score) {
					document.getElementById('view_score').innerHTML = obj.new_user_score;
				}
				showTask(id);
			} else {
				document.getElementById("quest_error").innerHTML = obj.error.message;
			}
		}
	);
}

function deleteQuest(id)
{
	if (!confirm("Are you sure that wand remove this quest?"))
		return;

	document.getElementById("quest_error").innerHTML = "";
	var params = {};
	params.questid = id;
	send_request_post(
		'api/tasks/delete.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadTasks();
			} else {
				document.getElementById("quest_error").innerHTML = obj.error.message;
			}
		}
	);
}

function updateQuest(id)
{
	var params = {};
	params["questid"] = id;
	params["name"] = document.getElementById("editquest_name").value;
	params["short_text"] = document.getElementById("editquest_short_text").value;
	params["text"] = document.getElementById("editquest_text").value;
	params["score"] = document.getElementById("editquest_score").value;
	params["min_score"] = document.getElementById("editquest_min_score").value;
	params["subject"] = document.getElementById("editquest_subject").value;
	params["idauthor"] = document.getElementById("editquest_authorid").value;
	params["author"] = document.getElementById("editquest_author").value;
	params["answer"] = document.getElementById("editquest_answer").value;
	params["state"] = document.getElementById("editquest_state").value;
	params["description_state"] = document.getElementById("editquest_description_state").value;

	// alert(createUrlFromObj(params));

	send_request_post(
		'api/tasks/update.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				reloadTasks();
				showTask(id);
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function formEditQuest(id)
{
	closeModalDialog();
	var params = {};
	params.questid = id;
	send_request_post(
		'api/tasks/get_all.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				showModalDialog(obj.error.message);
				return;
			}
			
			var content = '\n';

			/*content += createQuestRow('Quest UUID:', '<input type="text" id="newquest_quest_uuid" value="' + guid() + '"/>');
			// 
			
			content += createQuestRow('', '<div class="button3 ad" onclick="createQuest();">Create</div>');*/
			
			if (!obj.quest) {
				showModalDialog("error");
				return;
			}
			content += '<div class="quest_info_table">\n';
			
			content += createQuestRow('Quest ID: ', obj.quest);
			content += createQuestRow('Game: ', obj.data.game_title);
			content += createQuestRow('Name:', '<input type="text" id="editquest_name" value="' + obj.data.name + '"/>');
			content += createQuestRow('Short Text:', '<input type="text" id="editquest_short_text" value="' + obj.data.short_text + '"/>');
			content += createQuestRow('Text:', '<textarea id="editquest_text">' + obj.data.text + '</textarea>');
			content += createQuestRow('Score(+):', '<input type="text" id="editquest_score" value="' + obj.data.score + '"/>');
			content += createQuestRow('Min Score(>):', '<input type="text" id="editquest_min_score" value="' + obj.data.min_score + '"/>');
			content += createQuestRow('Subject:', '<input type="text" id="editquest_subject" value="' + obj.data.subject + '"/>');
			content += createQuestRow('Author Id:', '<input type="text" id="editquest_authorid" value="' + obj.data.authorid + '"/>');
			content += createQuestRow('Author:', '<input type="text" id="editquest_author" value="' + obj.data.author + '"/>');
			content += createQuestRow('Answer:', '<input type="text" id="editquest_answer" value="' + obj.data.answer + '"/>');
			content += createQuestRow('State:', '<input type="text" id="editquest_state" value="' + obj.data.state + '"/>');
			content += createQuestRow('Description State:', '<textarea id="editquest_description_state">' + obj.data.description_state + '</textarea>');
			content += createQuestRow('', '<div class="button3 ad" onclick="updateQuest(' + obj.quest + ');">Update</div>'
				+ '<div class="button3 ad" onclick="showTask(' + obj.quest + ');">Cancel</div>'
			);

			content += '</div>';
			content += '<div id="quest_error"><div>';
			content += '\n';
			showModalDialog(content);
		}
	);
}

function showTask(id)
{
	var params = {};
	params.taskid = id;
	send_request_post(
		'api/tasks/get.php',
		createUrlFromObj(params),
		function (obj) {
			var content = '\n';

			if (!obj.quest) {
				showModalDialog("error");
				return;
			}
			content += '<div class="quest_info_table">\n';
			
			content += createQuestRow('Quest ID: ', obj.quest);
			if (obj.data.game_title)
				content += createQuestRow('Game: ', obj.data.game_title);
	
			if (obj.data.name)
				content += createQuestRow('Name: ', obj.data.name);
				
			if (obj.data.subject)
				content += createQuestRow('Subject: ', obj.data.subject);

			if (obj.data.score)
				content += createQuestRow('Score: ', '+' + obj.data.score + ' (>' + obj.data.min_score + ')');
				
			if (obj.data.short_text)
				content += createQuestRow('Short Text: ', obj.data.short_text);
			
			if (obj.data.author)
				content += createQuestRow('Author: ', obj.data.author);

			if (obj.data.date_start == null && obj.data.date_stop == null) {
				content += createQuestRow('', '<div class="button3 ad" onclick="takeQuest(' + obj.quest + ');">Take quest</div>');
			} else if (obj.data.date_stop == null || obj.data.date_stop == '0000-00-00 00:00:00') {
				if (obj.data.text)
					content += createQuestRow('Text: ', obj.data.text);
				if (obj.data.date_start)
					content += createQuestRow('Date Start: ', obj.data.date_start);
				content += createQuestRow('', '<input id="quest_answer" type="text"/><div class="button3 ad" onclick="passQuest(' + obj.quest + ');">Pass quest</div>');
			} else {
				if (obj.data.text)
					content += createQuestRow('Text: ', '<pre>' + obj.data.text + '</pre>');
				if (obj.data.date_start)
					content += createQuestRow('Date Start: ', obj.data.date_start);
				if (obj.data.date_stop)
					content += createQuestRow('Date Stop: ', obj.data.date_stop);
			}
			
			if (obj.permissions.edit == true && obj.permissions['delete'] == true) {
				content += createQuestRow('',
					'<div class="button3 ad" onclick="formEditQuest(' + obj.quest + ');">Edit</div>'
					+ '<div class="button3 ad" onclick="deleteQuest(' + obj.quest + ');">Delete</div>'
				);
			}
			content += '</div>';
			content += '<div id="quest_error"><div>';
			content += '\n';
			showModalDialog(content);
		}
	);
}

function createQuest() 
{
	var params = {};
	params["quest_uuid"] = document.getElementById("newquest_quest_uuid").value;
	params["name"] = document.getElementById("newquest_name").value;
	params["short_text"] = document.getElementById("newquest_short_text").value;
	params["text"] = document.getElementById("newquest_text").innerHTML;
	params["score"] = document.getElementById("newquest_score").value;
	params["min_score"] = document.getElementById("newquest_min_score").value;
	params["subject"] = document.getElementById("newquest_subject").value;
	params["idauthor"] = document.getElementById("newquest_author_id").value;
	params["author"] = document.getElementById("newquest_author").value;
	params["answer"] = document.getElementById("newquest_answer").value;
	params["state"] = document.getElementById("newquest_state").value;
	params["description_state"] = document.getElementById("newquest_description_state").innerHTML;

	// alert(createUrlFromObj(params));
	send_request_post(
		'api/tasks/insert.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadTasks();
			} else {
				alert(obj.error.message);
			}
		}
	);
};

function formCreateQuest() 
{
	var content = '';
	content += '<div class="quest_info_table">\n';
	content += createQuestRow('Quest UUID:', '<input type="text" id="newquest_quest_uuid" value="' + guid() + '"/>');
	content += createQuestRow('Name:', '<input type="text" id="newquest_name" value=""/>');
	content += createQuestRow('Short Text:', '<input type="text" id="newquest_short_text"/>');
	content += createQuestRow('Text:', '<textarea id="newquest_text"></textarea>');
	content += createQuestRow('Score(+):', '<input type="text" id="newquest_score" value="100"/>');
	content += createQuestRow('Min Score(>):', '<input type="text" id="newquest_min_score" value="0"/>');
	content += createQuestRow('Subject:', '<input type="text" id="newquest_subject" value="enjoy"/>');
	content += createQuestRow('Author Id:', '<input type="text" id="newquest_author_id" value=""/>');
	content += createQuestRow('Author:', '<input type="text" id="newquest_author" value=""/>');
	content += createQuestRow('Answer:', '<input type="text" id="newquest_answer" value=""/>');
	content += createQuestRow('State:', '<input type="text" id="newquest_state" value="open"/>');
	content += createQuestRow('Description State:', '<textarea id="newquest_description_state"></textarea>');
	content += createQuestRow('', '<div class="button3 ad" onclick="createQuest();">Create</div>');
	content += '</div>'; // quest_info_table
	showModalDialog(content);
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
