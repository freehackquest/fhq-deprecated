
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
				updateEvents();
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

/*
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

				var tmpLastEventId = fhq.users.getLastEventId();
				var maxLastEventId = fhq.users.getLastEventId();
				for (var k in obj.data.events) {
					content += '';
					if (obj.data.events.hasOwnProperty(k)) {
						var e = obj.data.events[k];
						if (e.id > maxLastEventId) {
							maxLastEventId = e.id;
						}
						e.marknew = false;
						if (e.id > tmpLastEventId) {
							e.marknew = true;
						}
						content += fhqgui.eventView(e, obj.access); // TODO mark events which new
					}
					content += '';
				}
				el.innerHTML = content;
				if (tmpLastEventId != maxLastEventId)
					fhq.users.setLastEventId(maxLastEventId);
			}
		}
	);
}
*/

function updateCountOfEvents() {
	// alert(1);
	// alert(fhq.users.getLastEventId());
	fhq.events.count(function (obj) {
		if (obj.result == 'ok') {
			var el = document.getElementById('plus_events');		
			if (obj.data.count == 0) {
				if (el.style.visibility != 'hidden')
					el.style.visibility = 'hidden';
				el.innerHTML = '0';
			} else {
				if (el.style.visibility == 'hidden') {
					el.style.visibility = 'visible';
				}
				el.innerHTML = obj.data.count;
			}
		} else {
			// el.innerHTML = obj.error.message;
		}
		setTimeout(function(){ updateCountOfEvents(); }, 5000);
	});
}

function resetEventsPage() {
	document.getElementById('events_page').value = 0;
}

function setEventsPage(val) {
	document.getElementById('events_page').value = val;
}

function createPageEvents() {
	var pt = new FHQParamTable();
	// TODO
	/*pt.row('',fhqgui.btn('Create News', 'formCreateEvent();'));
	pt.skip();*/
	pt.row('Search:', '<input type="text" id="events_search" value="" onkeydown="if (event.keyCode == 13) {resetEventsPage(); updateEvents();};"/>');
	pt.row('Type:', fhqgui.combobox('events_type', '', fhq.getEventTypesFilter()));
	pt.row('On Page:', fhqgui.combobox('events_onpage', '15', fhq.getOnPage()));
	pt.row('', fhqgui.btn('Search', 'resetEventsPage(); updateEvents();'));
	pt.skip();
	pt.row('Found:', '<font id="search_found">0</font>');
	var cp = new FHQContentPage();
	cp.clear();
	cp.append(pt.render());
	cp.append('<input type="hidden" id="events_page" value="0"/>'
		+ '<div id="error_search"></div>'
		+ '<hr/>'
		+ '<div id="listEvents"></div>');
}

function updateEvents() {
	var lu = document.getElementById("listEvents");
	lu.innerHTML = "Please wait...";
	
	var params = {};
	params.search = document.getElementById('events_search').value;
	params.type = document.getElementById('events_type').value;
	params.page = document.getElementById('events_page').value;
	params.onpage = document.getElementById('events_onpage').value;

	// alert(createUrlFromObj(params));
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

				var found = parseInt(obj.data.found, 10);
				document.getElementById("search_found").innerHTML = found;

				var onpage = parseInt(obj.data.onpage, 10);
				var page = parseInt(obj.data.page, 10);

				content += '<div id="events_paging">' + fhqgui.paginator(0, found, onpage, page, 'setEventsPage', 'updateEvents') + '</div>';
				
				var nLastEventId = fhq.users.getLastEventId();
				var maxid = parseInt(obj.data.maxid, 10);
				for (var k in obj.data.events) {
					content += '';
					if (obj.data.events.hasOwnProperty(k)) {
						var e = obj.data.events[k];

						e.marknew = false;
						if (e.id > nLastEventId) {
							e.marknew = true;
						}
						content += fhqgui.eventView(e, obj.access); // TODO mark events which new
					}
					content += '';
				}
				lu.innerHTML = content;
				lu.innerHTML += "obj.data.maxid: " + maxid + ", lastEventId: " + nLastEventId;
				if (maxid > nLastEventId) {
					fhq.users.setLastEventId(maxid);
				}
			}
		}
	);
}
