

function resetStatisticsPage() {
	fhqgui.filter.stats.page = 0;
}

function setStatisticsPage(val) {
	fhqgui.filter.stats.page = val;
}

function createPageStatistics(gameid) {
	fhqgui.setFilter('stats');
	var cp = new FHQContentPage();
	cp.clear();
	cp.append('Found: <font id="statistics_found">0</font><hr/>'
		+ '<div id="listStatistics"></div>');
}

function updateStatistics() {

	var ls = document.getElementById("listStatistics");
	ls.innerHTML = "Loading...";
	
	var params = fhqgui.filter.stats.getParams();
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
	content += 'Found: <font id="answerlist_found">0</font><br>';
	content += '</div><hr>'; // fhqparamtbl
	content += '<div id="answerList"></div>';
	cp.innerHTML = content;
}

function resetPageAnswerList() {
	fhqgui.filter.answerlist.page = 0;
}

function setPageAnswerList(val) {
	fhqgui.filter.answerlist.page = val;
}

function updateAnswerList() {

	var al = document.getElementById("answerList");
	fhqgui.closeModalDialog();

	al.innerHTML = "Loading...";

	fhq.statistics.answerlist(
		fhqgui.filter.answerlist.getParams(),
		function (obj) {
			if (obj.result == "fail") {
				el.innerHTML = obj.error.message;
			} else {
				var found = parseInt(obj.data.count, 10);
				document.getElementById("answerlist_found").innerHTML = found;
				var onpage = parseInt(obj.data.onpage, 10);
				var page = parseInt(obj.data.page, 10);

				al.innerHTML = '<div id="answerlist_paging">' + fhqgui.paginator(0,found, onpage, page, 'setPageAnswerList', 'updateAnswerList') + '</div>';

				var tbl = new FHQTable();
				tbl.openrow();
				tbl.cell('Date Time / Game');
				tbl.cell('Quest');
				tbl.cell('Answer');
				tbl.cell('Passed');
				tbl.cell('User');
				tbl.closerow();

				for (var k in obj.data.answers) {
					content += '';
					if (obj.data.answers.hasOwnProperty(k)) {
						var ans = obj.data.answers[k];
						if (ans.levenshtein == 0)
							tbl.openrow('fhqrow_yellow');
						else if (ans.levenshtein > 0 && ans.levenshtein < 4)
							tbl.openrow('fhqrow_red');
						else
							tbl.openrow('');

						tbl.cell(ans.dt + ' / ' + ans.game.title);
						tbl.cell(fhqgui.questIcon(ans.quest.id, ans.quest.name, ans.quest.subject, ans.quest.score, ans.quest.solved));
						tbl.cell('Try: <br>' + (ans.passed == 'Yes' ? hatchAnswer(ans.answer_try) : ans.answer_try)
							+ '<br><br>Real: <br>' + hatchAnswer(ans.answer_real)
							+ '<br>Levenstein: ' + ans.levenshtein
						);
						tbl.cell(ans.passed);
						tbl.cell(fhqgui.userIcon(ans.user.id, ans.user.logo, ans.user.nick));
						tbl.closerow();
						
					}
				}
				content = tbl.render();
				al.innerHTML += content;
			}
		}
	);
}
