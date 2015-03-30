
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
				
				var content = '';
				content += 'Processed ' + obj.lead_time_sec + ' sec <br>';
				content += '<table id="customers">';
				content += '<tr class="alt">';
				content += '	<th width=10%>Quest</th>';
				content += '	<th width=5%>Attempts</th>';
				content += '	<th>Users who solved quest</th>';
				content += '</tr>\n';
				var bColor = false;
				
				for (var k in obj.data.quests) {
					content += '';
					if (obj.data.quests.hasOwnProperty(k)) {
						var q = obj.data.quests[k];

						content += '<tr ' + (bColor == true ? 'class="alt"' : '') + '>';
						
						content += '	<td align=center valign=top>';
						content += '<div class="button3 ad" onclick="showQuest(' + q.id + ');">';
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

function getHTMLPaging2(min,max,onpage,page, table) {
	if (min == max || page > max || page < min )
		return " Paging Error ";
	
	var pages = Math.ceil(max / onpage);

	var pagesInt = [];
	var leftp = 5;
	var rightp = leftp + 1;

	if (pages > (leftp + rightp + 2)) {
		pagesInt.push(min);
		if (page - leftp > min + 1) {
			pagesInt.push(-1);
			for (var i = (page - leftp); i <= page; i++) {
				pagesInt.push(i);
			}
		} else {
			for (var i = min+1; i <= page; i++) {
				pagesInt.push(i);
			}
		}
		
		if (page + rightp < pages-1) {
			for (var i = page+1; i < (page + rightp); i++) {
				pagesInt.push(i);
			}
			pagesInt.push(-1);
		} else {
			for (var i = page+1; i < pages-1; i++) {
				pagesInt.push(i);
			}
		}
		if (page != pages-1)
			pagesInt.push(pages-1);
	} else {
		for (var i = 0; i < pages; i++) {
			pagesInt.push(i);
		}
	}

	var pagesHtml = [];
	for (var i = 0; i < pagesInt.length; i++) {
		if (pagesInt[i] == -1) {
			pagesHtml.push("...");
		} else if (pagesInt[i] == page) {
			pagesHtml.push('<div class="selected_user_page">[' + (pagesInt[i]+1) + ']</div>');
		} else {
			pagesHtml.push('<div class="button3 ad" onclick="loadAnswerList(null, ' + pagesInt[i] + ', ' + onpage + ', \'' + table + '\'); updateUsers();">[' + (pagesInt[i]+1) + ']</div>');
		}
	}

	return pagesHtml.join(' ');
}

function hatchAnswer(answer) {
	hatch = "";
	for (var i = 0; i < answer.length; i++) {
		hatch += "*";
	}
	return '<div answer="' + answer + '" hatch="' + hatch + '" onmouseover="this.innerHTML=this.getAttribute(\'answer\');" onmouseout="this.innerHTML=this.getAttribute(\'hatch\');">' + hatch + "</div>";
}


function loadAnswerList(gameid, page, onpage, table) {
	
	if (page == null)
		page = 0;
		
	if (onpage == null)
		onpage = 10;
	
	if (table == null)
		table = 'active';
		
	var params = {};
	// params.gameid = gameid;
	params.page = page;
	params.onpage = onpage;
	params.table = table;

	var el = document.getElementById("content_page");
	el.innerHTML = "Loading...";

	send_request_post(
		'api/statistics/answerlist.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				el.innerHTML = obj.error.message;
			} else {
				var content = '';
				
				content += '<div class="button3 ad" onclick="loadAnswerList(' + gameid + ', 0, ' + onpage + ', \'active\');">Active</div>';
				content += '<div class="button3 ad" onclick="loadAnswerList(' + gameid + ', 0, ' + onpage + ', \'backup\');">Backup</div>';
				content += '<br>';
				content += 'Found: ' + obj.data.count + '<br>';
				content += getHTMLPaging2(0, obj.data.count, obj.data.onpage, obj.data.page, obj.data.table);
				content += '<table id="customers">';
				content += '<tr class="alt">';
				content += '	<th width=10%>Date Time</th>';
				content += '	<th width=10%>Game</th>';
				content += '	<th>Quest</th>';
				content += '	<th>Answer Try</th>';
				content += '	<th>Answer Real</th>';
				content += '	<th>Passed</th>';
				content += '	<th>User</th>';
				content += '</tr>\n';
				var bColor = false;
				
				for (var k in obj.data.answers) {
					content += '';
					if (obj.data.answers.hasOwnProperty(k)) {
						var ans = obj.data.answers[k];

						// style
						content += '<tr ';
						if (ans.passed == 'Yes')
							content += 'class="alt2"';
						else if (bColor == true)
							content += 'class="alt"';
						else
							content += '';
						content += '>';

						content += '	<td align=center>' + ans.datetime_try + '</td>';
						content += '	<td align=center>' + ans.gametitle + '</td>';

						content += '	<td align=center valign=top>';
						content += '<div class="button3 ad" onclick="showQuest(' + ans.questid + ');">';
						content += ans.questid + ' ' + ans.questname + '<br>';
						content += ' <font size=3> ' + ans.questsubject + ' ' + ans.questscore + '</font><br>';
						content += 'Solved ' + ans.questsolved + ' ';
						content += '	</div></td>';
						
						content += '	<td align=center>' + (ans.passed == 'Yes' ? hatchAnswer(ans.answer_try) : ans.answer_try) + '</td>';
						content += '	<td align=center>' + hatchAnswer(ans.answer_real) + '</td>';
						content += '	<td align=center>' + ans.passed + '</td>';
						content += '	<td align=center><div class="button3 ad" onclick="showUserInfo(' + ans.userid + ');">' + ans.userid + ', ' + ans.usernick + ', ' + ans.username + ' </div></td>';
						
						
						
						/*
						// quest
						content += '	<td align=center valign=top>';
						content += '<div class="button3 ad" onclick="showQuest(' + q.id + ');">';
						content += q.id + ' ' + q.name + '<br>';
						content += ' <font size=3> ' + q.subject + ' ' + q.score + '</font><br>';
						content += 'Solved ' + q.solved + ' ';
						content += '	</div></td>';
						
						content += '	<td align=center>' + q.tries + '</td>';
						content += '	<td>';
							for (var u in q.users) {
								content += ' <div class="button3 ad" onclick="showUserInfo(' + q.users[u].userid + ');">' + q.users[u].nick + '</div> ';
							}
						content += '</td>';*/
						content += '</tr>\n';
						bColor = !bColor;
					}
				}
				el.innerHTML = content;
			}
		}
	);
	
}
