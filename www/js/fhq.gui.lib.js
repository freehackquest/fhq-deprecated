function FHQGuiLib(api) {
	var self = this;
	this.fhq = api;
	this.api = api;
	
	this.lang = function(){
		return this.sLang || this.locale();
	};
	
	this.locale = function() {
		var langs = ['en', 'ru']
		self.sLang = 'en';
		if(this.containsPageParam('lang') && langs.indexOf(this.pageParams['lang']) >= -1){
			this.sLang = this.pageParams['lang'];
		} else if (navigator) {
			var navLang = 'en';
			navLang = navigator.language ? navigator.language.substring(0,2) : navLang;
			navLang = navigator.browserLanguage ? navigator.browserLanguage.substring(0,2) : navLang;
			navLang = navigator.systemLanguage ? navigator.systemLanguage.substring(0,2) : navLang;
			navLang = navigator.userLanguage ? navigator.userLanguage.substring(0,2) : navLang;
			this.sLang =  langs.indexOf(navLang) >= -1 ? navLang : self.sLang;
		} else {
			this.sLang = 'en';
		}
		return this.sLang;
	};
	
	this.t = function(message){
		if(FHQLocalization[message]){
			return FHQLocalization[message][this.lang()];
		}else{
			console.warn("Not found localization for '" + message + "'");
		}
		return message;
	}
	
	this.parsePageParams = function() {
		var loc = location.search.slice(1);
		var arr = loc.split("&");
		var result = {};
		var regex = new RegExp("(.*)=([^&#]*)");
		for(var i = 0; i < arr.length; i++){
			if(arr[i].trim() != ""){
				p = regex.exec(arr[i].trim());
				console.log("results: " + JSON.stringify(p));
				if(p == null)
					result[decodeURIComponent(arr[i].trim().replace(/\+/g, " "))] = '';
				else
					result[decodeURIComponent(p[1].replace(/\+/g, " "))] = decodeURIComponent(p[2].replace(/\+/g, " "));
			}
		}
		console.log(JSON.stringify(result));
		return result;
	}
	
	this.pageParams = this.parsePageParams();
	
	this.containsPageParam = function(name){
		return (typeof this.pageParams[name] !== "undefined");
	}
	
	// include dark style
	if(this.containsPageParam("dark")){
		var link  = document.createElement('link');
		link.rel  = 'stylesheet';
		link.type = 'text/css';
		link.href = 'templates/dark/styles/colors.css';
		link.media = 'all';
		document.head.appendChild(link);
	};

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

	/* Old Modal Dialog */
	
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
	
	/* FHQModalDialog */
	
	this.showFHQModalDialog = function(obj) {
		// document.getElementById('modal_dialog').style.top = document.body.
		document.getElementById('fhqmodaldialog').style.visibility = 'visible';
		$('#fhqmodaldialog_header').html(obj.header);
		$('#fhqmodaldialog_content').html(obj.content);
		$('#fhqmodaldialog_buttons').html(obj.buttons + $('#fhqmodaldialog_btncancel').html());
		
		document.documentElement.style.overflow = 'hidden';  // firefox, chrome
		document.body.scroll = "no"; // ie only
		this.modalDialog2ClickContent = false;
		document.onkeydown = function(evt) {
			if (evt.keyCode == 27)
				fhqgui.closeFHQModalDialog();
		}
	}

	this.updateFHQModalDialog = function(obj) {
		$('#fhqmodaldialog_header').html(obj.header);
		$('#fhqmodaldialog_content').html(obj.content);
		$('#fhqmodaldialog_buttons').html(obj.buttons + $('#fhqmodaldialog_btncancel').html());
	}

	this.clickFHQModalDialog_content = function() {
		this.FHQModalDialog_ClickContent = true;
	}
	
	this.clickFHQModalDialog_dialog = function() {
		if(this.FHQModalDialog_ClickContent != true){
			this.closeFHQModalDialog();
		}else{
			this.FHQModalDialog_ClickContent = false;
		}
	}

	this.closeFHQModalDialog = function() {
		document.getElementById('fhqmodaldialog').style.visibility = 'hidden';
		document.documentElement.style.overflow = 'auto';  // firefox, chrome
		document.body.scroll = "yes"; // ie only
		document.onkeydown = null;
		$('#fhqmodaldialog_content').html("");
	}
	
	/* top menu */
	
	this.loadTopPanel = function(){
		var toppanel = $('.fhqtopmenu_toppanel');
		toppanel.html('');
		toppanel.append('<a id="btnmenu_main_page" class="fhq_btn_menu" href="?about">'
			+ '<img class="fhq_btn_menu_img" src="images/fhq2016_200x150.png"/> '
			+ this.t('About')
			+ '</a>')
		toppanel.append('<a id="btnmenu_quests" class="fhq_btn_menu" href="?quests">'
			+ '<img class="fhq_btn_menu_img" src="images/menu/quests_40x40.png"/> '
			+ this.t('Quests')
			+ '</a>')
			
		toppanel.append('<a id="btnmenu_tools" class="fhq_btn_menu" href="?tools">'
			+ '<img class="fhq_btn_menu_img" src="images/menu/tools_150x150.png"/> '
			+ this.t('Tools')
			+ '</a>')
			
		toppanel.append('<a id="btnmenu_news" class="fhq_btn_menu" href="?news">'
			+ '<img class="fhq_btn_menu_img" src="images/menu/news.png"/>'
			+ '<div class="fhqredcircle hide" id="plus_events">0</div> '
			+ this.t('News')
			+ '</a>');

		toppanel.append('<div id="btnmenu_user" class="fhq_btn_menu" href="javascript:void(0);">'
			+ '<img class="fhq_btn_menu_img" src="images/menu/user.png"/> '
			+ this.t('Account')
			+ '<div class="accout-panel">'
			+ '<img class="fhq_btn_menu_img" src="images/menu/user.png"/> '
			+ this.t('Account')
			+ '<div class="border"></div>'
			+ '</div> '
			+ '</div>');
		
		// if(!fhq.cache.is_authorized){
			$('.accout-panel').append('<div id="btnmenu_signin" class="fhq-simple-btn" onclick="fhqgui.showSignInForm();">' + this.t('Sign-in') + '</div>');
			$('.accout-panel').append('<div id="btnmenu_signup" class="fhq-simple-btn" onclick="fhqgui.showSignUpForm();">' + this.t('Sign-up') + '</div>');
			$('.accout-panel').append('<div id="btnmenu_restore_password" class="fhq-simple-btn" onclick="fhqgui.showResetPasswordForm();">' + this.t('Forgot password?') + '</div>');
		/*}else{
			$('.accout-panel').append('<div id="btnmenu_signin" class="fhq-simple-btn" onclick="fhqgui.signout();">' + this.t('Sign-out') + '</div>');
		}*/
		
		$('#btnmenu_user').unbind().bind('click', function(e){
			e.preventDefault();
			$('.accout-panel').show();
		});
	}
	
	/* Sign In */
	
	this.showSignInForm = function() {
		this.showFHQModalDialog({
			'header' : 'Sign In',
			'content': $("#signin-form").html(),
			'buttons': $("#signin-form-buttons").html()
		});
		if(this.fhq.supportsHtml5Storage()){
			if(localStorage.getItem("email") != null){
				$("#signin-email").val(localStorage.getItem("email"));
			}else{
				$("#signin-email").val("");
			}
			if(localStorage.getItem("password") != null){
				$("#signin-password").val(localStorage.getItem("password"));
			}else{
				$("#signin-password").val("");
			}
		}
	}

	this.cleanupSignInMessages = function() {
		$('#signin-error-message').html('');
	}
	
	this.signin = function() {
		var email = $("#signin-email").val();
		var password = $("#signin-password").val();

		var obj = this.fhq.security.login(email,password);
		if (obj.result == "fail") {
			$("#signin-error-message").html(obj.error.message);
		} else {
			// TODO
			$('#signin-email').val('');
			$("#signin-password").val('');
			if(this.fhq.supportsHtml5Storage()){
				localStorage.setItem("email", email);
				localStorage.setItem("password", password);
			}
			window.location.href = "main.php";
		}
	}
	
	/* Sign Up */
	
	this.showSignUpForm = function() {
		this.showFHQModalDialog({
			'header' : 'Sign Up',
			'content': $("#signup-form").html(),
			'buttons': $("#signup-form-buttons").html()
		});
		this.refreshSignUpCaptcha();
	}

	this.refreshSignUpCaptcha = function() {
		$('#signup-captcha-image').attr('src', 'api/captcha.php?rid=' + Math.random());
	}

	this.cleanupSignUpMessages = function() {
		$('#signup-error-message').html('');
		$('#signup-info-message').html('');
	}

	this.signup = function() {
		var self = this;
		$('#signup-error-message').html('');
		$('#signup-info-message').html('Please wait...');
		var email = $('#signup-email').val();
		var captcha = $('#signup-captcha').val();

		this.fhq.security.registration(email,captcha, function(response){
			if(response.result == "fail"){
				$('#signup-error-message').html(response.error.message);
				$('#signup-info-message').html('');
			}else{
				
				$('#signup-email').val('');
				$('#signup-captcha').val('');
				$('#signup-info-message').html('');
				$('#signup-error-message').html('');
				
				self.updateFHQModalDialog({
					'header' : 'Sign Up',
					'content': response.data.message,
					'buttons': ''
				});
			}
			self.refreshSignUpCaptcha();
		});
	}
	
	/* Reset Password */

	this.showResetPasswordForm = function() {
		this.showFHQModalDialog({
			'header' : 'Reset Password',
			'content': $("#reset-password-form").html(),
			'buttons': $("#reset-password-form-buttons").html()
		});
		this.refreshResetPasswordCaptcha();
	};

	this.refreshResetPasswordCaptcha = function() {
		$('#reset-password-captcha-image').attr('src', 'api/captcha.php?rid=' + Math.random());
	}

	this.cleanupResetPasswordMessages = function() {
		$('#reset-password-info-message').html('');
		$('#reset-password-error-message').html('');
	}

	this.resetPassword = function() {
		var self = this;
		$('#reset-password-error-message').html('');
		$('#reset-password-info-message').html('Please wait...');
		var email = $('#reset-password-email').val();
		var captcha = $('#reset-password-captcha').val();

		this.fhq.security.resetPassword(email,captcha, function(response){
			if(response.result == "fail"){
				$('#reset-password-error-message').html(response.error.message);
				$('#reset-password-info-message').html('');
			}else{
				
				$('#reset-password-email').val('');
				$('#reset-password-captcha').val('');
				$('#reset-password-info-message').html('');
				$('#reset-password-error-message').html('');
				
				self.updateFHQModalDialog({
					'header' : 'Reset Password',
					'content': response.data.message,
					'buttons': ''
				});
			}
			self.refreshResetPasswordCaptcha();
		});
	};

	this.loadCities = function() {
		this.api.publicInfo(function(response){
			if (response.result == "fail") {
				$('#cities').html('Fail');
			} else {

				$('#statistics-count-quests').removeClass('preloading');
				$('#statistics-count-quests').text(response.data.quests.count);
				
				$('#statistics-all-attempts').removeClass('preloading');
				$('#statistics-all-attempts').text(response.data.quests.attempts);
				
				$('#statistics-already-solved').removeClass('preloading');
				$('#statistics-already-solved').text(response.data.quests.solved);

				var cities = [];
				for (var k in response.data.cities){
					cities.push(response.data.cities[k].city + ' (' + response.data.cities[k].cnt + ')');
				}

				$('#statistics-playing-with-us').removeClass('preloading');
				$('#statistics-playing-with-us').text(cities.join(", "));				

				var content = "";
				for (var k in response.data.winners) {
					var us = []
					var score = 0;
					for (var k1 in response.data.winners[k]) {
						if(us.length < 3){
							score = response.data.winners[k][k1].score;
							us.push(response.data.winners[k][k1].user);
						}
					}
					content += '<div class="single-line-preloader"> <div class="single-line-name">' + k + ' (+' + score + '):</div> ';
					content += '<div class="single-line-value">' + us.join(', ') + '</div>';
					content += '</div>';
				}
				$('#winners').html(content);
			}
		});
	};

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


	this.changeLocationState = function(newPageParams){
		var url = '';
		var params = [];
		console.log("changeLocationState");
		console.log("changeLocationState", newPageParams);
		for(var p in newPageParams){
			params.push(encodeURIComponent(p) + "=" + encodeURIComponent(newPageParams[p]));
		}
		console.log("changeLocationState", params);
		console.log("changeLocationState", window.location.pathname + '?' + params.join("&"));
		window.history.pushState(newPageParams, document.title, window.location.pathname + '?' + params.join("&"));
		this.pageParams = this.parsePageParams();
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
			$('#btnfilter').hide();
		} else {
			$('#btnfilter').show();
		}
	}
	
	this.showFilter = function() {
		var current_page = this.filter.current;
		
		var pt = new FHQParamTable();
		var header = '';
		var buttons = '';
		if (current_page == 'quests') {
			header = 'Filter Quests';
			pt.row('Status:', fhqgui.combobox('quests_userstatus', this.filter.quests.userstatus, fhq.getQuestUserStatusFilter()));
			pt.row('Subject:', fhqgui.combobox('quests_subject', this.filter.quests.subject, fhq.getQuestTypesFilter()));
			buttons = this.btn('Apply', 'fhqgui.applyQuestsFilter(); reloadQuests(); fhqgui.closeFHQModalDialog();');
		} else if (current_page == 'answerlist') {
			header = 'Filter Answer List';
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
			buttons = this.btn('Apply', 'fhqgui.applyAnswerListFilter(); resetPageAnswerList(); updateAnswerList(); fhqgui.closeFHQModalDialog();');
		} else if (current_page == 'stats') {
			header = 'Filter Statistics';
			pt.row('Quest Name:', '<input type="text" id="statistics_questname" value=""/>');
			pt.row('Quest ID:', '<input type="text" id="statistics_questid" value=""/>');
			pt.row('Quest Subject:', fhqgui.combobox('statistics_questsubject', this.filter.stats.questsubject, fhq.getQuestTypesFilter()));
			pt.row('On Page:', fhqgui.combobox('statistics_onpage', this.filter.stats.onpage, fhq.getOnPage()));
			buttons = this.btn('Apply', 'fhqgui.applyStatsFilter(); resetStatisticsPage(); updateStatistics(); fhqgui.closeFHQModalDialog();');
		} else if (current_page == 'skills') {
			header = 'Filter Skills';
			pt.row('Subject:', fhqgui.combobox('skills_subject', this.filter.skills.subject, fhq.getQuestTypesFilter()));
			pt.row('User:', '<input type="text" id="skills_user" value=""/>');
			pt.row('On Page:', fhqgui.combobox('skills_onpage', this.filter.skills.onpage, fhq.getOnPage()));
			buttons = this.btn('Apply', 'fhqgui.applySkillsFilter(); fhqgui.resetSkillsPage(); fhqgui.updatePageSkills(); fhqgui.closeFHQModalDialog();');
		} else if (current_page == 'events') {
			header = 'Filter News';
			pt.row('Search:', '<input type="text" id="events_search" value=""/>');
			pt.row('Type:', fhqgui.combobox('events_type', this.filter.events.type, fhq.getEventTypesFilter()));
			pt.row('On Page:', fhqgui.combobox('events_onpage', this.filter.events.onpage, fhq.getOnPage()));
			buttons = this.btn('Apply', 'fhqgui.applyEventsFilter(); fhqgui.resetEventsPage(); updateEvents(); fhqgui.closeFHQModalDialog();');
		} else {
			pt.row('TODO', current_page);
		}

		this.showFHQModalDialog({
			'header' : header,
			'content' : pt.render(),
			'buttons' : buttons
		});

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
	
	this.loadGames = function() {
		this.setFilter('games');
		var self = this;
		$('#content_page').html("Please wait...");

		$.post('api/games/list.php', {},
			function (obj) {
				var current_game = obj.current_game;
				
				// todo redesign handleFail
				if(obj.result=="fail"){
					var content = "";
					if(obj.error.code == 1224){
						content = "<div class='fhqbtn' onclick='fhqgui.showSignInForm();'>Sign In</div> or <div class='fhqbtn' onclick='fhqgui.showSignUpForm();'>Sign Up</div>";
					}
					$('#content_page').html(obj.error.message + '<br><br>' + content);
					return;
				}

				var content = '';
				if (obj['permissions']['insert'] == true)
					content += '<div class="fhqinfo">'
						+ '<div class="fhqbtn" onclick="formCreateGame();">Create Game</div>'
						+ '<div class="fhqbtn" onclick="fhqgui.formImportGame();">Import Game</div>'
						+ '</div><br>';

				for (var k in obj.data) {
					if (obj.data.hasOwnProperty(k)) {
						content += fhqgui.gameView(obj.data[k], current_game);
					}
				}
				$('#content_page').html(content);
			}
		)
	}

	this.loadMainPage = function() {
		this.setFilter('');
		var strVar="";
		strVar += "<table style=\"display: inline-block;\">";
		strVar += "					<tr>";
		strVar += "						<td valign=\"top\">";
		strVar += "							";
		strVar += "							<img class=\"leftimg\" src=\"images\/fhq2016_200x150.png\"\/>";
		strVar += "							<div id=\"jointothedarkside\" class=\"fhq-join-darkside\">";
		strVar += "								Join the dark side...";
		strVar += "							<\/div>";
		strVar += "						<\/td>";
		strVar += "						<td valign=\"top\">";
		strVar += "							<div class=\"fhq-topic\">free-hack-quest<\/div>";
		strVar += "							This is an open source platform for competitions in computer security.";
		strVar += "							";
		strVar += "							<div class=\"fhq-topic\">statistics<\/div>";
		strVar += "							<div class=\"single-line-preloader\">";
		strVar += "								<div class=\"single-line-name\">Quests:<\/div>";
		strVar += "								<div class=\"single-line-value preloading\" id=\"statistics-count-quests\">...<\/div>";
		strVar += "							<\/div>";
		strVar += "							<div class=\"single-line-preloader\">";
		strVar += "								<div class=\"single-line-name\">All attempts:<\/div>";
		strVar += "								<div class=\"single-line-value preloading\" id=\"statistics-all-attempts\">...<\/div>";
		strVar += "							<\/div>";
		strVar += "							<div class=\"single-line-preloader\">";
		strVar += "								<div class=\"single-line-name\">Already solved:<\/div>";
		strVar += "								<div class=\"single-line-value preloading\" id=\"statistics-already-solved\">...<\/div>";
		strVar += "							<\/div>";
		strVar += "							<div class=\"single-line-preloader\">";
		strVar += "								<div class=\"single-line-name\">Playing with us:<\/div>";
		strVar += "								<div class=\"single-line-value preloading\" id=\"statistics-playing-with-us\">...<\/div>";
		strVar += "							<\/div>";
		strVar += "							<div class=\"fhq-topic\">winners<\/div>";
		strVar += "							<div id=\"winners\">";
		strVar += "							<\/div>";
		strVar += "							<div class=\"fhq-topic\">developers and designers<\/div>";
		strVar += "							Evgenii Sopov<br>";
		strVar += "							<div class=\"fhq-topic\">team<\/div>";
		strVar += "							If you are not in team you can join to FHQ team on <a href=\"https:\/\/ctftime.org\/team\/16804\">ctftime<\/a>";
		strVar += "							<div class=\"fhq-topic\">thanks for<\/div>";
		strVar += "							<a href=\"http:\/\/www.chartjs.org\/docs\/\" target=\"_blank\">Charts.js<\/a>,";
		strVar += "							Sergey Belov (found xss!),";
		strVar += "							Igor Polyakov,";
		strVar += "							Maxim Samoilov (Nitive),";
		strVar += "							Dmitrii Mukovkin,";
		strVar += "							Team Keva,";
		strVar += "							Alexey Gulyaev,";
		strVar += "							Alexander Menschikov,";
		strVar += "							Ilya Bokov, ";
		strVar += "							Extrim Code,";
		strVar += "							Taisiya Lebedeva";
		strVar += "							<br>";
		strVar += "							";
		strVar += "							<div class=\"fhq-topic\">contacts<\/div>";
		strVar += "							<div class=\"single-line-preloader\">";
		strVar += "								<div class=\"single-line-name\">Group in VK: <\/div>";
		strVar += "								<div class=\"single-line-value\"><a href=\"http:\/\/vk.com\/freehackquest\" target=\"_blank\"><img width=30px src=\"images\/vk.png\"\/><\/a><\/div>";
		strVar += "							<\/div>";
		strVar += "							<div class=\"single-line-preloader\">";
		strVar += "								<div class=\"single-line-name\">Twitter: <\/div>";
		strVar += "								<div class=\"single-line-value\"><a href=\"https:\/\/twitter.com\/freehackquest\" target=\"_blank\"><img width=30px src=\"images\/twitter.png\"\/><\/a><\/div>";
		strVar += "							<\/div>";
		strVar += "							<div class=\"single-line-preloader\">";
		strVar += "								<div class=\"single-line-name\">Telegram: <\/div>";
		strVar += "								<div class=\"single-line-value\"><a href=\"https:\/\/telegram.me\/freehackquest\" target=\"_blank\"><img width=30px src=\"images\/telegram.png\"\/><\/a><\/div>";
		strVar += "							<\/div>";
		strVar += "							<div class=\"single-line-preloader\">";
		strVar += "								<div class=\"single-line-name\">Email: <\/div>";
		strVar += "								<div class=\"single-line-value\">freehackquest@gmail.com<\/div>";
		strVar += "							<\/div>";
		strVar += "";
		strVar += "							<div class=\"fhq-topic\">distribution<\/div>";
		strVar += '							You can download <a href=\"http://dist.freehackquest.com/" target="_blank">virtual machine (ova)</a> and up in local network.';
		strVar += "";
		strVar += "							<div class=\"fhq-topic\">source code<\/div>";
		strVar += "							<a href=\"http:\/\/github.com\/freehackquest\/fhq\" target=\"_blank\">http:\/\/github.com\/freehackquest\/fhq<\/a>";
		strVar += "";
		strVar += "							<div class=\"fhq-topic\">api<\/div>";
		strVar += "							<a href=\"api\/?html\">HTML<\/a>, ";
		strVar += "							<a href=\"api\/?json\">JSON<\/a><br>";
		strVar += "";
		strVar += "							<div class=\"fhq-topic\">donate<\/div>";
		strVar += "							<div id=\"donate-form\"><\/div>";
		strVar += "						<\/td>";
		strVar += "					<\/tr>";
		strVar += "				<\/table>";

		
		
		$("#content_page").html(strVar);

		this.loadCities();
		$.get('donate.html', function(result){
			$('#donate-form').html(result);
		});
		
		$('#jointothedarkside').unbind().bind('click', function(){
			if($('body').hasClass('dark')){
				$('body').removeClass('dark');
				$('#jointothedarkside').html('Join the dark side!');
			}else{
				$('body').addClass('dark');
				$('#jointothedarkside').html('You are on the dark side. Turn back?');
			}
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
		var userid = this.getUrlParameterByName("userid");
		if (questid) {
			showQuest(questid);
		} else if (userid) {
			this.showFullUserProfile(userid);
		};
		// else 
	}

	this.openQuestInNewTab = function(questid) {
		var win = window.open('?questid=' + questid, '_blank');
		win.focus();
	}

	this.openUserInNewTab = function(userid) {
		var win = window.open('?userid=' + userid, '_blank');
		win.focus();
	}

	this.showFullUserProfile = function(userid) {
		/*content += '<div class="fhquser_table">';
		content += '<div class="fhquser_row">';
		content += '<div class="fhquser_nick" id="user_baseinfo">?</div>';
		content += '</div>';
		content += '<div class="fhquser_row_skip"></div>';
		content += '<div class="fhquser_row">';
		content += '<div class="fhquser_info">';
		content += '<div class="fhquser_nick" ></div><br>';
		content += '</div>';
		content += '<div class="fhquser_row_skip"></div>';
		content += '<div class="fhquser_row" id="user_games"></div>';
		content += '<div class="fhquser_row_skip"></div>';
		content += '<div class="fhquser_row" id="user_skills"></div>';
		content += '<div class="fhquser_row_skip"></div>';
		content += '</div>';
		content += '</div>';*/
		
		var cp = new FHQContentPage();
		cp.clear();
		cp.append('\
			<div class="userpanel"> \
				<img src="files/users/0.png" id="user_logo" alt="photo" class="userpanel__photo"> \
				<h3 class="userpanel__title" id="user_nick">[team] usernick</h3> \
				<div class="userpanel__info" id="user_info">?</div> \
				<div class="userpanel__games gamespanel"> \
				<svg class="gamespanel__arrow gamespanel__arrow--left" viewBox="0 0 24 62"><path d="M17.27 31C17.27 21.04 24 0 24 0L.112 31 24 62s-6.73-20.292-6.73-31z" stroke="#50E3C2" fill="#212928" fill-rule="evenodd"/></svg> \
				<div class="gamespanel__gameswrap"> \
					<div class="gamespanel__games" id="user_games"> \
					</div>\
				</div> \
				<svg class="gamespanel__arrow gamespanel__arrow--right" viewBox="0 0 24 62" xmlns="http://www.w3.org/2000/svg"><path d="M6.73 31C6.73 40.96 0 62 0 62l23.888-31L0 0s6.73 20.292 6.73 31z" stroke="#50E3C2" fill="#212928" fill-rule="evenodd"/></svg> \
			</div> \
			<div class="bg"></div> \
		</div>');

		// info
		fhq.users.get(userid, function(obj) {
			// document.getElementById("user_baseinfo").innerHTML = obj.data.status + ' ' + obj.data.role + ' #' + obj.data.userid + '<br>'
			 // + 'UUID: ' + obj.data.uuid + '<br>'
			// + 'Last visit: ' + obj.data.dt_last_login;
			document.getElementById("user_nick").innerHTML = '#' + obj.data.userid + ' ' + obj.data.nick;
			document.getElementById("user_logo").src = obj.data.logo;

			document.getElementById("user_info").innerHTML = '';
			var arrProfile = new Array();
			if (obj.profile.country && obj.profile.country != '')
				arrProfile.push(obj.profile.country);
			if (obj.profile.city && obj.profile.city != '')
				arrProfile.push(obj.profile.city);
			if (obj.profile.university && obj.profile.university != '')
				arrProfile.push(obj.profile.university);
			if (obj.data.email)
				arrProfile.push(obj.data.email);	
			document.getElementById("user_info").innerHTML += arrProfile.join('</br>');
			
			for (var k in obj.games) {
				var nProgress = Math.floor((obj.games[k].score * 100) / obj.games[k].maxscore);
				// alert(obj.games[k].score + ' / ' + obj.games[k].maxscore + ' = ' + nProgress + '%');
				document.getElementById("user_games").innerHTML += '\<div title="' + obj.games[k].title + '" data-progress="' + nProgress + '" style="background-image: url(' + obj.games[k].logo + ')" class="gamespanel__game"></div>';
				// document.getElementById("user_games").innerHTML += 'Game "' +  + '" (' + obj.games[k].type_game + '): ' + obj.games[k].score + " / " + obj.games[k].maxscore
				//	+ "<br>";
			}
			
			// --- begin app.js ---
			(function e(t,n,r) { function s(o,u) {  if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports) {
				var $games, circle, element, gameWidth, i, len, ref;

				gameWidth = 415 / 4;

				$games = $('.gamespanel__games');

				$games.css('left', 0);

				$('.gamespanel__arrow--left').click(function() {
				  var left;
				  left = Math.min(0, parseFloat($games.css('left')) + gameWidth * 3);
				  return $games.stop().animate({
					'left': left
				  }, 400);
				});

				$('.gamespanel__arrow--right').click(function() {
				  var left;
				  left = Math.max(-gameWidth * $games.length, parseFloat($games.css('left')) - gameWidth * 3);
				  return $games.stop().animate({
					'left': left
				  }, 400);
				});

				ref = document.getElementsByClassName('gamespanel__game');
				for (i = 0, len = ref.length; i < len; i++) {
				  element = ref[i];
				  circle = new ProgressBar.Circle(element, {
					color: '#50e3c2',
					strokeWidth: 4,
					trailColor: 'black',
					duration: 1500,
					easing: 'elastic',
					fill: 'rgba(0,0,0,.7)',
					text: {
					  value: '0'
					},
					step: function(state, bar) {
					  return bar.setText((bar.value() * 100).toFixed(0));
					},
					click: function() {
					  return alert('cat');
					}
				  });
				  circle.progress = element.dataset.progress / 100;
				  setTimeout((function() {
					return this.animate(this.progress);
				  }).bind(circle), 1000);
				}
			},{}]},{},[1]);
			// --- end app.js ---
		});
		
		// skills
		fhq.users.skills(userid, function(obj) {
			
			/*document.getElementById("user_skills").innerHTML = '<canvas id="skill' + userid + '" width="450" height="170"></canvas>';
			// document.getElementById("user_skills").innerHTML += '<br>' + JSON.stringify(obj);

			var ctx = document.getElementById('skill' + userid).getContext("2d");
			ctx.font = "12px Arial";
			ctx.fillStyle = "#CCC";
			ctx.strokeStyle = "#CCC";

			var y = 10;
			for (var sub in obj.data.skills[userid].subjects) {
				// data.labels.push(sub);
				var max = obj.data.skills[userid].subjects[sub].max;
				var score = obj.data.skills[userid].subjects[sub].score;
				var percent = 0;
				if (max != 0) {
					percent = Math.round((score/max)*100);
				}
				ctx.fillText(sub, 10, y);
				ctx.fillText('' + percent + '% ', 80, y);
				ctx.strokeRect(120, y-9, 200, 8);
				ctx.fillRect (120, y-9, percent*2, 9);
				y += 12;
			}*/
		});	
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
	
	this.exportGame = function(gameid) {
		fhq.games.export(gameid);
	}

	this.formImportGame = function() {
		var content = "todo import Game";
		var pt = new FHQParamTable();
		pt.row('', 'ZIP: <input id="importgame_zip" type="file" required/>');
		pt.row('', '<div class="fhqbtn" onclick="fhqgui.importGame();">Import</div>');
		pt.skip();
		this.showModalDialog(pt.render());
	}
	
	this.importGame = function() {
		var files = document.getElementById('importgame_zip').files;
		if (files.length == 0) {
			alert("Please select file");
			return;
		}
		/*for(i = 0; i < files.length; i++)
			alert(files[i].name);*/
		
		send_request_post_files(
			files,
			'api/games/import.php',
			createUrlFromObj({}),
			function (obj) {
				if (obj.result == "fail") {
					alert(obj.error.message);
					return;
				}
				// document.getElementById('editgame_logo').src = obj.data.logo + '?' + new Date().getTime();
				fhqgui.closeModalDialog();
				fhqgui.loadGames();
			}
		);
	}
		
	this.exportQuest = function(questid) {
		fhq.quests.export(questid);
	}

	this.formImportQuest = function() {
		var pt = new FHQParamTable();
		pt.row('', 'ZIP: <input id="importquest_zip" type="file" required/>');
		pt.row('', '<div class="fhqbtn" onclick="fhqgui.importQuest();">Import</div>');
		pt.skip();
		this.showModalDialog(pt.render());
	}

	this.importQuest = function() {
		var files = document.getElementById('importquest_zip').files;
		if (files.length == 0) {
			alert("Please select file");
			return;
		}
		/*for(i = 0; i < files.length; i++)
			alert(files[i].name);*/
		
		send_request_post_files(
			files,
			'api/quests/import.php',
			createUrlFromObj({}),
			function (obj) {
				if (obj.result == "fail") {
					alert(obj.error.message);
					return;
				}
				fhqgui.closeModalDialog();
				loadQuests();
			}
		);
	}
	
	this.handleFail = function(response){
		if(response.result=='fail'){
			if(response.error.code == 1224){
				self.showFHQModalDialog({
					'header' : '',
					'content' : 'Please Sing In or Sing Up',
					'buttons' : ''
				});
			}
			return true;
		}
		return false;
	}

	this.chooseGame = function(id) {
		var self = this;
		$.post('api/games/choose.php', {'id' : id},
			function(obj){
				if(self.handleFail(obj)){
					return;
				}
				if(obj.result=='ok'){
					window.location.href = "?page=quests";
				}
			}
		);
	}

	this.loadTool = function(toolid){
		this.changeLocationState({'tools' : '', 'toolid': toolid});
		$('.toolinfo').html('Loading...');
		$.getScript("./js/fhq.plugins/" + toolid + "/index.js", function(){
			$('.toolinfo').html('');
			window[toolid].init($('.toolinfo'));
		});
	}
	
	this.loadTools = function(){
		$('#content_page').html('<div class="toolinfo"></div><div class="toolslist"></div>');
		
		var len = FHQPlugins.length;
		
		$('.toolslist').html('');
		$('.toolslist').append('<div class="tools"><div class="icon">Tools</div><div class="content"></div></div>');
		
		for(var i = 0; i < len; i++){
			var tool = FHQPlugins[i];
			if(tool.type == 'tools'){
				$('.toolslist .tools .content').append('<div class=toolitem toolid="' + tool.id + '"><div class="name">' + tool.name[this.lang()] + '</div></div>');	
			}
		}

		$('.toolitem').unbind('click').bind('click', function(){
			self.loadTool($(this).attr('toolid'));
		});
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

		if (perms['choose'] == true)
			content += '<div class="fhqbtn" onclick="fhqgui.chooseGame(' + game.id + ');">Choose</div> ';
		
		if (perms['delete'] == true)
			content += '<div class="fhqbtn" onclick="formDeleteGame(' + game.id + ');">Delete</div>';

		if (perms['update'] == true)
			content += '<div class="fhqbtn" onclick="formEditGame(' + game.id + ');">Edit</div>';
			
		if (perms['export'] == true)
			content += '<div class="fhqbtn" onclick="fhqgui.exportGame(' + game.id + ');">Export</div>';

		content += '			</div>';
		content += '		</div>'; // fhq_event_info_cell_content
		content += '	</div>'; // fhq_event_info_row
		content += '</div><br>'; // fhq_event_info
		return content;
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
						ctx.fillStyle = $('#content_page').css( "color" );
						ctx.strokeStyle = $('#content_page').css( "color" );

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
