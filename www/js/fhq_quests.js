
function createQuestFilters() {
	return '<div id="quests"></div>';
}

function reloadQuests()
{
	var quests = document.getElementById("quests");
	if (!quests)
		return;
	quests.innerHTML = "Please wait...";

	var params = {};
	var q_status = fhqgui.filter.quests.userstatus;

	if (q_status == 'not_completed') {
		params.filter_open = true;
		params.filter_current = true;
		params.filter_completed = false;
	} else if (q_status == 'open') {
		params.filter_open = true;
		params.filter_current = false;
		params.filter_completed = false;
	} else if (q_status == 'in_progress') {
		params.filter_open = false;
		params.filter_current = true;
		params.filter_completed = false;
	} else if (q_status == 'completed') {
		params.filter_open = false;
		params.filter_current = false;
		params.filter_completed = true;
	} else {
		params.filter_open = true;
		params.filter_current = true;
		params.filter_completed = true;
	}

	// filter
	params.filter_subjects = fhqgui.filter.quests.subject;
	
	// alert(createUrlFromObj(params));
	send_request_post(
		'api/quests/list.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == 'fail')
			{
				quests.innerHTML = obj.error.message;
				if (obj.error.code == 1094) {
					changeGame();
				}
				return;
			}
			
			quests.innerHTML = '';
			
			var perms = obj['permissions'];
			if (perms['insert'] == true) {
				var tmp = '<center>';
				tmp += '<div class="fhqbtn" onclick="formCreateQuest();">Create Quest</div>';
				tmp += '<div class="fhqbtn" onclick="fhqgui.formImportQuest();">Import Quest</div>';
				tmp += '</center><hr>';
				quests.innerHTML += tmp; 
			}

			var stats = [];
			var tps = fhq.getQuestTypes();
			if (obj.subjects != null) {
				for (var k in tps) {
					var quest_sub_count = obj.subjects[tps[k].value];
					if (quest_sub_count != null) {
						stats.push(tps[k].caption + ': ' + quest_sub_count);
					}
				}
			}
			quests.innerHTML += '<center>' + stats.join(', ') + '</center>';

			if (params.filter_current && obj.status.current > 0)
				quests.innerHTML += '<hr><h3>In progress (' + obj.status.current + ')</h3><br><div id="current_quests"></div>';

			if (params.filter_open && obj.status.open > 0)
				quests.innerHTML += '<hr><h3>Open (' + obj.status.open + ')</h3><br><div id="open_quests"></div>'

			if (params.filter_completed && obj.status.completed > 0)
				quests.innerHTML += '<hr><h3>Completed (' + obj.status.completed + ')</h3><br><div id="completed_quests"></div>';

			var open_quests = document.getElementById("open_quests");
			var current_quests = document.getElementById("current_quests");
			var completed_quests = document.getElementById("completed_quests");

			for (var k in obj.data) {
				var q = obj.data[k];
				var content = fhqgui.questIcon(q.questid, q.name, q.subject, q.score, q.solved);
				
				if (q.status == 'current' && current_quests)
					current_quests.innerHTML += content;

				if (q.status == 'open' && open_quests)
					open_quests.innerHTML += content;

				if (q.status == 'completed' && completed_quests)
					completed_quests.innerHTML += content;
			}
		}
	);	
}

function loadQuests()
{
	fhqgui.setFilter('quests');
	var el = document.getElementById("content_page");
	el.innerHTML = createQuestFilters();
	reloadQuests();
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
		'api/quests/take.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				reloadQuests();
				showQuest(id);
			} else {
				document.getElementById("quest_error").innerHTML = obj.error.message;
			}
		}
	);
}

function passQuest(id)
{
	/*var el = document.getElementById('user_answers');
	if (el.innerHTML.length != 0) {
		el.innerHTML = "";
		return;
	}*/
	
	var params = {};
	params.questid = id;
	params.answer = document.getElementById('quest_answer').value;
	document.getElementById("quest_error").innerHTML = "";
	send_request_post(
		'api/quests/pass.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				reloadQuests();
				if (obj.new_user_score) {
					document.getElementById('view_score').innerHTML = obj.new_user_score;
				}
				showQuest(id);
			} else {
				if (isShowMyAnswers())
					updateMyAnswers(id);
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
		'api/quests/delete.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadQuests();
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
	params["text"] = document.getElementById("editquest_text").value;
	params["score"] = document.getElementById("editquest_score").value;
	params["min_score"] = document.getElementById("editquest_min_score").value;
	params["subject"] = document.getElementById("editquest_subject").value;
	params["idauthor"] = 0;
	params["author"] = document.getElementById("editquest_author").value;
	params["answer"] = document.getElementById("editquest_answer").value;
	params["state"] = document.getElementById("editquest_state").value;
	params["description_state"] = document.getElementById("editquest_description_state").value;

	// alert(createUrlFromObj(params));

	send_request_post(
		'api/quests/update.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				reloadQuests();
				showQuest(id);
			} else {
				alert(obj.error.message);
			}
		}
	);
}

// http://stackoverflow.com/questions/11076975/insert-text-into-textarea-at-cursor-position-javascript
function editQuestAddLink(filepath, filename, as) {
	var t = document.getElementById('editquest_text');
	var val = '';
	if (as == 'asfile')
		val = '<a class="fhqbtn" target="_ablank" href="' + filepath + '">Download ' + filename + '</a>';
	else if (as == 'asimg')
		val = '<img width="250px" src="' + filepath + '"/>';
	else
		val = filename;
		
	//IE support
    if (document.selection) {
        t.focus();
        sel = document.selection.createRange();
        sel.text = val;
    }
    //MOZILLA and others
    else if (t.selectionStart || t.selectionStart == '0') {
        var startPos = t.selectionStart;
        var endPos = t.selectionEnd;
        t.value = t.value.substring(0, startPos)
            + val
            + t.value.substring(endPos, t.value.length);
    } else {
        t.value += val;
    }
};

function uploadQuestFiles(questid) {
	var files = document.getElementById('editquest_upload_files').files;
	/*for(i = 0; i < files.length; i++)
		alert(files[i].name);*/
	
	send_request_post_files(
		files,
		'api/quests/files_upload.php',
		createUrlFromObj({"questid": questid}),
		function (obj) {
			if (obj.result == "fail") {
				alert(obj.error.message);
				return;
			}
			alert('uploaded!');
			formEditQuest(questid);
		}
	);
}

function removeQuestFile(id, questid)
{
	var params = {};
	params["fileid"] = id;
	// alert(createUrlFromObj(params));

	send_request_post(
		'api/quests/files_remove.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				alert("removed!");
				formEditQuest(questid);
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
		'api/quests/get_all.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				showModalDialog(obj.error.message);
				return;
			}
			var content = '\n';

			/*content += createQuestRow('Quest UUID:', '<input type="text" id="newquest_quest_uuid" value="' + guid() + '"/>');
			content += createQuestRow('', '<div class="fhqbtn" onclick="createQuest();">Create</div>');*/
			
			if (!obj.quest) {
				showModalDialog("error");
				return;
			}
			content += '<div class="quest_info_table">\n';
			
			content += createQuestRow('Quest ID: ', obj.quest);
			content += createQuestRow('Game: ', obj.data.game_title);
			content += createQuestRow('Name:', '<input type="text" id="editquest_name" value="' + obj.data.name + '"/>');
			content += createQuestRow('Text:', '<textarea id="editquest_text">' + obj.data.text + '</textarea>');
			content += createQuestRow('Files:', '<div id="editquest_files"></div>');
			content += createQuestRow('', '<input id="editquest_upload_files" multiple required="" type="file">' 
				+ ' <div class="fhqbtn" onclick="uploadQuestFiles(' + obj.quest + ');">Upload files</div>');
			content += createQuestRow('Score(+):', '<input type="text" id="editquest_score" value="' + obj.data.score + '"/>');
			content += createQuestRow('Min Score(>):', '<input type="text" id="editquest_min_score" value="' + obj.data.min_score + '"/>');
			content += createQuestRow('Subject:', fhqgui.combobox('editquest_subject', obj.data.subject, fhq.getQuestTypes()));
			// content += createQuestRow('Author Id:', '<input type="text" id="editquest_authorid" value="' + obj.data.authorid + '"/>');
			content += createQuestRow('Author:', '<input type="text" id="editquest_author" value="' + obj.data.author + '"/>');
			content += createQuestRow('Answer:', '<input type="text" id="editquest_answer" value="' + obj.data.answer + '"/>');
			content += createQuestRow('State:', fhqgui.combobox('editquest_state', obj.data.state, fhq.getQuestStates()));
			content += createQuestRow('Description State:', '<textarea id="editquest_description_state">' + obj.data.description_state + '</textarea>');
			content += createQuestRow('', '<div class="fhqbtn" onclick="updateQuest(' + obj.quest + ');">Update</div>'
				+ '<div class="fhqbtn" onclick="showQuest(' + obj.quest + ');">Cancel</div>'
			);

			content += '</div>';
			content += '<div id="quest_error"><div>';
			content += '\n';
			showModalDialog(content);
			for (var k in obj.data.files) {
				var f = document.getElementById('editquest_files');
				f.innerHTML += obj.data.files[k].filename + ' '
				+ '<div class="fhqbtn" onclick="editQuestAddLink(\'' + obj.data.files[k].filepath + '\', \'' + obj.data.files[k].filename + '\', \'asfile\');">Add link as file</div> '
				+ '<div class="fhqbtn" onclick="editQuestAddLink(\'' + obj.data.files[k].filepath + '\', \'' + obj.data.files[k].filename + '\', \'asimg\');">Add link as img</div> '
				+ ' <a class="fhqbtn" target="_ablank" href="' + obj.data.files[k].filepath + '">Download</a>' 
				+ ' <div class="fhqbtn" onclick="removeQuestFile(' + obj.data.files[k].id + ', ' + obj.quest + ');">Remove</div><br>';
			}
		}
	);
}

function showQuest(id)
{
	var params = {};
	params.taskid = id;
	send_request_post(
		'api/quests/get.php',
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
			
			if (obj.data.author)
				content += createQuestRow('Author: ', obj.data.author);

			
			if (obj.data.dt_passed == null) {
				if (obj.data.text)
					content += createQuestRow('Text: ', '<pre>' + obj.data.text + '</pre>');
				
				if (obj.data.files && obj.data.files.length > 0) {
					var files1 = '';						
					for (var k in obj.data.files) {
						files1 += '<a class="fhqbtn" href="' + obj.data.files[k].filepath + '" target="_ablank"> Download '+ obj.data.files[k].filename + '</a><br>';
					}
					content += createQuestRow('Attachmnet files: ', files1);
				}

				content += createQuestRow('', '<input id="quest_answer" type="text" onkeydown="if (event.keyCode == 13) passQuest(' + obj.quest + ');"/>'
					+ '<div class="fhqbtn" onclick="passQuest(' + obj.quest + ');">Pass quest</div>'
				);
				content += createQuestRow('', '<div class="fhqbtn" onclick="showMyAnswers(' + obj.quest + ');">Show/Hide my answers</div><br>'
					+ '<div id="user_answers"></div>'
				);					
			} else {
				if (obj.data.text)
					content += createQuestRow('Text: ', '<pre>' + obj.data.text + '</pre>');
				
				if (obj.data.files && obj.data.files.length > 0) {
					var files1 = '';						
					for (var k in obj.data.files) {
						files1 += '<a class="fhqbtn" href="' + obj.data.files[k].filepath + '" target="_ablank"> Download '+ obj.data.files[k].filename + '</a><br>';
					}
					content += createQuestRow('Attachmnet files: ', files1);
				}

				if (obj.data.dt_passed)
					content += createQuestRow('Date Stop: ', obj.data.dt_passed);
			}
			
			if (obj.permissions.edit == true && obj.permissions['delete'] == true) {
				content += createQuestRow('',
					'<div class="fhqbtn" onclick="formEditQuest(' + obj.quest + ');">Edit</div>'
					+ '<div class="fhqbtn" onclick="deleteQuest(' + obj.quest + ');">Delete</div>'
					+ '<div class="fhqbtn" onclick="fhqgui.exportQuest(' + obj.quest + ');">Export</div>'
				);
			}
			content += createQuestRow('','<div class="fhqbtn" onclick="fhqgui.openQuestInNewTab(' + obj.quest + ');">Open in new tab</div>');
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
	params["text"] = document.getElementById("newquest_text").value;
	params["score"] = document.getElementById("newquest_score").value;
	params["min_score"] = document.getElementById("newquest_min_score").value;
	params["subject"] = document.getElementById("newquest_subject").value;
	params["idauthor"] = 0; // document.getElementById("newquest_author_id").value;
	params["author"] = document.getElementById("newquest_author").value;
	params["answer"] = document.getElementById("newquest_answer").value;
	params["state"] = document.getElementById("newquest_state").value;
	params["description_state"] = document.getElementById("newquest_description_state").value;

	// alert(createUrlFromObj(params));
	send_request_post(
		'api/quests/insert.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadQuests();
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
	content += createQuestRow('Text:', '<textarea id="newquest_text"></textarea>');
	content += createQuestRow('Score(+):', '<input type="text" id="newquest_score" value="100"/>');
	content += createQuestRow('Min Score(>):', '<input type="text" id="newquest_min_score" value="0"/>');
	content += createQuestRow('Subject:', fhqgui.combobox('newquest_subject', 'trivia', fhq.getQuestTypes()));
	// content += createQuestRow('Author Id:', '<input type="text" id="newquest_author_id" value=""/>');
	content += createQuestRow('Author:', '<input type="text" id="newquest_author" value=""/>');
	content += createQuestRow('Answer:', '<input type="text" id="newquest_answer" value=""/>');
	content += createQuestRow('State:', fhqgui.combobox('newquest_state', 'open', fhq.getQuestStates()));
	content += createQuestRow('Description State:', '<textarea id="newquest_description_state"></textarea>');
	content += createQuestRow('', '<div class="fhqbtn" onclick="createQuest();">Create</div>');
	content += '</div>'; // quest_info_table
	showModalDialog(content);
}

function isShowMyAnswers() {
	var el = document.getElementById('user_answers');
	return (el.innerHTML.length != 0);
}

function updateMyAnswers(id)
{
	var params = {};
	params["questid"] = id;

	// alert(createUrlFromObj(params));
	send_request_post(
		'api/statistics/user_answers.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				var el = document.getElementById('user_answers');
				el.innerHTML = " Answers: <br>";
				for (var i = 0; i < obj.data.length; ++i) {
					el.innerHTML += '<div class="fhq_task_tryanswer">[' + obj.data[i].datetime_try + ', levenshtein: ' + obj.data[i].levenshtein + '] ' + obj.data[i].answer_try + '</div>';
				}
			} else {
				
			}
		}
	);
}

function hideMyAnswers()
{
	var el = document.getElementById('user_answers');
	el.innerHTML = "";
}

function showMyAnswers(id)
{
	if (isShowMyAnswers()) {
		hideMyAnswers();
		return;
	}

	updateMyAnswers(id);
}
