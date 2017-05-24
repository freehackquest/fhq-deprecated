

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
				updateEvents();
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
				updateEvents();
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

function updateCountOfEvents() {
	fhq.api.events.count().done(function(count) {
		var el = document.getElementById('plus_events');		
		if (count == 0) {
			if (el.style.visibility != 'hidden')
				el.style.visibility = 'hidden';
			el.innerHTML = '0';
		} else {
			if (el.style.visibility == 'hidden') {
				el.style.visibility = 'visible';
			}
			el.innerHTML = '+' + count;
		}
		setTimeout(function(){ updateCountOfEvents(); }, 5000);
	});
}
