
/*function createDivRowEvent(name, value) {
	return '<div class="user_info_row"> \n'
		+ '\t<div class="user_info_param">' + name + '</div>\n'
		+ '\t<div class="user_info_value">' + value + '</div>\n'
		+ '</div>\n';
}*/

var g_feedbackTypes = [
	{ type: 'complaint', caption: 'Complaint (Жалоба)'},
	{ type: 'defect', caption: 'Defect (Недочет)'},
	{ type: 'error', caption: 'Error (Ошибка)'},
	{ type: 'approval', caption: 'Approval (Одобрение)'},
	{ type: 'proposal', caption: 'Proposal (Предложение)'}
];

// the same function createComboBoxGame
function createComboBoxFeedback(idelem, value, arr) {
	var result = '<select id="' + idelem + '">';
	for (var k in arr) {
		result += '<option ';
		if (arr[k].type == value)
			result += ' selected ';
		result += ' value="' + arr[k].type + '">';
		result += arr[k].caption + '</option>';
	}
	result += '</select>';
	return result;
}

function formCreateFeedback() {
	var content = '<div class="fhq_game_info">';
	content += '<div class="fhq_game_info_table">\n';
	content += createDivRowEvent('Type:', createComboBoxFeedback('newfeedback_type', 'complaint', g_feedbackTypes));
	content += createDivRowEvent('Message:', '<textarea id="newfeedback_text"></textarea>');
	content += createDivRowEvent('', '<div class="button3 ad" onclick="insertFeedback();">Create</div>');
	content += '</div>'; // game_info_table
	content += '</div>\n'; // game_info
	showModalDialog(content);
}

function insertFeedback()
{
	var params = {};
	params["type"] = document.getElementById("newfeedback_type").value;
	params["text"] = document.getElementById("newfeedback_text").value;
// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/feedback/insert.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadFeedback();
			} else {
				alert(obj.error.message);
			}
		}
	);
};

function formInsertFeedbackMessage(feedbackid) {
	var content = '<div class="fhq_game_info">';
	content += '<div class="fhq_game_info_table">\n';
	content += createDivRowEvent('Message:', '<textarea id="newfeedbackmessage_text"></textarea>');
	content += createDivRowEvent('', '<div class="button3 ad" onclick="insertFeedbackMessage(' + feedbackid + ');">Add</div>');
	content += '</div>'; // game_info_table
	content += '</div>\n'; // game_info
	showModalDialog(content);
}

function insertFeedbackMessage(feedbackid)
{
	var params = {};
	params["feedbackid"] = feedbackid;
	params["text"] = document.getElementById("newfeedbackmessage_text").value;
// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/feedback/insertmessage.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadFeedback();
			} else {
				alert(obj.error.message);
			}
		}
	);
};

function deleteFeedback(id)  {
	var params = {};
	params["id"] = id;
	// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/feedback/delete.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadFeedback();
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function deleteConfirmFeedback(id) {
	var content = 'Are you sure that want remove this Feedback (and all answers)?<br>';
	content += '<div class="button3 ad" onclick="deleteFeedback(' + id + ');">YES!!!</div>';
	showModalDialog(content);
};

function saveFeedback(id)  {
	var params = {};
	params["id"] = id;
	params["type"] = document.getElementById("editfeedback_type").value;
	params["text"] = document.getElementById("editfeedback_text").value;
	
	// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/feedback/update.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadFeedback();
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function formEditFeedback(id)  {
	var params = {};
	params["id"] = id;
	// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/feedback/get.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				var content = '<div class="fhq_game_info">';
				content += '<div class="fhq_game_info_table">\n';
				content += createDivRowEvent('Type:', createComboBoxFeedback('editfeedback_type', obj.data.type, g_feedbackTypes));
				content += createDivRowEvent('Message:', '<textarea id="editfeedback_text">' + obj.data.text + '</textarea>');
				content += createDivRowEvent('', '<div class="button3 ad" onclick="saveFeedback(' + id + ');">Save</div>');
				content += '</div>'; // game_info_table
				content += '</div>\n'; // game_info
				showModalDialog(content);
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function deleteFeedbackMessage(id)  {
	var params = {};
	params["id"] = id;
	// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/feedback/deletemessage.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadFeedback();
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function deleteConfirmFeedbackMessage(id) {
	var content = 'Are you sure that want remove this Feedback Message?<br>';
	content += '<div class="button3 ad" onclick="deleteFeedbackMessage(' + id + ');">YES!!!</div>';
	showModalDialog(content);
};

function saveFeedbackMessage(id)  {
	var params = {};
	params["id"] = id;
	params["text"] = document.getElementById("editfeedbackmessage_text").value;
	
	// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/feedback/updatemessage.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadFeedback();
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function formEditFeedbackMessage(id)  {
	var params = {};
	params["id"] = id;
	// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/feedback/getmessage.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				var content = '<div class="fhq_game_info">';
				content += '<div class="fhq_game_info_table">\n';
				content += createDivRowEvent('Message:', '<textarea id="editfeedbackmessage_text">' + obj.data.text + '</textarea>');
				content += createDivRowEvent('', '<div class="button3 ad" onclick="saveFeedbackMessage(' + id + ');">Save</div>');
				content += '</div>'; // game_info_table
				content += '</div>\n'; // game_info
				showModalDialog(content);
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function loadFeedback() {
	var params = {};
	var el = document.getElementById("content_page");
	el.innerHTML = "Please wait...";
	
	send_request_post(
		'api/feedback/list.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				el.innerHTML = obj.error.message;
			} else {
				var content = '';
				content += '<div class="button3 ad" onclick="formCreateFeedback();">Create Feedback</div><br>';
				
				for (var k in obj.data.feedback) {
					content += '';
					if (obj.data.feedback.hasOwnProperty(k)) {
						var f = obj.data.feedback[k];

						content += '\n<div class="fhq_event_info">\n';
						content += '	<div class="fhq_event_info_row">\n';
						content += '		<div class="fhq_event_info_cell_img"><img src="' + f.logo + '" width="100px"></div>\n';
						content += '		<div class="fhq_event_info_cell_content">\n';
						content += '			<div class="fhq_event_caption">[' + f.type + ', ' + f.dt + ', {' + f.nick + '}, {' + f.email + '}]</div>';
						content += '			<div class="fhq_feedback_text"><pre>' + f.text + '</pre></div>';
						content += '			<div class="fhq_event_caption">'; 
						content += '				<div class="button3 ad" onclick="formInsertFeedbackMessage(' + f.id + ');">Add message</div>';
						if (obj.access == true) {
							content += '				<div class="button3 ad" onclick="deleteConfirmFeedback(' + f.id + ');">Delete</div>';
							content += '				<div class="button3 ad" onclick="formEditFeedback(' + f.id + ');">Edit</div>';
						}
						content += '			</div>';
						
						content += '			<div class="fhq_event_caption">'; 
						/*content += '				<div class="button3 ad" onclick="deleteConfirmEvent(' + f.id + ');">Delete</div>';
						content += '				<div class="button3 ad" onclick="formEditEvent(' + f.id + ');">Edit</div>';*/
						
						for (var k1 in f.messages) {
							var m = f.messages[k1];
							content += '\n<div class="fhq_event_info">\n';
							content += '	<div class="fhq_event_info_row">\n';
							content += '		<div class="fhq_event_info_cell_img"><img src="' + m.logo + '" width="100px"></div>\n';
							content += '		<div class="fhq_event_info_cell_content">\n';
							content += '			<div class="fhq_event_caption">[' + m.dt + ', {' + m.nick + '}, {' + m.email + '}]</div>';
							content += '			<div class="fhq_feedback_text"><pre>' + m.text + '</pre></div>';
							if (obj.access == true) {
								content += '			<div class="fhq_event_caption">'; 
								content += '				<div class="button3 ad" onclick="deleteConfirmFeedbackMessage(' + m.id + ');">Delete</div>';
								content += '				<div class="button3 ad" onclick="formEditFeedbackMessage(' + m.id + ');">Edit</div>';
								content += '			</div>';
							}
							content += '		</div>'; // fhq_event_info_cell_content
							content += '	</div>'; // fhq_event_info_row
							content += '</div><br>'; // fhq_event_info
							/*content += '				<div class="button3 ad" onclick="deleteConfirmEvent(' + f.id + ');">Delete</div>';
							content += '				<div class="button3 ad" onclick="formEditEvent(' + f.id + ');">Edit</div>';*/
						}
						content += '			</div>';

						content += '		</div>'; // fhq_event_info_cell_content
						content += '	</div>'; // fhq_event_info_row
						content += '</div><br>'; // fhq_event_info
					}
					content += '';
				}
				el.innerHTML = content;
			}
		}
	);
}
