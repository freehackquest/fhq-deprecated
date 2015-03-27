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

						content += '\n<div class="fhq_quest_info">\n';
						content += '	<div class="fhq_quest_info_row">\n';
						content += '		<div class="fhq_quest_info_cell_img"><img src="' + imgpath + '" width="100px"></div>\n';
						content += '		<div class="fhq_quest_info_cell_content">\n';
						content += '			<div class="fhq_quest_caption">' + e.type + '</div>';
						content += '			<div class="fhq_quest_score">' + e.message + '</div>';
						content += '			<div class="fhq_quest_caption">' + e.dt + '</div>';
						content += '		</div>'; // fhq_quest_info_cell_content
						content += '	</div>'; // fhq_quest_info_row
						content += '</div><br>'; // fhq_quest_info
					}
					content += '';
				}
				el.innerHTML = content;
			}
		}
	);
}
