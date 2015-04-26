
function insertFeedback()
{
	var feedback = new FHQFeedback();
 	// alert(createUrlFromObj(feedback.params()));
	send_request_post(
		'api/feedback/insert.php',
		createUrlFromObj(feedback.params()),
		function (obj) {
			if (obj.result == "ok") {
				feedback.close();
				loadFeedback();
			} else {
				alert(obj.error.message);
			}
		}
	);
};

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
	content += '<div class="button3 ad" onclick="deleteFeedback(' + id + ');">YES!!!</div>';
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

function loadFeedback() {
	fhqgui.setFilter('feedback');
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
				content += '<div class="button3 ad" onclick="new FHQFeedback().show();">Create Feedback</div><br>';
				
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
