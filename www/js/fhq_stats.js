
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
				var content = '';
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
						tbl.cell('<a target="blank_" href="?subject=' + ans.quest.subject + '">' + ans.quest.subject + '</a>'+ ' / ' 
							+ '<a target="blank_" href="?quest=' + ans.quest.id + '">Quest ' + ans.quest.id + '</a><br>' + ans.quest.name + ' ' + '  (+' + ans.quest.score + ') ');
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
