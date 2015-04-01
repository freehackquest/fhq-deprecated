
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

// this same function to getHTMLPaging1
function getHTMLPaging2(min,max,onpage,page) {
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
			pagesHtml.push('<div class="button3 ad" onclick="setPageAnswerList(' + pagesInt[i] + '); updateAnswerList();">[' + (pagesInt[i]+1) + ']</div>');
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

function createPageAnswerList() {
	var cp = document.getElementById('content_page');
	cp.innerHTML = '';

	var content = '';
	var onkeydown_ = 'onkeydown="if (event.keyCode == 13) {resetPageAnswerList(); updateAnswerList();};"';
	
	var pt = new FHQParamTable();
	pt.row('UserID:', '<input type="text" id="answerlist_userid" value="" ' + onkeydown_ + '/>');
	pt.row('E-mail or Nick:', '<input type="text" id="answerlist_user" value="" ' + onkeydown_ + '/>');
	pt.row('GameID:', '<input type="text" id="answerlist_gameid" value="" ' + onkeydown_ + '/>');
	pt.row('Game Name:', '<input type="text" id="answerlist_gamename" value="" ' + onkeydown_ + '/>');
	pt.row('Quest ID:', '<input type="text" id="answerlist_questid" value="" ' + onkeydown_ + '/>');
	pt.row('Quest Name:', '<input type="text" id="answerlist_questname" value="" ' + onkeydown_ + '/>');
	pt.row('Quest Subject:', fhqgui.combobox('answerlist_questsubject', '', fhq.getQuestTypesFilter()));
	pt.row('Passed:', fhqgui.combobox('answerlist_passed', '', fhq.getAnswerlistPassedFilter()));
	pt.row('Table:', fhqgui.combobox('answerlist_table', 'active', fhq.getAnswerlistTable()));
	pt.row('On Page:', fhqgui.combobox('answerlist_onpage', '10', fhq.getOnPage()));
	pt.row('', '<div class="button3 ad" onclick="resetPageAnswerList(); updateAnswerList();">Update</div>');
	pt.skip();
	pt.row('Found:', '<font id="answerlist_found">0</font>');
	pt.skip();
	content += pt.render();
	content += '</div><hr>'; // fhqparamtbl
	content += '<input type="hidden" id="answerlist_page" value="0"/>'	
	content += '<div id="answerList"></div>';
	cp.innerHTML = content;
}

function resetPageAnswerList() {
	document.getElementById('answerlist_page').value = 0;
}

function setPageAnswerList(val) {
	document.getElementById('answerlist_page').value = val;
}

function updateAnswerList() {

	var al = document.getElementById("answerList");
	al.innerHTML = "Loading...";
	
	var params = {};
	params.userid = document.getElementById('answerlist_userid').value;
	params.username = document.getElementById('answerlist_user').value;
	params.gameid = document.getElementById('answerlist_gameid').value;
	params.gamename = document.getElementById('answerlist_gamename').value;
	params.questid = document.getElementById('answerlist_questid').value;
	params.questname = document.getElementById('answerlist_questname').value;
	params.questsubject = document.getElementById('answerlist_questsubject').value;
	params.passed = document.getElementById('answerlist_passed').value;
	params.table = document.getElementById('answerlist_table').value;
	params.page = document.getElementById('answerlist_page').value;
	params.onpage = document.getElementById('answerlist_onpage').value;
	send_request_post(
		'api/statistics/answerlist.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				el.innerHTML = obj.error.message;
			} else {
				var found = parseInt(obj.data.count, 10);
				document.getElementById("answerlist_found").innerHTML = found;
				var onpage = parseInt(obj.data.onpage, 10);
				var page = parseInt(obj.data.page, 10);
				var table = parseInt(obj.data.table, 10);
				al.innerHTML = '<div id="answerlist_paging">' + getHTMLPaging2(0,found, onpage, page) + '</div>';

				var content = '';
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
						content += '</tr>\n';
						bColor = !bColor;
					}
				}
				al.innerHTML += content;
			}
		}
	);
	
}
