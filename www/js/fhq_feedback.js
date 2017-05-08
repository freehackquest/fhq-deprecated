

function saveFeedback()  {
	var feedback = new FHQFeedback();
	// alert(createUrlFromObj(feedback.params()));
	send_request_post(
		'api/feedback/update.php',
		createUrlFromObj(feedback.params()),
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

function formInsertFeedbackMessage(feedbackid) {
	var pt = new FHQParamTable();
	pt.row('Message:', fhqgui.textedit('newfeedbackmessage_text', ''));
	pt.right(fhqgui.btn('Add', 'insertFeedbackMessage(' + feedbackid + ');'));
	fhqgui.showModalDialog(pt.render());
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
	content += '<div class="fhqbtn" onclick="deleteFeedback(' + id + ');">YES!!!</div>';
	showModalDialog(content);
};

function formEditFeedback(id)  {
	var params = {};
	params["id"] = id;
	// 	alert(createUrlFromObj(params));
	send_request_post(
		'api/feedback/get.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				FHQFeedback.type = 'error';
				var feedback = new FHQFeedback();
				feedback.show(obj.data);
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
	content += '<div class="fhqbtn" onclick="deleteFeedbackMessage(' + id + ');">YES!!!</div>';
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
				var pt = new FHQParamTable();
				pt.row('Message:', fhqgui.textedit('editfeedbackmessage_text', obj.data.text));
				pt.right(fhqgui.btn('Save', 'saveFeedbackMessage(' + id + ');'));
				fhqgui.showModalDialog(pt.render());
			} else {
				alert(obj.error.message);
			}
		}
	);
}
