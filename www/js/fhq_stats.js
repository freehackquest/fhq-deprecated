
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
				content += '	<th width=10%>Quest</th>';
				content += '	<th width=5%>Attempts</th>';
				content += '	<th>Users who decided quest</th>';
				content += '</tr>\n';
				var bColor = false;
				
				for (var k in obj.data.quests) {
					content += '';
					if (obj.data.quests.hasOwnProperty(k)) {
						var q = obj.data.quests[k];

						content += '<tr ' + (bColor == true ? 'class="alt"' : '') + '>';
						
						content += '	<td align=center valign=top>';
						// content += '<div class="button3 ad" onclick="showQuest(' + q.id + ');">';
						content += '<div class="button3 ad">';
						content += q.id + ' ' + q.name + '<br>';
						content += ' <font size=3> ' + q.subject + ' ' + q.score + '</font><br>';
						content += 'Solved ' + q.solved + ' ';
						content += '	</div></td>';
						
						content += '	<td align=center>' + q.tries + '</td>';					
						content += '	<td>';
							for (var u in q.users) {
								content += ' <div class="button3 ad" onclick="showUserInfo(' + q.users[u].userid + ');">' + q.users[u].nick + '</div> ';
							}
						content += '</td>';
						content += '</tr>\n';
						bColor = !bColor;
					}
				}
				el.innerHTML = content;
			}
		}
	);
	
}
