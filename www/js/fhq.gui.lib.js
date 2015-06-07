function FHQGuiLib() {
	this.createComboBox = function(idelem, defaultvalue, arr) {
		var result = '<select id="' + idelem + '">';
		for (var k in arr) {
			result += '<option ';
			if (arr[k].value == defaultvalue)
				result += ' selected ';
			result += ' value="' + arr[k].value + '">';
			result += arr[k].caption + '</option>';
		}
		result += '</select>';
		return result;
	};
	
	this.combobox = function(idelem, defaultvalue, arr) {
		var result = '<select id="' + idelem + '">';
		for (var k in arr) {
			result += '<option ';
			if (arr[k].value == defaultvalue)
				result += ' selected ';
			result += ' value="' + arr[k].value + '">';
			result += arr[k].caption + '</option>';
		}
		result += '</select>';
		return result;
	};
	
	this.readonly = function(idelem, value) {
		return '<div id="' + idelem +'">' + value + '</div>';
	};

	this.btn = function(caption, js) {
		return '<div class="fhqbtn" onclick="' + js + '">' + caption + '</div>';
	}
	
	this.textedit = function(idelem, text) {
		return '<textarea id="' + idelem + '">' + text + '</textarea>';
	}

	this.showModalDialog = function(content) {
		// document.getElementById('modal_dialog').style.top = document.body.
		document.getElementById('modal_dialog').style.visibility = 'visible';
		document.getElementById('modal_dialog_content').innerHTML = content;
		document.documentElement.style.overflow = 'hidden';  // firefox, chrome
		document.body.scroll = "no"; // ie only
		document.onkeydown = function(evt) {
			if (evt.keyCode == 27)
				closeModalDialog();
		}	
	}

	this.closeModalDialog = function() {
		document.getElementById('modal_dialog').style.visibility = 'hidden';
		document.documentElement.style.overflow = 'auto';  // firefox, chrome
		document.body.scroll = "yes"; // ie only
		document.onkeydown = null;
		document.getElementById('modal_dialog_content').innerHTML = "";
	}
	
	this.paginator = function(min,max,onpage,page, setfuncname, updatefuncname) {
		if (max == 0) 
			return "";

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
				pagesHtml.push('<div class="fhqbtn" onclick="' + setfuncname + '(' + pagesInt[i] + '); ' + updatefuncname + '();">[' + (pagesInt[i]+1) + ']</div>');
			}
		}
		return pagesHtml.join(' ');
	}
	
	this.filter = {
		'current' : 'quests',
		'quests' : {
			'quests_open' : 0,
			'quests_in_progress' : 0,
			'quests_completed' : 0,
			'userstatus' : 'not_completed',
			'subject' : '',
			'getParams' : function() {
				// TODO
				var params = {};
				return params;
			}
		},
		'answerlist' : {
			'userid' : '',
			'user' : '',
			'gameid' : '',
			'gamename' : '',
			'questid' : '',
			'questname' : '',
			'questsubject' : '',
			'passed' : '',
			'table' : 'active',
			'onpage' : 10,
			'page' : 0,
			'getParams' : function() {
				var params = {};
				params.userid = this.userid;
				params.user = this.user;
				params.gameid = this.gameid;
				params.gamename = this.gamename;
				params.questid = this.questid;
				params.questname = this.questname;
				params.questsubject = this.questsubject;
				params.passed = this.passed;
				params.table = this.table;
				params.page = this.page;
				params.onpage = this.onpage;
				return params;
			}
		},
		'stats' : {
			'questname' : '',
			'questid' : '',
			'questsubject' : '',
			'onpage' : 10,
			'page' : 0,
			'getParams' : function() {
				var params = {};
				params.questname = this.questname;
				params.questid = this.questid;
				params.questsubject = this.questsubject;
				params.onpage = this.onpage;
				params.page = this.page;
				return params;
			}
		},
		'skills' : {
			'subject' : '',
			'user' : '',			
			'onpage' : 10,
			'page' : 0,
			'getParams' : function() {
				var params = {};
				params.subject = this.subject;
				params.user = this.user;				
				params.onpage = this.onpage;
				params.page = this.page;
				return params;
			}
		},
		'events' : {
			'search' : '',
			'type' : '',			
			'onpage' : 10,
			'page' : 0,
			'getParams' : function() {
				var params = {};
				params.search = this.search;
				params.type = this.type;				
				params.onpage = this.onpage;
				params.page = this.page;
				return params;
			}
		}
	};

	this.setFilter = function(current_filter) {
		this.filter.current = current_filter;
		if (this.filter[current_filter] == null) {
			document.getElementById('btnfilter').style.visibility = 'hidden';
		} else {
			document.getElementById('btnfilter').style.visibility = 'visible';
		}
	}
	
	this.showFilter = function() {
		var current_page = this.filter.current;
		
		var pt = new FHQParamTable();
		
		if (current_page == 'quests') {
			pt.row('Status:', fhqgui.combobox('quests_userstatus', this.filter.quests.userstatus, fhq.getQuestUserStatusFilter()));
			pt.row('Subject:', fhqgui.combobox('quests_subject', this.filter.quests.subject, fhq.getQuestTypesFilter()));
			pt.right(this.btn('Apply', 'fhqgui.applyQuestsFilter(); reloadQuests(); fhqgui.closeModalDialog();'));
		} else if (current_page == 'answerlist') {
			pt.row('User ID:', '<input type="text" id="answerlist_userid" value=""/>');
			pt.row('E-mail or Nick:', '<input type="text" id="answerlist_user" value=""/>');
			pt.row('Game ID:', '<input type="text" id="answerlist_gameid" value=""/>');
			pt.row('Game Name:', '<input type="text" id="answerlist_gamename" value=""/>');
			pt.row('Quest ID:', '<input type="text" id="answerlist_questid" value=""/>');
			pt.row('Quest Name:', '<input type="text" id="answerlist_questname" value=""/>');
			pt.row('Quest Subject:', fhqgui.combobox('answerlist_questsubject', this.filter.answerlist.questsubject, fhq.getQuestTypesFilter()));
			pt.row('Passed:', fhqgui.combobox('answerlist_passed', this.filter.answerlist.passed, fhq.getAnswerlistPassedFilter()));
			pt.row('Table:', fhqgui.combobox('answerlist_table', this.filter.answerlist.table, fhq.getAnswerlistTable()));
			pt.row('On Page:', fhqgui.combobox('answerlist_onpage', this.filter.answerlist.onpage, fhq.getOnPage()));
			pt.right(this.btn('Apply', 'fhqgui.applyAnswerListFilter(); resetPageAnswerList(); updateAnswerList(); fhqgui.closeModalDialog();'));
		} else if (current_page == 'stats') {
			pt.row('Quest Name:', '<input type="text" id="statistics_questname" value=""/>');
			pt.row('Quest ID:', '<input type="text" id="statistics_questid" value=""/>');
			pt.row('Quest Subject:', fhqgui.combobox('statistics_questsubject', this.filter.stats.questsubject, fhq.getQuestTypesFilter()));
			pt.row('On Page:', fhqgui.combobox('statistics_onpage', this.filter.stats.onpage, fhq.getOnPage()));
			pt.right(this.btn('Apply', 'fhqgui.applyStatsFilter(); resetStatisticsPage(); updateStatistics(); fhqgui.closeModalDialog();'));
		} else if (current_page == 'skills') {
			pt.row('Subject:', fhqgui.combobox('skills_subject', this.filter.skills.subject, fhq.getQuestTypesFilter()));
			pt.row('User:', '<input type="text" id="skills_user" value=""/>');
			pt.row('On Page:', fhqgui.combobox('skills_onpage', this.filter.skills.onpage, fhq.getOnPage()));
			pt.right(this.btn('Apply', 'fhqgui.applySkillsFilter(); fhqgui.resetSkillsPage(); fhqgui.updatePageSkills(); fhqgui.closeModalDialog();'));
		} else if (current_page == 'events') {
			pt.row('Search:', '<input type="text" id="events_search" value=""/>');
			pt.row('Type:', fhqgui.combobox('events_type', this.filter.events.type, fhq.getEventTypesFilter()));
			pt.row('On Page:', fhqgui.combobox('events_onpage', this.filter.events.onpage, fhq.getOnPage()));
			pt.right(this.btn('Apply', 'fhqgui.applyEventsFilter(); fhqgui.resetEventsPage(); updateEvents(); fhqgui.closeModalDialog();'));
		} else {
			pt.row('TODO', current_page);
		}

		this.showModalDialog(pt.render());

		if (current_page == 'answerlist') {
			document.getElementById('answerlist_userid').value = this.filter.answerlist.userid;
			document.getElementById('answerlist_user').value = this.filter.answerlist.user;
			document.getElementById('answerlist_gameid').value = this.filter.answerlist.gameid;
			document.getElementById('answerlist_gamename').value = this.filter.answerlist.gamename;
			document.getElementById('answerlist_questid').value = this.filter.answerlist.questid;
			document.getElementById('answerlist_questname').value = this.filter.answerlist.questname;
		} else if (current_page == 'stats') {
			document.getElementById('statistics_questname').value = this.filter.stats.questname;
			document.getElementById('statistics_questid').value = this.filter.stats.questid;
			document.getElementById('statistics_questsubject').value = this.filter.stats.questsubject;
		} else if (current_page == 'skills') {
			document.getElementById('skills_user').value = this.filter.skills.user;
		} else if (current_page == 'events') {
			document.getElementById('events_search').value = this.filter.events.search;
		}
	}

	this.applyQuestsFilter = function() {
		this.filter.quests.userstatus = document.getElementById("quests_userstatus").value;
		this.filter.quests.subject = document.getElementById('quests_subject').value;
	}
	
	this.applySkillsFilter = function() {
		this.filter.skills.user = document.getElementById("skills_user").value;
		this.filter.skills.subject = document.getElementById('skills_subject').value;
		this.filter.skills.onpage = document.getElementById('skills_onpage').value;
	}
	
	this.applyEventsFilter = function() {
		this.filter.events.search = document.getElementById('events_search').value;
		this.filter.events.type = document.getElementById("events_type").value;
		this.filter.events.onpage = document.getElementById('events_onpage').value;
	}

	this.applyAnswerListFilter = function() {
		this.filter.answerlist.userid = document.getElementById('answerlist_userid').value;
		this.filter.answerlist.user = document.getElementById('answerlist_user').value;
		this.filter.answerlist.gameid = document.getElementById('answerlist_gameid').value;
		this.filter.answerlist.gamename = document.getElementById('answerlist_gamename').value;
		this.filter.answerlist.questid = document.getElementById('answerlist_questid').value;
		this.filter.answerlist.questname = document.getElementById('answerlist_questname').value;
		this.filter.answerlist.onpage = document.getElementById('answerlist_onpage').value;
		this.filter.answerlist.table = document.getElementById('answerlist_table').value;
		this.filter.answerlist.passed = document.getElementById('answerlist_passed').value;
		this.filter.answerlist.questsubject = document.getElementById('answerlist_questsubject').value;
	}

	this.applyStatsFilter = function() {
		this.filter.stats.onpage = document.getElementById('statistics_onpage').value;	
		this.filter.stats.questname = document.getElementById('statistics_questname').value;
		this.filter.stats.questid = document.getElementById('statistics_questid').value;
		this.filter.stats.questsubject = document.getElementById('statistics_questsubject').value;
	}

	this.loadAbout = function() {
		this.setFilter('about');
		send_request_post_html('about.php', '', function(html) {
			document.getElementById('content_page').innerHTML = html;
		});
	}

	this.eventView = function(event, access) {
		var content = '';
		var imgpath = '';
		if (event.type == 'users')
			imgpath = 'images/menu/user.png';
		else if (event.type == 'quests')
			imgpath = 'images/menu/quests.png';
		else if (event.type == 'warning')
			imgpath = 'images/menu/warning.png';
		else if (event.type == 'info')
			imgpath = 'images/menu/news.png';
		else if (event.type == 'games')
			imgpath = 'images/menu/games.png';
		else
			imgpath = 'images/menu/default.png'; // default

		var marknew = '';
		if (event.marknew && event.marknew == true)
			marknew = '*** NEW!!! ***,';

		content += '\n<div class="fhq_event_info">\n';
		content += '	<div class="fhq_event_info_row">\n';
		content += '		<div class="fhq_event_info_cell_img"><img src="' + imgpath + '" width="100px"></div>\n';
		content += '		<div class="fhq_event_info_cell_content">\n';
		content += '			<div class="fhq_event_caption"> [' + marknew + event.type + ', ' + event.dt + ']</div>';
		content += '			<div class="fhq_event_score">' + event.message + '</div>';
		if (access == true) {
			content += '			<div class="fhq_event_caption">'; 
			content += '				<div class="fhqbtn" onclick="deleteConfirmEvent(' + event.id + ');">Delete</div>';
			content += '				<div class="fhqbtn" onclick="formEditEvent(' + event.id + ');">Edit</div>';
			content += '			</div>';
		}
		content += '		</div>'; // fhq_event_info_cell_content
		content += '	</div>'; // fhq_event_info_row
		content += '</div><br>'; // fhq_event_info
		return content;
	}
	
	this.questIcon = function(questid, name, subject, score, solved) {
		solved = solved == null ? "?" : solved;

		var content = '\n\n<div class="fhq_quest_info" onclick="showQuest(' + questid + ');">';
		content += '<div class="fhq_quest_info_row">\n';
		content += '<div class="fhq_quest_info_cell_img">';
		content += '<img  width="100px" src="images/quests/' + subject + '.png">';
		content += '</div>';
		
		content += '<div class="fhq_quest_info_cell_content">';
		content += '<div class="fhq_quest_caption">' + questid + ' ' + name + '</div>';
		content += '<div class="fhq_quest_score">' + subject + ' +' + score + '</div>';
		content += '<div class="fhq_quest_caption">solved: ' + solved + '</div>';
		content += '</div>';
		content += '</div></div>\n';
		return content;
	}
	
	this.userIcon = function(userid, logo, nick) {
		return '<div class="fhqbtn" onclick="showUserInfo(' + userid + ')"> <img class="fhqmiddelinner" width=25px src="' + logo + '"/> ' + nick + '</div>'
	}

	this.getUrlParameterByName = function(name) {
		name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
  
	this.processParams = function() {
		var questid = this.getUrlParameterByName("questid");
		if (questid) {
			showQuest(questid);
		};
	}

	this.openQuestInNewTab = function(questid) {
		var win = window.open('?questid=' + questid, '_blank');
		win.focus();
	}

	this.makeSystemPanel = function() {
		/*var cp = new FHQContentPage();

		var submenu = new FHQDynamicContent('submenu');
		submenu.clear();
		*/
		/*submenu.append(
			'<div class="fhq_btn_menu hint--bottom" data-hint="Settings" onclick="fhqgui.loadSettings(\'content_page\');">' 
			+ '<img class="fhq_btn_menu_img" src="images/menu/settings.png"/>'
			+ '</div><br>'
		);*/
		
		/*submenu.append(
			'<div class="fhq_btn_menu hint--bottom" data-hint="Users" onclick="alert(\'todo\');">' 
			+ '<img class="fhq_btn_menu_img" src="images/menu/users.png"/>'
			+ '</div><br>'
		);
		
		submenu.append(
			'<div class="fhq_btn_menu hint--bottom" data-hint="Answer List" onclick="alert(\'todo\');">' 
			+ '<img class="fhq_btn_menu_img" src="images/menu/answerlist.png"/>'
			+ '</div><br>'
		);
		
		submenu.append(
			'<div class="fhq_btn_menu hint--bottom" data-hint="Update DB" onclick="alert(\'todo\');">' 
			+ '<img class="fhq_btn_menu_img" src="images/menu/updates.png"/>'
			+ '</div><br>'
		);
		
		submenu.append(
			'<div class="fhq_btn_menu hint--bottom" data-hint="Dumps" onclick="alert(\'todo\');">' 
			+ '<img class="fhq_btn_menu_img" src="images/menu/dumps.png"/>'
			+ '</div><br>'
		);*/
		
		
		
		// init first menu
		fhqgui.loadSettings('content_page');
	}

	this.loadSettings = function(idelem) {
		this.setFilter('settings');
		var scp = new FHQDynamicContent(idelem);
		send_request_post(
			'api/admin/settings.php',
			'',
			function (obj) {
				if (obj.result == "fail") {
					scp.set(obj.error.message);
					return;
				}
				var pt = new FHQParamTable();
				for (var k in obj.data) {
					for (var k1 in obj.data[k]) {
						pt.row(k+'.'+k1, obj.data[k][k1]);
					}
					pt.skip();
				}
				pt.skip();
				scp.clear();
				scp.append(pt.render());
			}
		);
	}
	
	this.loadRules = function(gameid) {
		this.setFilter('rules');
		var el = document.getElementById("content_page");
		el.innerHTML = 'Loading...';
		var params = {};
		params.gameid = gameid;
		send_request_post(
			'api/games/get.php',
			createUrlFromObj(params),
			function (obj) {
				if (obj.result == "fail") {
					el.innerHTML = obj.error.message;
				} else {
					el.innerHTML = '<h1>Rules</h1><h2>' + obj.data.title + '</h2>';
					if (obj.access.edit == true) {
						el.innerHTML += '<div class="fhqbtn" onclick="fhqgui.formEditRule(' + obj.data.id + ');">Edit</div>';
					}
					el.innerHTML += '<br><div id="game_rules" class="fhqrules"></div>';
					var rules = document.getElementById("game_rules");
					rules.innerHTML = obj.data.rules;
				}
			}
		);
	}
	
	this.formEditRule = function(gameid) {
		var params = {};
		params.gameid = gameid;
		send_request_post(
			'api/games/get.php',
			createUrlFromObj(params),
			function (obj) {
				if (obj.result == "fail") {
					el.innerHTML = obj.error.message;
				} else {
					var content = '<textarea id="edit_game_rules"></textarea><br>';
					content += '<div class="fhqbtn" onclick="fhqgui.saveGameRule(' + gameid + ');">Save</div>';
					
					this.showModalDialog(content);
					document.getElementById('edit_game_rules').innerHTML = obj.data.rules;
				}
			}
		);
	}
	
	this.saveGameRule = function(gameid) {
		var params = {};
		params.id = gameid;
		params.rules = document.getElementById('edit_game_rules').value;
		send_request_post(
			'api/games/update_rules.php',
			createUrlFromObj(params),
			function (obj) {
				if (obj.result == "fail") {
					alert(obj.error.message);
				} else {
					fhqgui.closeModalDialog();
					fhqgui.loadRules(gameid);
				}
			}
		);
	}
	
	this.gameView = function(game, currentGameId) {
		var content = '';
		content += '\n<div class="fhq_event_info">\n';
		content += '	<div class="fhq_event_info_row">\n';
		content += '		<div class="fhq_event_info_cell_img"><img src="' + game.logo + '" width="100px"></div>\n';
		content += '		<div class="fhq_event_info_cell_content">\n';
		content += '			<div class="fhq_event_caption"> [' + game.type_game + ', ' + game.state + ', ' + game.form + ', ' + ' by <b>{' + game.organizators + '}</b></div>';
		content += '			<div class="fhq_event_caption"> ' + game.date_start + ' - ' + game.date_stop + ', restart: ' + game.date_restart + ']</div>';
		content += '			<div class="fhq_event_score"><b><h1>' + game.title + '</h1></b></div>';
		content += '			<div class="fhq_event_caption"><font size="5">Maximal score in this game: <b>' + game.maxscore + '</b></font></div>';
		content += '			<div class="fhq_event_score"><pre>' + game.description + '</pre></div>';
		content += '			<div class="fhq_event_caption">'; 
		var perms = game.permissions;

		if (currentGameId != game.id)
			content += '<div class="fhqbtn" onclick="chooseGame(' + game.id + ');">Choose</div> ';
		else
			content += 'Current Game';
		
		if (perms['delete'] == true)
			content += '<div class="fhqbtn" onclick="formDeleteGame(' + game.id + ');">Delete</div>';
			
		if (perms['update'] == true)
			content += '<div class="fhqbtn" onclick="formEditGame(' + game.id + ');">Edit</div>';
		
		content += '			</div>';
		content += '		</div>'; // fhq_event_info_cell_content
		content += '	</div>'; // fhq_event_info_row
		content += '</div><br>'; // fhq_event_info
		return content;
	}
	
	this.createPageDumps = function() {
		this.setFilter('dumps');
		alert('todo');
	}
	
	
	this.resetEventsPage = function() {
		this.filter.events.page = 0;
	}

	this.setEventsPage = function(val) {
		this.filter.events.page = val;
	}
	
	this.resetSkillsPage = function() {
		this.filter.skills.page = 0;
	}
	
	this.setSkillsPage = function(p) {
		this.filter.skills.page = p;
	}
	
	this.createPageSkills = function() {
		this.setFilter('skills');
		var el = document.getElementById("content_page");
		el.innerHTML = '<h1>User\'s Skills</h1>Found:<font id="skills_found">0</font><hr><div id="skills_page"></div>';
	}
	
	this.updatePageSkills = function() {
		var el = document.getElementById("skills_page");
		el.innerHTML = 'Loading...';
		
		var filter = createUrlFromObj(this.filter.skills.getParams());
		
		send_request_post(
			'api/statistics/skills.php',
			filter,
			function (obj) {
				if (obj.result == "fail") {
					el.innerHTML = obj.error.message;
					alert(obj.error.message);

				} else {
					document.getElementById('skills_found').innerHTML = obj.data.found;
					var onpage = parseInt(obj.data.onpage, 10);
					var page = parseInt(obj.data.page, 10);
					el.innerHTML = fhqgui.paginator(0, obj.data.found, onpage, page, 'fhqgui.setSkillsPage', 'fhqgui.updatePageSkills');

					var tbl = new FHQTable();
					tbl.openrow();
					tbl.cell('User');
					tbl.cell('Skills');
					tbl.closerow();
					
					for (var userid in obj.data.skills) {
						var sk = obj.data.skills[userid];
						tbl.openrow();
						var u = sk.user;
						tbl.cell(fhqgui.userIcon(u.userid, u.logo, u.nick));
						var h = fhqgui.filter.skills.subject == '' ? 170 : 25;
						tbl.cell('<canvas id="skill' + u.userid + '" width="450" height="' + h + '"></canvas><br>');
						tbl.closerow();
					}
					el.innerHTML += '<br>' + tbl.render();

					// update charts
					for (var userid in obj.data.skills) {
						var sk = obj.data.skills[userid];
						var u = sk.user;
						var chartid = 'skill' + u.userid;
						var ctx = document.getElementById(chartid).getContext("2d");
						ctx.font = "12px Arial";
						ctx.fillStyle = "#CCC";
						ctx.strokeStyle = "#CCC";
						
						// ctx.strokeRect(0,0,300,140);
						
						var y = 10;
						for (var sub in sk.subjects) {
							// data.labels.push(sub);
							var max = sk.subjects[sub].max;
							var score = sk.subjects[sub].score;
							var percent = 0;
							if (max != 0) {
								percent = Math.round((score/max)*100);
							}
							ctx.fillText(sub, 10, y);
							ctx.fillText('' + percent + '% ', 80, y);
							ctx.strokeRect(120, y-9, 200, 8);
							ctx.fillRect (120, y-9, percent*2, 9);
							y += 12;
						}
					}
				}
			}
		);
	}
};

function FHQParamTable() {
	this.paramtable = [];
	this.row = function(name,param) {
		this.paramtable.push( '\n'
			+ '\t<div class="fhqparamtbl_row">\n'
			+ '\t\t<div class="fhqparamtbl_param">' + name + '</div>\n'
			+ '\t\t<div class="fhqparamtbl_value">' + param + '</div>\n'
			+ '\t</div>\n'
		);
	};
	this.rowid = function(id,name,param) {
		this.paramtable.push( '\n'
			+ '\t<div id="' + id + '" class="fhqparamtbl_row">\n'
			+ '\t\t<div class="fhqparamtbl_param">' + name + '</div>\n'
			+ '\t\t<div class="fhqparamtbl_value">' + param + '</div>\n'
			+ '\t</div>\n'
		);
	};
	this.right = function(param) {
		this.paramtable.push( '\n'
			+ '\t<div class="fhqparamtbl_row">\n'
			+ '\t\t<div class="fhqparamtbl_param"></div>\n'
			+ '\t\t<div class="fhqparamtbl_value">' + param + '</div>\n'
			+ '\t</div>\n'
		);
	};
	this.left = function(name) {
		this.paramtable.push( '\n'
			+ '\t<div class="fhqparamtbl_row">\n'
			+ '\t\t<div class="fhqparamtbl_param">' + name + '</div>\n'
			+ '\t\t<div class="fhqparamtbl_value"></div>\n'
			+ '\t</div>\n'
		);
	};

	this.skip = function() {
		this.paramtable.push(
			'<div class="fhqparamtbl_rowskip"></div>'
		);
	};
	this.render = function() {
		var result = '\n';
		result += '<div class="fhqinfo">\n';
		result += '<div class="fhqparamtbl">\n';
		result += this.paramtable.join('\n');
		result += '</div>\n';
		result += '</div>\n';
		return result;
	};
}

// work with content page
function FHQContentPage() {
	this.cp = document.getElementById('content_page');
	if (this.cp)
		this.cp.innerHTML = 'Loading...';
	else
		throw 'Not found content_page';

	this.append = function(str) {
		this.cp.innerHTML += str;
	};
	this.clear = function() {
		this.cp.innerHTML = '';
	};
}

// work with content page
function FHQDynamicContent(idelem) {
	this.cp = document.getElementById(idelem);
	if (this.cp)
		this.cp.innerHTML = 'Loading...';
	else
		throw 'Not found ' + idelem;

	this.append = function(str) {
		this.cp.innerHTML += str;
	};
	this.set = function(str) {
		this.cp.innerHTML = str;
	};
	this.clear = function() {
		this.cp.innerHTML = '';
	};
}

function FHQFeedback() {
	this.type = '';
	this.text = '';
	this.id = null;
	this.show = function(obj) {
		this.id = obj ? obj.id : null;
		this.type = obj ? obj.type : '';
		this.text = obj ? obj.text : '';

		var pt = new FHQParamTable();
		if (this.id != null)
			pt.row('ID:', fhqgui.readonly('editfeedback_id', this.id));
		pt.row('Type:', fhqgui.combobox('editfeedback_type', this.type, fhq.getFeedbackTypes()));
		pt.row('Message:', fhqgui.textedit('editfeedback_text', this.text));
		if (this.id == null)
			pt.right(fhqgui.btn('Create', 'insertFeedback();'));
		else
			pt.right(fhqgui.btn('Save', 'saveFeedback();'));
		fhqgui.showModalDialog(pt.render());
	};
	
	this.params = function() {
		var params = {};
		if (document.getElementById("editfeedback_id"))
			params.id = document.getElementById("editfeedback_id").innerHTML;
		params.text = document.getElementById("editfeedback_text").value;
		params.type = document.getElementById("editfeedback_type").value;
		return params;
	};

	this.close = function() {
		fhqgui.closeModalDialog();
	};
	
	this.save = function() {
		alert('feedback save nothing');
	};
	
	this.create = function() {
		alert('feedback create nothing');
	};
	
	this.delete = function() {
		alert('feedback create nothing');
	};
};

function FHQTable() {
	this.table = [];

	this.openrow = function(stl) {
		if (stl == null)
			stl = '';
		this.table.push(
			'<div class="fhqrow ' + stl + '">'
		);
	};
	
	this.closerow = function() {
		this.table.push(
			'</div>'
		);
	};
	
	this.cell = function(text) {
		this.table.push(
			'<div class="fhqcell">' + text + '</div>'
		);
	};

	this.render = function() {
		var result = '\n';
		result += '<div class="fhqtable">\n';
		result += this.table.join('\n');
		result += '</div>\n <!-- fhqtable -->';
		return result;
	};
}
