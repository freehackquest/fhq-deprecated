
function createDivRowEvent(name, value) {
	return '<div class="user_info_row"> \n'
		+ '\t<div class="user_info_param">' + name + '</div>\n'
		+ '\t<div class="user_info_value">' + value + '</div>\n'
		+ '</div>\n';
}

function formCreateEvent() {
	var content = '<div class="fhq_game_info">';
	content += '<div class="fhq_game_info_table">\n';
	content += createDivRowEvent('Type:', '<input type="text" id="newevent_type" value="info"/>');
	content += createDivRowEvent('Message:', '<textarea id="newevent_message"></textarea>');
	content += createDivRowEvent('', '<div class="button3 ad" onclick="insertEvent();">Create</div>');
	content += '</div>'; // game_info_table
	content += '</div>\n'; // game_info
	showModalDialog(content);
}

function insertEvent()
{
	var params = {};
	params["type"] = document.getElementById("newevent_type").value;
	params["message"] = document.getElementById("newevent_message").value;
// 	alert(createUrlFromObj(params));

	send_request_post(
		'api/events/insert.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadEvents();
			} else {
				alert(obj.error.message);
			}
		}
	);
};

function deleteEvent(id)  {
	var params = {};
	params["id"] = id;
	// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/events/delete.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadEvents();
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function deleteConfirmEvent(id) {
	var content = 'Are you sure that want remove this News?<br>';
	content += '<div class="button3 ad" onclick="deleteEvent(' + id + ');">YES!!!</div>';
	showModalDialog(content);
};

function saveEvent(id)  {
	var params = {};
	params["id"] = id;
	params["type"] = document.getElementById("editevent_type").value;
	params["message"] = document.getElementById("editevent_message").value;
	
	// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/events/update.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				loadEvents();
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function formEditEvent(id, type, message)  {
	var params = {};
	params["id"] = id;
	// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/events/get.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				var content = '<div class="fhq_game_info">';
				content += '<div class="fhq_game_info_table">\n';
				content += createDivRowEvent('Type:', '<input type="text" id="editevent_type" value="' + obj.data.type + '"/>');
				content += createDivRowEvent('Message:', '<textarea id="editevent_message">' + obj.data.message + '</textarea>');
				content += createDivRowEvent('', '<div class="button3 ad" onclick="saveEvent(' + id + ');">Save</div>');
				content += '</div>'; // game_info_table
				content += '</div>\n'; // game_info
				showModalDialog(content);
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function loadEvents() {
	var params = {};
	var el = document.getElementById("content_page");
	el.innerHTML = "Please wait...";
	
	send_request_post(
		'api/events/list.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				el.innerHTML = obj.error.message;
			} else {
				var content = '';
				if (obj.access == true)
					content += '<div class="button3 ad" onclick="formCreateEvent();">Create News</div><br>';
				
				for (var k in obj.data.events) {
					content += '';
					if (obj.data.events.hasOwnProperty(k)) {
						var e = obj.data.events[k];
						
						var imgpath = '';
						if (e.type == 'users')
							imgpath = 'templates/base/images/menu_btn_default_logo_user.png';
						else if (e.type == 'quests')
							imgpath = 'templates/base/images/menu_btn_quests.png';
						else if (e.type == 'info')
							imgpath = 'templates/base/images/menu_btn_news.png';
						else
							imgpath = 'templates/base/images/menu_btn_default.png'; // default

						content += '\n<div class="fhq_event_info">\n';
						content += '	<div class="fhq_event_info_row">\n';
						content += '		<div class="fhq_event_info_cell_img"><img src="' + imgpath + '" width="100px"></div>\n';
						content += '		<div class="fhq_event_info_cell_content">\n';
						content += '			<div class="fhq_event_caption">[' + e.type + ', ' + e.dt + ']</div>';
						content += '			<div class="fhq_event_score">' + e.message + '</div>';
						if (obj.access == true) {
							content += '			<div class="fhq_event_caption">'; 
							content += '				<div class="button3 ad" onclick="deleteConfirmEvent(' + e.id + ');">Delete</div>';
							content += '				<div class="button3 ad" onclick="formEditEvent(' + e.id + ');">Edit</div>';
							content += '			</div>';
						}
						
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
