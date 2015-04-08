
function formCreateEvent() {
	var pt = new FHQParamTable();
	pt.row('Type:', fhqgui.combobox('newevent_type', 'info', fhq.getEventTypes()));
	pt.row('Message:', fhqgui.textedit('newevent_message', ''));
	pt.right(fhqgui.btn('Create', 'insertEvent();'));
	fhqgui.showModalDialog(pt.render());
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
	content += '<div class="fhqbtn" onclick="deleteEvent(' + id + ');">YES!!!</div>';
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
				var pt = new FHQParamTable();
				pt.row('Type:', fhqgui.combobox('editevent_type', obj.data.type, fhq.getEventTypes()));
				pt.row('Message:', fhqgui.textedit('editevent_message', obj.data.message));
				pt.right(fhqgui.btn('Save', 'saveEvent(' + id + ');'));
				fhqgui.showModalDialog(pt.render());
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
					content += '<div class="fhqbtn" onclick="formCreateEvent();">Create News</div><br>';
				
				for (var k in obj.data.events) {
					content += '';
					if (obj.data.events.hasOwnProperty(k)) {
						var e = obj.data.events[k];
						content += fhqgui.eventView(e, obj.access);
					}
					content += '';
				}
				el.innerHTML = content;
			}
		}
	);
}
