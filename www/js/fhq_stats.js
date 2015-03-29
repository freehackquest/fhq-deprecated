
function loadStatistics(gameid) {
	var params = {};
	params.gameid = gameid;
	var el = document.getElementById("content_page");
	el.innerHTML = "Loading...";
	
	send_request_post(
		'api/statistics/list.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				el.innerHTML = obj.error.message;
			} else {
				var content = '<table id="customers">';
				content += '<tr class="alt">';
				content += '	<th width=10%>Subject</th>';
				content += '	<th width=10%>Quest-Id, Name)</th>';
				content += '	<th width=10%>Score(Min Score)</th>';
				content += '	<th width=10%>Tries</th>';
				content += '	<th width=10%>Solved</th>';
				content += '	<th>Users who passed</th>';
				content += '</tr>\n';
			
				/*if (obj.access == true)
					content += '<div class="button3 ad" onclick="formCreateEvent();">Create News</div><br>';*/
				var bColor = false;
				
				for (var k in obj.data.quests) {
					content += '';
					if (obj.data.quests.hasOwnProperty(k)) {
						var q = obj.data.quests[k];

						content += '<tr ' + (bColor == true ? 'class="alt"' : '') + '>';
						// content += '	<td><div class="button3 ad" onclick="showQuest(' + q.id + ');"> #' + q.id + ', ' + q.name + ', ' + q.subject + '</div></td>';
						content += '	<td>' + q.subject + '</td>';
						content += '	<td>#' + q.id + ', ' + q.name + '</td>';
						content += '	<td>+' + q.score + ' (>=' + q.min_score + ')</td>';
						content += '	<td>' + q.tries + '</td>';
						content += '	<td>' + q.solved + '</td>';
						
						content += '	<td>'
							for (var u in q.users) {
								content += ' <div class="button3 ad" onclick="showUserInfo(' + q.users[u].userid + ');">' + q.users[u].nick + '</div> ';
							}
						content += '</td>';
						content += '</tr>\n';
						bColor = !bColor;
						
				/*<td><a href=main.php?content_page=view_quest&id='.$idquest.'>'.$idquest.', '.$name.'</a></td>
				<td>+'.$score.' ( >='.$min_score.') </td>
				<td>'.$plus.'</td>
				<td>'.$minus.'</td>
				<td>'.$users.'</td>
			</tr>';
			
			
			
						id": "47",
"name": "trivia",
"subject": "trivia",
"min_score": "0",
"score": "111",
"solved": 2,
"tries": 0,*/
						/*
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
						content += '</div><br>'; // fhq_event_info*/
					}
					// content += '';
				}
				el.innerHTML = content;
			}
		}
	);
	
}
