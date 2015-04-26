

function resetStatisticsPage() {
	document.getElementById('statistics_page').value = 0;
}

function setStatisticsPage(val) {
	document.getElementById('statistics_page').value = val;
}

function createPageStatistics(gameid) {
	fhqgui.setFilter('stats');
	var pt = new FHQParamTable();
	pt.row('Quest Name:', '<input type="text" id="statistics_questname" value="" onkeydown="if (event.keyCode == 13) {resetStatisticsPage(' + gameid + '); updateStatistics();};"/>');
	pt.row('Quest ID:', '<input type="text" id="statistics_questid" value="" onkeydown="if (event.keyCode == 13) {resetStatisticsPage(' + gameid + '); updateStatistics();};"/>');
	pt.row('Quest Subject:', fhqgui.combobox('statistics_questsubject', '', fhq.getQuestTypesFilter()));
	pt.row('On Page:', fhqgui.combobox('statistics_onpage', '5', fhq.getOnPage()));
	pt.row('', fhqgui.btn('Search', 'resetStatisticsPage(' + gameid + '); updateStatistics();'));
	pt.skip();
	pt.row('Found:', '<font id="statistics_found">0</font>');
	var cp = new FHQContentPage();
	cp.clear();
	cp.append(pt.render());
	cp.append('<input type="hidden" id="statistics_page" value="0"/>'
		+ '<input type="hidden" id="statistics_gameid" value="' + gameid + '"/>'
		+ '<div id="error_search"></div>'
		+ '<hr/>'
		+ '<div id="listStatistics"></div>');
}

function updateStatistics() {

	var ls = document.getElementById("listStatistics");
	ls.innerHTML = "Loading...";
	
	var params = {};
	params.gameid = document.getElementById('statistics_gameid').value;
	params.questname = document.getElementById('statistics_questname').value;
	params.questid = document.getElementById('statistics_questid').value;
	params.questsubject = document.getElementById('statistics_questsubject').value;
	params.page = document.getElementById('statistics_page').value;
	params.onpage = document.getElementById('statistics_onpage').value;

	send_request_post(
		'api/statistics/list.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				ls.innerHTML = obj.error.message;
			} else {
				
				var found = parseInt(obj.data.count, 10);
				document.getElementById("statistics_found").innerHTML = found;
				var onpage = parseInt(obj.data.onpage, 10);
				var page = parseInt(obj.data.page, 10);
				
				var content = '';
				// content += 'Processed ' + obj.lead_time_sec + ' sec <br>';
				content += fhqgui.paginator(0, found, onpage, page, 'setStatisticsPage', 'updateStatistics');
				var tbl = new FHQTable();
				tbl.openrow();
				tbl.cell('Quest');
				tbl.cell('Chart');
				tbl.cell('Users who solved quest');
				tbl.closerow();
				for (var k in obj.data.quests) {
					if (obj.data.quests.hasOwnProperty(k)) {
						var q = obj.data.quests[k];
						tbl.openrow();
						tbl.cell(fhqgui.questIcon(q.id, q.name, q.subject, q.score, q.solved));
						tbl.cell('<canvas id="questChart' + q.id + '" width="300" height="200"></canvas>');
						var usrs = [];
						for (var u in q.users) {
							usrs.push(fhqgui.userIcon(q.users[u].userid, q.users[u].logo, q.users[u].nick));
						}
						tbl.cell(usrs.join(" "));
						tbl.closerow();
					}
				}
				content += tbl.render();

				ls.innerHTML = content;
				
				var options = {
					segmentShowStroke : true,
					segmentStrokeColor : "#606060",
					segmentStrokeWidth : 1,
					percentageInnerCutout : 35, // This is 0 for Pie charts
					animationSteps : 100,
					animationEasing : "easeOutBounce",
					animateRotate : false,
					animateScale : false,
					legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
				};
				
				// update charts
				for (var k in obj.data.quests) {
					if (obj.data.quests.hasOwnProperty(k)) {
						var q = obj.data.quests[k];
						var chartid = 'questChart' + q.id;
						var data = [
							{
								value: q.solved,
								color: "#9f9f9f",
								highlight: "#606060",
								label: "Solved"
							},
							{
								value: q.tries_solved,
								color: "#9f9f9f",
								highlight: "#606060",
								label: "Tried (who solved)"
							},
							{
								value: q.tries_nosolved,
								color:"#9f9f9f",
								highlight: "#606060",
								label: "Tried (who didn't solve)"
							}
						];
						var ctx = document.getElementById(chartid).getContext("2d");
						var myNewChart = new Chart(ctx).Doughnut(data, options);
					}
				}
			}
		}
	);	
}

function hatchAnswer(answer) {
	hatch = "";
	for (var i = 0; i < answer.length; i++) {
		hatch += "*";
	}
	return '<div answer="' + answer + '" hatch="' + hatch + '" onmouseover="this.innerHTML=this.getAttribute(\'answer\');" onmouseout="this.innerHTML=this.getAttribute(\'hatch\');">' + hatch + "</div>";
}

function createPageAnswerList() {
	fhqgui.setFilter('answerlist');
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
	params.user = document.getElementById('answerlist_user').value;
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

				al.innerHTML = '<div id="answerlist_paging">' + fhqgui.paginator(0,found, onpage, page, 'setPageAnswerList', 'updateAnswerList') + '</div>';

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
						content += '	<td align=center><div class="button3 ad" onclick="showUserInfo(' + ans.userid + ');">' + ans.userid + ', ' + ans.usernick + ', ' + ans.email + ' </div></td>';
						content += '</tr>\n';
						bColor = !bColor;
					}
				}
				al.innerHTML += content;
			}
		}
	);
	
}
