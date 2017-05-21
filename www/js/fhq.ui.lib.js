if(!window.fhq) window.fhq = {};
if(!window.fhq.ui) window.fhq.ui = {};

fhq.ui.modalDialog2ClickContent = false;

fhq.ui.showModalDialog = function(obj) {
	// document.getElementById('modal_dialog').style.top = document.body.
	$('#fhqmodaldialog').css({'transform': 'scale(1)', visibility: 'visible', overflow: 'hidden'});
	
	$('#fhqmodaldialog_header').html(obj.header);
	$('#fhqmodaldialog_content').html(obj.content);
	$('#fhqmodaldialog_buttons').html(obj.buttons + fhq.ui.templates.dialog_btn_cancel());
	document.body.scroll = "no"; // ie only
	fhq.ui.modalDialog2ClickContent = false;
	document.onkeydown = function(evt) {
		if (evt.keyCode == 27)
			fhq.ui.closeModalDialog();
	}
}

fhq.ui.closeModalDialog = function() {
	$('#fhqmodaldialog').css({'transform': ''});
	setTimeout(function(){
		$('#fhqmodaldialog_content').html("");
		document.getElementById('fhqmodaldialog').style.visibility = 'hidden';
		document.documentElement.style.overflow = 'auto';  // firefox, chrome
		document.body.scroll = "yes"; // ie only
		document.onkeydown = null;
	},800);
}

fhq.ui.updateModalDialog = function(obj) {
	$('#fhqmodaldialog_header').html(obj.header);
	$('#fhqmodaldialog_content').html(obj.content);
	$('#fhqmodaldialog_buttons').html(obj.buttons + fhq.ui.templates.dialog_btn_cancel());
}

fhq.ui.clickModalDialog_content = function() {
	fhq.ui.FHQModalDialog_ClickContent = true;
}

fhq.ui.clickModalDialog_dialog = function() {
	if(fhq.ui.FHQModalDialog_ClickContent != true){
		fhq.ui.closeModalDialog();
	}else{
		fhq.ui.FHQModalDialog_ClickContent = false;
	}
}

/* Sign In */

fhq.ui.showSignInForm = function() {
	fhq.ui.showModalDialog(fhq.ui.templates.singin());

	if(fhq.supportsHtml5Storage()){
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

fhq.ui.cleanupSignInMessages = function() {
	$('#signin-error-message').html('');
}

fhq.ui.signin = function() {
	var email = $("#signin-email").val();
	var password = $("#signin-password").val();
	
	var obj = fhq.api.users.login(email,password).done(function(r){
		// TODO
		// $('#signin-email').val('');
		// $("#signin-password").val('');
		$('.message_chat').remove();
		if(fhq.supportsHtml5Storage()){
			localStorage.setItem("email", email);
			localStorage.setItem("password", password);
		}
		fhq.ui.processParams();
		fhq.ui.closeModalDialog();
	}).fail(function(r){
		if(r.error && r.error.message){
			$("#signin-error-message").html(r.error.message);
		}
	})
}

fhq.ui.signout = function(){
	$('.message_chat').remove();
	fhq.api.users.logout().done(function(){
		fhq.ui.processParams();
	});
}

function FHQGuiLib(api) {
	var self = this;
	this.fhq = api;
	this.api = api;
	
	// include dark style
	if(fhq.containsPageParam("dark")){
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

	/* top menu */

	this.loadTopPanel = function(){
		var toppanel = $('.fhqtopmenu_toppanel_container');
		toppanel.html('');
		// logo
		toppanel.append('<a class="fhq-menu-logo" href="./?">'
			+ '<img class="fhq_btn_menu_img" src="images/fhq2016_200x150.png"/> '
			+ '</a>')
		
		toppanel.append('<div id="btnmenu_quests" class="fhq0041">' + fhq.t('Quests') + '</div>')
		toppanel.append('<div id="btnmenu_scoreboard" class="fhq0041">' + fhq.t('Scoreboard') + '</div>');
		toppanel.append('<div id="btnmenu_news" class="fhq0041">' + fhq.t('News') + '</div>');
		toppanel.append('<div id="btnmenu_more" class="fhq0041">' + fhq.t('Other') + '</div>');
		toppanel.append('<div id="btnmenu_about" class="fhq0041">' + fhq.t('About') + '</div>');

		toppanel.append('<div id="btnmenu_colorscheme" class="fhq0041">'
			+ '<img class="fhq_btn_menu_img" src="images/menu/lightside_150x150.png"/> '
			+ '</div>');

		toppanel.append('<div id="btnmenu_user" class="fhq0041">'
			+ '<img class="fhq_btn_menu_img user-logo" src="' + (fhq.isAuth() && fhq.userinfo ? fhq.userinfo.logo : 'images/menu/user.png') + '"/>  '
			+ (fhq.isAuth() && fhq.userinfo ? fhq.userinfo.nick : fhq.t('Account'))
			+ '<div class="account-panel"></div>');

		$('.account-panel').append(
			'<img class="fhq_btn_menu_img user-logo" src="' + (fhq.isAuth() && fhq.userinfo ? fhq.userinfo.logo : 'images/menu/user.png') + '"/>  '
			+ (fhq.isAuth() && fhq.userinfo ? fhq.userinfo.nick : fhq.t('Account'))
		);
		$('.account-panel').append('<div class="border"></div>');

		if(!fhq.isAuth()){
			$('.account-panel').append('<div id="btnmenu_signin" class="fhq-simple-btn" onclick="fhq.ui.showSignInForm();">' + fhq.t('Sign-in') + '</div>');
			$('.account-panel').append('<div class="fhq-simple-btn" onclick="window.location=\'./google_auth.php\'">' + fhq.t('Sign-in with Google') + '</div>');
			$('.account-panel').append('<div id="btnmenu_signup" class="fhq-simple-btn" onclick="fhqgui.showSignUpForm();">' + fhq.t('Sign-up') + '</div>');
			$('.account-panel').append('<div id="btnmenu_restore_password" class="fhq-simple-btn" onclick="fhqgui.showResetPasswordForm();">' + fhq.t('Forgot password?') + '</div>');
		}else{
			var game_id = 0;
			$('.account-panel').append('<div class="fhq-simple-btn" onclick="fhq.ui.loadUserProfile(' + (fhq.userinfo ? fhq.userinfo.id : 0) + ');">' + fhq.t('Your Profile') + '</div>');
			$('.account-panel').append('<div class="fhq-simple-btn" onclick="fhqgui.createPageSkills(); fhqgui.updatePageSkills();">Skills</div>');
			$('.account-panel').append('<div class="fhq-simple-btn" onclick="fhq.ui.signout();">' + fhq.t('Sign-out') + '</div>');
			
			if(fhq.isAdmin()){
				$('.account-panel').append('<div class="border"></div>');
				$('.account-panel').append('<div class="fhq-simple-btn" onclick="fhqgui.loadSettings(\'content_page\');">Settings</div>');
				$('.account-panel').append('<div class="fhq-simple-btn" onclick="createPageUsers(); updateUsers();">' + fhq.t('Users') + '</div>');
				$('.account-panel').append('<div class="fhq-simple-btn" onclick="fhq.ui.loadUsers()">Users 2</div>');
				$('.account-panel').append('<div class="fhq-simple-btn" onclick="fhq.ui.loadAnswerList()">' + fhq.t('Answer List') + '</div>');
				$('.account-panel').append('<div class="fhq-simple-btn" onclick="fhq.ui.loadServerInfo()">' + fhq.t('Server Info') + '</div>');
			}
		}
		
		toppanel.append(''
			+ '<div id="btnmenu_plus" class="fhq0041 fhq0042">'
			+ '<div class="fhq0043"></div>'
			+ '<div class="fhq0044"></div>'
			+ '</div>');
		// create menu
		
		$('.fhq0044').append('<div class="fhq0045" onclick="fhq.ui.loadNewFeedback()">' + fhq.t('New Feedback') + '</div>');

		if (fhq.isAdmin()){
			$('.fhq0044').append('<div class="fhq0045" onclick="formCreateGame();">' + fhq.t('Create Game') + '</div>');
			$('.fhq0044').append('<div class="fhq0045" onclick="fhqgui.formImportGame();">' + fhq.t('Import Game') + '</div>');
		}
		

		$('#btnmenu_user').unbind().bind('click', function(e){
			$('.accout-panel').show();
		});
		
		$('#btnmenu_colorscheme').unbind().bind('click', function(){
			if ($('body').hasClass('dark')) {
				self.setLightColorScheme();
			} else {
				self.setDarkColorScheme();
			}
		})
		
		$('#btnmenu_quests').unbind().bind('click', function(){
			fhq.changeLocationState({'quests':''});
			fhq.ui.loadStatSubjectsQuests();
		})
		
		$('#btnmenu_scoreboard').unbind().bind('click', function(){
			window.fhq.changeLocationState({'scoreboard':''});
			fhq.ui.loadScoreboard();
		})
		
		
		$('#btnmenu_news').unbind().bind('click', function(){
			window.fhq.changeLocationState({'news':''});
			fhq.ui.loadPageNews();
		})
		
		$('#btnmenu_more').unbind().bind('click', function(){
			window.fhq.changeLocationState({'more':''});
			fhq.ui.loadPageMore();
		})
		
		$('#btnmenu_about').unbind().bind('click', function(){
			window.fhq.changeLocationState({'about':''});
			fhq.ui.loadPageAbout();
		})
		
		$('#btnmenu_plus').unbind().bind('click', function(){
			$('.fhq0043').show();
			$('.fhq0044').show();
			console.log("1234");
		});

		$('.fhq0043').unbind().bind('click', function(e){
			console.log("123");
			e.stopPropagation();
			e.preventDefault();
			$('.fhq0043').hide();
			$('.fhq0044').hide();
			return true;
		})
	}
	
	/* Sign Up */
	
	this.showSignUpForm = function() {
		fhq.ui.showModalDialog(fhq.ui.templates.singup());
		this.refreshSignUpCaptcha();
	}

	this.refreshSignUpCaptcha = function() {
		fhq.api.users.captcha().done(function(r){
			$('#signup-captcha-image').attr({
				'src': 'data:image/png;base64, ' + r.data.captcha,
				'uuid': r.data.uuid
			});
		}).fail(function(r){
			console.error(r)
		})
	}

	this.cleanupSignUpMessages = function() {
		$('#signup-error-message').html('');
		$('#signup-info-message').html('');
	}

	this.signup = function() {
		$('#signup-error-message').html('');
		$('#signup-info-message').html('Please wait...');
		var params = {};
		params.email = $('#signup-email').val();
		params.captcha = $('#signup-captcha').val();
		params.captcha_uuid = $('#signup-captcha-image').attr('uuid');

		fhq.api.users.registration(params).done(function(r){
			console.log(r);
			$('#signup-email').val('');
			$('#signup-captcha').val('');
			$('#signup-info-message').html('');
			$('#signup-error-message').html('');
			fhq.ui.updateModalDialog({
				'header' : 'Sign Up',
				'content': r.data.message,
				'buttons': ''
			});
		}).fail(function(r){
			console.error(r);
			$('#signup-error-message').html(r.responseJSON.error.message);
			$('#signup-info-message').html('');
			self.refreshSignUpCaptcha();
			$('#signup-captcha').val('');
		})
			
	}
	
	/* Reset Password */

	this.showResetPasswordForm = function() {
		fhq.ui.showModalDialog(fhq.ui.templates.reset_password());
		this.refreshResetPasswordCaptcha();
	};

	this.refreshResetPasswordCaptcha = function() {
		fhq.api.users.captcha().done(function(r){
			$('#reset-password-captcha-image').attr({
				'src': 'data:image/png;base64, ' + r.data.captcha,
				'uuid': r.data.uuid
			});
		}).fail(function(r){
			console.error(r)
		})
	}

	this.cleanupResetPasswordMessages = function() {
		$('#reset-password-info-message').html('');
		$('#reset-password-error-message').html('');
	}

	this.resetPassword = function() {
		var self = this;
		$('#reset-password-error-message').html('');
		$('#reset-password-info-message').html('Please wait...');
		var params = {};
		params.email = $('#reset-password-email').val();
		params.captcha = $('#reset-password-captcha').val();
		params.captcha_uuid = $('#reset-password-captcha-image').attr('uuid');

		fhq.api.users.reset_password(params).done(function(r){
			$('#reset-password-email').val('');
			$('#reset-password-captcha').val('');
			$('#reset-password-info-message').html('');
			$('#reset-password-error-message').html('');
			
			fhq.ui.updateModalDialog({
				'header' : 'Reset Password',
				'content': r.data.message,
				'buttons': ''
			});
		}).fail(function(r){
			console.error(r);
			$('#reset-password-error-message').html(r.responseJSON.error.message);
			$('#reset-password-info-message').html('');
			$('#reset-password-captcha').val('');
			self.refreshResetPasswordCaptcha();
		})
	};

	this.loadCities = function() {
		fhq.ws.getPublicInfo().done(function(response){
			$('#statistics-users-online').removeClass('preloading');
			$('#statistics-users-online').text(response.connectedusers);
		});
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
				$('#statistics-playing-with-us').append('<br><a href="map.php" target="_blank">On Map</a>');

				var content = "";
				for (var k in response.data.winners) {
					var winner = response.data.winners[k];
					content += '<div class="single-line-preloader"> <div class="single-line-name">' + winner.place + ' (+' + winner.rating + '):</div> ';
					content += '<div class="single-line-value">' + winner.user + '</div>';
					content += '</div>';
				}
				$('#winners').html(content);
			}
		});
	};

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
	
	this.showFilter = function() {
		var current_page = this.filter.current;
		
		var pt = new FHQParamTable();
		var header = '';
		var buttons = '';
		if (current_page == 'stats') {
			header = 'Filter Statistics';
			pt.row('Quest Name:', '<input type="text" id="statistics_questname" value=""/>');
			pt.row('Quest ID:', '<input type="text" id="statistics_questid" value=""/>');
			pt.row('Quest Subject:', fhqgui.combobox('statistics_questsubject', this.filter.stats.questsubject, fhq.getQuestTypesFilter()));
			pt.row('On Page:', fhqgui.combobox('statistics_onpage', this.filter.stats.onpage, fhq.getOnPage()));
			buttons = this.btn('Apply', 'fhqgui.applyStatsFilter(); resetStatisticsPage(); updateStatistics(); fhq.ui.closeModalDialog();');
		} else if (current_page == 'skills') {
			header = 'Filter Skills';
			pt.row('Subject:', fhqgui.combobox('skills_subject', this.filter.skills.subject, fhq.getQuestTypesFilter()));
			pt.row('User:', '<input type="text" id="skills_user" value=""/>');
			pt.row('On Page:', fhqgui.combobox('skills_onpage', this.filter.skills.onpage, fhq.getOnPage()));
			buttons = this.btn('Apply', 'fhqgui.applySkillsFilter(); fhqgui.resetSkillsPage(); fhqgui.updatePageSkills(); fhq.ui.closeModalDialog();');
		} else if (current_page == 'events') {
			header = 'Filter News';
			pt.row('Search:', '<input type="text" id="events_search" value=""/>');
			pt.row('Type:', fhqgui.combobox('events_type', this.filter.events.type, fhq.getEventTypesFilter()));
			pt.row('On Page:', fhqgui.combobox('events_onpage', this.filter.events.onpage, fhq.getOnPage()));
			buttons = this.btn('Apply', 'fhqgui.applyEventsFilter(); fhqgui.resetEventsPage(); updateEvents(); fhq.ui.closeModalDialog();');
		} else {
			pt.row('TODO', current_page);
		}

		fhq.ui.showModalDialog({
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

	if(localStorage.getItem('colorscheme') == null){
		localStorage.setItem('colorscheme', 'light');
	}

	this.applyColorScheme = function(){
		if(localStorage.getItem('colorscheme') == 'dark'){
			self.setDarkColorScheme();
		}else{
			self.setLightColorScheme();
		}
	}
	
	this.setDarkColorScheme = function(){
		$('body').addClass('dark');
		localStorage.setItem('colorscheme', 'dark');
		$('#jointothedarkside').html(fhq.t('You are on the dark side. Turn back?'));
		$('#btnmenu_colorscheme img').attr({'src': 'images/menu/lightside_150x150.png'})
	}
	
	this.setLightColorScheme = function(){
		$('body').removeClass('dark');
		localStorage.setItem('colorscheme', 'light');
		$('#jointothedarkside').html(fhq.t('Join the dark side...'));
		$('#btnmenu_colorscheme img').attr({'src': 'images/menu/darkside_150x150.png'})
		
	}

	this.eventView = function(event, access) {
		var content = '';
		var imgpath = '';
		if (event.type == 'users')
			imgpath = 'images/menu/user.png';
		else if (event.type == 'quests')
			imgpath = 'images/menu/quests_150x150.png';
		else if (event.type == 'warning')
			imgpath = 'images/menu/warning.png';
		else if (event.type == 'info')
			imgpath = 'images/menu/news.png';
		else if (event.type == 'games')
			imgpath = 'images/menu/games.png';
		else
			imgpath = 'images/menu/default.png'; // default

		var marknew = '';
		if (event.marknew && event.marknew == true && fhq.isAuth())
			marknew = '*** NEW!!! ***,';

		var content = ''
			+ '<div class="fhq0017">'
			+ '	<div class="fhq0018">'
			+ '		<div class="fhq0019" style="background-image: url(' + imgpath + ')"></div>\n'
			+ '		<div class="fhq0020">'
			+ event.message
			+ '			<div class="fhq_event_caption"> [' + marknew + event.type + ', ' + event.dt + ']</div>';
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
	
	this.userIcon = function(userid, logo, nick) {
		return '<div class="fhqbtn" onclick="showUserInfo(' + userid + ')"> <img class="fhqmiddelinner" width=25px src="' + logo + '"/> ' + nick + '</div>'
	}

	this.getUrlParameterByName = function(name) {
		name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	
	this.messageLastId = 0;
	this.showedMessages = [];
	
	this.updatePostionMessages = function(){
		var count = self.showedMessages.length;
		for(var t in self.showedMessages){
			var id = self.showedMessages[t];
			count--;
			var bottom = (115 + count*60) + 'px';
			$('#' + id).css({
				'bottom' : bottom,
				'right': '25px'
			});
		}
	}

	fhq.handlerReceivedChatMessage = function(response) {
		self.showChatMessage(response.message, response.user);
	}

	this.showChatMessage = function(m,u){
		self.messageLastId++;
		var id = 'message' + self.messageLastId;
		self.showedMessages.push(id);
		m = $('<div/>').text(m).html();
		u = $('<div/>').text(u).html();
		var newel = $( '<div id="' + id + '" class="message_chat">' + m  + '<div class="message-chat-user">' + u + '</div></div>');
		$( "body" ).append( newel );
		newel.bind('click', function(){
			$( "#" + id).remove();
			self.showedMessages = jQuery.grep(self.showedMessages, function(value) { return value != id; });
			self.updatePostionMessages();
		});
		setTimeout(function(){self.updatePostionMessages();}, 1000);
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

		});	
	}

	this.loadSettings = function(idelem) {
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
				fhq.ui.loadGames();
			}
		);
	}
		
	this.exportQuest = function(questid) {
		fhq.quests.export(questid);
	}
	
	this.handleFail = function(response){
		if(response.result=='fail'){
			if(response.error.code == 1224){
				fhq.ui.showModalDialog({
					'header' : '',
					'content' : 'Please Sing In or Sing Up',
					'buttons' : ''
				});
			}
			return true;
		}
		return false;
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
					el.innerHTML = fhq.ui.paginator(0, obj.data.found, onpage, page, 'fhqgui.setSkillsPage', 'fhqgui.updatePageSkills');

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

fhq.ui.processParams = function() {
	fhq.api.users.profile().always(function(){
		fhqgui.loadTopPanel();
		fhq.ui.initChatForm();
		if(fhq.containsPageParam("quests")){
			fhq.ui.loadStatSubjectsQuests();
		} else if(fhq.containsPageParam("news")){
			fhq.ui.loadPageNews();
		} else if(fhq.containsPageParam("classbook")){
			fhq.ui.loadClassbook();
		} else if(fhq.containsPageParam("about")){
			fhq.ui.loadPageAbout();
		} else if(fhq.containsPageParam("skills")){
			fhqgui.createPageSkills();
			fhqgui.updatePageSkills();
		} else if(fhq.containsPageParam("stats")){
			// TODO
			createPageStatistics('.$gameid.');
			updateStatistics('.$gameid.');
		} else if(fhq.containsPageParam("games")){
			fhq.ui.loadGames();
		} else if(fhq.containsPageParam("scoreboard")){
			fhq.ui.loadScoreboard();
		} else if (fhq.containsPageParam("quest")){
			fhq.ui.loadQuest(fhq.pageParams["quest"]);
		}else if(fhq.containsPageParam("userid")){
			var userid = fhq.pageParams["userid"]
			this.showFullUserProfile(userid);
		}else if(fhq.containsPageParam("subject")){
			fhq.ui.loadQuestsBySubject(fhq.pageParams["subject"]);
		}else if(fhq.containsPageParam("new_feedback")){
			fhq.ui.loadNewFeedback();
		}else if(fhq.containsPageParam("tools")){
			fhq.ui.loadTools();
		}else if(fhq.containsPageParam("tool")){
			fhq.ui.loadTool(fhq.pageParams["tool"]);
		}else if(fhq.containsPageParam("serverinfo")){
			fhq.ui.loadServerInfo();
		}else if(fhq.containsPageParam("answerlist")){
			fhq.ui.loadAnswerList();
		}else if(fhq.containsPageParam("more")){
			fhq.ui.loadPageMore();
		}else if(fhq.containsPageParam("feedback")){
			fhq.ui.loadFeedback()
		}else{
			// default
			fhq.ui.loadStatSubjectsQuests();
		}
	});
}

fhq.ui.loadServerInfo = function(){
	window.fhq.changeLocationState({'serverinfo':''});
	$("#content_page").html('<div class="fhq0054"></div>');
	fhq.ws.serverinfo().done(function(r){
		$('.fhq0054').append('<div class="fhq0055"><h1>Request Statistics</h1></div>');
		for(var i in r.data){
			$('.fhq0055').append('<div class="fhq0056">' + i + ' => ' + r.data[i] + '</div>')
		}
	}).fail(function(r){
		console.error(r);
		$('.fhq0054').append(r.error);
	})
}

fhq.ui.loadAnswerList = function(){
	var onpage = 8;
	if(fhq.containsPageParam("onpage")){
		onpage = parseInt(fhq.pageParams['onpage'], 10);
	}

	var page = 0;
	if(fhq.containsPageParam("page")){
		page = parseInt(fhq.pageParams['page'], 10);
	}
	
	window.fhq.changeLocationState({'answerlist': '', 'onpage': onpage, 'page': page});
	$("#content_page").html('<div class="fhq0057"></div>');
	$('.fhq0057').append('<h1>' + fhq.t('Answer List') + '</h1>');
	$('.fhq0057').append('<div class="fhq0063"></div>');
	$('.fhq0057').append('<div class="fhq0058"></div>');
	$('.fhq0058').append(fhq.ui.render([{
		'c': 'fhq0059',
		'r': [
			{ 'c': 'fhq0061', 'r': fhq.t('Date Time')},
			{ 'c': 'fhq0061', 'r': fhq.t('Quest')},
			{ 'c': 'fhq0061', 'r': fhq.t('Answer')},
			{ 'c': 'fhq0061', 'r': fhq.t('Passed')},
			{ 'c': 'fhq0061', 'r': fhq.t('User')},
		]
	}]));

	fhq.ws.answerlist({'onpage': onpage, 'page': page}).done(function(r){
		$('.fhq0063').append(fhq.ui.paginator(0, r.count, r.onpage, r.page));
		
		for(var i in r.data){
			var uqa = r.data[i];
			$('.fhq0058').append(fhq.ui.render([{
				'c': 'fhq0059' + (uqa.passed == 'Yes' ? ' fhq0062' : ''),
				'r': [
					{ 'c': 'fhq0061', 'r': uqa.dt},
					{ 'c': 'fhq0061', 'r': uqa.quest.subject + ' / Quest ' + uqa.quest.id + '<br>' + uqa.quest.name + ' (+' + uqa.quest.score + ')' },
					{ 'c': 'fhq0061', 'r': 'User: ' + uqa.user_answer + '<br> Quest: ' + uqa.quest_answer + '<br> Levenshtein: ' + uqa.levenshtein },
					{ 'c': 'fhq0061', 'r': uqa.passed },
					{ 'c': 'fhq0061', 'r': fhqgui.userIcon(uqa.user.id, uqa.user.logo, uqa.user.nick)},
				]
			}]));
			
			/*$('.fhq0058').append('<div class="fhq0059">'
			+ i + ' => ' + r.data[i]
			+ '</div>')*/
		}
	}).fail(function(r){
		console.error(r);
		$('.fhq0057').append(r.error);
	})
}

fhq.ui.loadPageAbout = function() {
	window.fhq.changeLocationState({'about':''});
	var strVar=''
	+ '<table style="display: inline-block;width: 80%;background-color: #494949;">';
	strVar += "					<tr>";
	strVar += "						<td valign=\"top\">";
	strVar += "							<div class=\"fhq-topic\">free-hack-quest<\/div>"
	+ fhq.t('This is an open source platform for competitions in computer security.')
	+ '							<div class="fhq-topic">' + fhq.t('statistics') + '</div>';
	strVar += "							<div class=\"single-line-preloader\">";
	strVar += '								<div class="single-line-name">' + fhq.t('Quests') + ':</div>';
	strVar += "								<div class=\"single-line-value preloading\" id=\"statistics-count-quests\">...<\/div>";
	strVar += "							<\/div>";
	strVar += "							<div class=\"single-line-preloader\">";
	strVar += '								<div class="single-line-name">' + fhq.t('All attempts') + ':</div>';
	strVar += "								<div class=\"single-line-value preloading\" id=\"statistics-all-attempts\">...<\/div>";
	strVar += "							</div>"
	+ '<div class="single-line-preloader">'
	+ '		<div class="single-line-name">' + fhq.t('Already solved') + ':</div>'
	+ '		<div class="single-line-value preloading" id="statistics-already-solved">...</div>'
	+ '</div>'
	+ '<div class="single-line-preloader">'
	+ '		<div class="single-line-name">' + fhq.t('Users online') + ':</div>'
	+ '		<div class="single-line-value preloading" id="statistics-users-online">...</div>'
	+ '</div>';
	strVar += "							<div class=\"single-line-preloader\">";
	strVar += '								<div class="single-line-name">' + fhq.t('Playing with us') + ':</div>';
	strVar += '								<div class="single-line-value preloading" id="statistics-playing-with-us">...</div>'
	+ '</div>'
	+ '<div class="fhq-topic">' + fhq.t('leaders') + '<\/div>';
	strVar += "							<div id=\"winners\">";
	strVar += "							<\/div>"
	+ ' <div class="fhq-topic">' + fhq.t('developers and designers') + '<\/div>';
	strVar += "							Evgenii Sopov<br>"
	+ '<div class="fhq-topic">' + fhq.t('team') + '</div>'
	+ fhq.t('If you are not in team you can join to FHQ team on') + ' <a href="https://ctftime.org/team/16804\">ctftime</a>'
	+ '<div class="fhq-topic">' + fhq.t('thanks for') + '</div>';
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
	strVar += "							Taisiya Lebedeva"
	+ '<br>'
	+ '<div class="fhq-topic">' + fhq.t('contacts') + '</div>';
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
	strVar += "								<div class=\"single-line-value\">freehackquest@gmail.com<\/div>"
	+ '</div>'
	+ '<div class="fhq-topic">' + fhq.t('distribution') + '</div>';
	strVar += 'You can download <a href=\"http://dist.freehackquest.com/" target="_blank">virtual machine (ova)</a> and up in local network.'
	+ '<div class="fhq-topic">' + fhq.t('source code') + '</div>';
	strVar += "							<a href=\"http:\/\/github.com\/freehackquest\/fhq\" target=\"_blank\">http:\/\/github.com\/freehackquest\/fhq<\/a>";
	strVar += '							<a href="http://github.com/freehackquest/backend/" target="_blank">http://github.com/freehackquest/backend</a>';
	strVar += "							<div class=\"fhq-topic\">api<\/div>";
	strVar += "							<a href=\"api\/?html\">HTML<\/a>, ";
	strVar += "							<a href=\"api\/?json\">JSON<\/a><br>";
	+ '<div class="fhq-topic">' + fhq.t('donate') + '</div>';
	strVar += "							<div id=\"donate-form\"><\/div>";
	strVar += "						<\/td>";
	strVar += "					<\/tr>";
	strVar += "				<\/table>";
	
	$("#content_page").html(strVar);

	fhqgui.loadCities();
	$.get('donate.html', function(result){
		$('#donate-form').html(result);
	});
	
	fhqgui.applyColorScheme();
}


fhq.ui.loadPageNews = function(){
	createPageEvents();
	updateEvents();
}

fhq.ui.loadScoreboard = function(){
	window.fhq.changeLocationState({'scoreboard':''});

	// document.getElementById("gameid").value;

	fhq.ws.scoreboard().done(
		function (r) {
			console.log(r);
			var el = document.getElementById("content_page");
			el.innerHTML = '';
			el.innerHTML += '<div id="scoreboard_table" class="fhq_scoreboard_table"></div>';
			var tbl = document.getElementById("scoreboard_table");

			var content = '';
			for (var k in r.data) {
				content = '<div class="fhq_scoreboard_row">';
				var row = r.data[k];
				content += '<div class="fhq_scoreboard_cell">' + row.place + '</div>';
				var arr = [];
				for (var k2 in row.users) {
					var u = row.users[k2];
					arr.push(fhqgui.userIcon(u.userid, u.logo, u.nick));
				}
				content += '<div class="fhq_scoreboard_cell">' + row.rating + '</div>';
				content += '<div class="fhq_scoreboard_cell"><div class="scoreboard-user-tile">' + arr.join('</div><div class="scoreboard-user-tile">') + '</div></div>';
				content += '</div>';
				content += '</div>';
				tbl.innerHTML += content;
			}
			content = '';
		}
	);
}

fhq.ui.loadPageMore = function(){
	window.fhq.changeLocationState({'more':''});
	$('#content_page').html('<div class="fhq0016"></div>')
	var el = $('.fhq0016');

	var lst = [];
	lst.push({'id': 'feedback', 'name': 'Feedback', 'descr': 'Send feedback', 'icon': 'images/menu/feedback.png', 'load': fhq.ui.loadFeedback});
	lst.push({'id': 'games', 'name': 'Games', 'descr': 'List of games', 'icon': 'images/menu/games.png', 'load': fhq.ui.loadGames});
	lst.push({'id': 'tools', 'name': 'Tools', 'descr': 'Useful tools', 'icon': 'images/menu/tools_150x150.png', 'load': fhq.ui.loadTools});
	lst.push({'id': 'classbook', 'name': 'Classbook', 'descr': 'A set of useful articles', 'icon': 'images/menu/classbook_150x150.png', 'load': fhq.ui.loadClassbook});
	lst.push({'id': 'users', 'name': 'Users', 'descr': 'Rating of users', 'icon': 'images/menu/users_150x150.png', 'load': fhq.ui.loadRatingOfUsers});
	for(var i in lst){
		var o = lst[i];
		el.append(''
			+ '<div class="fhq0001" moreid="' + o.id + '">'
			+ '	<div class="fhq0008">'
			+ '		<div class="fhq0002" style="background-image: url(' + o.icon + ')"></div>' // TODO icon quest
			+ ' 	<div class="fhq0003">' + fhq.t(o.name) + '<br>'
			+ '			<div class="fhq0004">' + fhq.t(o.descr) + '</div>'
			+ '		</div>'
			+ '	</div>'
			+ '</div>'
			+ '<div class="fhq0015"></div>'
		);
	}
	
	$('.fhq0001').unbind().bind('click', function(){
		var moreid = $(this).attr('moreid');
		for(var i in lst){
			if(lst[i].id == moreid){
				var p = {}
				p[moreid] = '';
				window.fhq.changeLocationState(p);
				lst[i].load();
			}
		}
	});
}

fhq.ui.loadUserProfile = function(userid) {
	// alert(userid);

	var cp = document.getElementById('content_page');
	cp.innerHTML = 'Please wait...';

	// alert(createUrlFromObj(params));
	fhq.api.users.profile(userid).done(function (obj) {
			var pt = new FHQParamTable();
			pt.row('ID:', userid);
			pt.row('Your logo:', '<img id="user_logo" src="' + obj.data.logo + '"/>');
			pt.row('Your name:', '<div id="user_current_nick">' + obj.data.nick + '</div>');
			pt.row('Your role:', obj.data.role);
			for (var k in obj.games) {
				pt.row('Game "' + obj.games[k].title + '" (' + obj.games[k].type_game + '):', obj.games[k].score);
			}
			pt.skip();
			pt.row('Update logo:', 'PNG: <input id="user_new_logo" type="file" accept="image/png" required/>');
			pt.row('', '<div class="fhqbtn" onclick="updateUserLogo(' + userid + ');">Upload</div>');
			
			pt.skip();
			pt.row('Update nick:', '<input id="user_new_nick" type="text" value="' + obj.data.nick + '"/>');
			pt.row('', '<div class="fhqbtn" onclick="changeUserNick(null);">Change name</div>');
			pt.skip();
			pt.row('Country:', '<input id="edit_user_country" type="text" value="'+obj.profile.country+'"/>');
			pt.row('City:', '<input id="edit_user_city" type="text" value="'+obj.profile.city+'"/>');
			pt.row('University:', '<input id="edit_user_university" type="text" value="'+obj.profile.university+'"/>');
			pt.row('', '<div class="fhqbtn" onclick="update_profile_location();">Update</div>');
			pt.skip();

			// todo change password
			pt.row('Old password:', '<input id="userpage_old_password" type="password" value=""/>');
			pt.row('New password:', '<input id="userpage_new_password" type="password" value=""/>');
			pt.row('New password(confirm):', '<input id="userpage_new_password_confirm" type="password" value=""/>');
			pt.row('', '<div class="fhqbtn" onclick="userpage_changeUserPassword();">Change password</div>');
			pt.skip();

			cp.innerHTML = pt.render();
		}
	).fail(function(r){
		content = obj.error.message;
		cp.innerHTML = content;
		return;
	});
}

fhq.ui.loadNewFeedback = function() {
	window.fhq.changeLocationState({'new_feedback':''});
	$('.fhq0044').hide();
	$('.fhq0043').hide();
	
	$('#content_page').html('<div class="fhq0046"></div>')
	$('#content_page').append('<div class="fhq0049"><div class="fhq0050"></div></div>')
	var el = $('.fhq0046');
	el.append('<h1>Feedback</h1>');
	
	el.append('<div class="fhq0048">' + fhq.t("Target") + ':</div>');
	el.append(''
		+ '<select class="fhq0047" id="newfeedback_type">'
		+ '	<option value="question">' + fhq.t("question") + '</option>'
		+ '	<option value="complaint">' + fhq.t("complaint") + '</option>'
		+ '	<option value="defect">' + fhq.t("defect") + '</option>'
		+ '	<option value="error">' + fhq.t("error") + '</option>'
		+ '	<option value="approval">' + fhq.t("approval") + '</option>'
		+ '	<option value="proposal">' + fhq.t("proposal") + '</option>'
		+ '</select>');
	
	if(fhq.userinfo){
		el.append('<input class="fhq0047" type="hidden" id="newfeedback_from" value="' + fhq.userinfo.email + '">');
	}else{
		el.append('<div class="fhq0048">' + fhq.t("From") + ':</div>');
		el.append('<input class="fhq0047" type="text" id="newfeedback_from" placeholder="youmail@domain.com" value="">');
	}
	
	el.append('<div class="fhq0048">' + fhq.t("Message") + ':</div>');
	el.append('<textarea id="newfeedback_text"></textarea><br><br>');
	el.append('<div class="fhqbtn" id="newfeedback_send" onclick="fhq.ui.insertFeedback()">' + fhq.t("Send") + '</div>');
}

fhq.ui.insertFeedback = function(){
	var data = {};

	data.type = $('#newfeedback_type').val();
	data.from = $('#newfeedback_from').val();
	data.text = $('#newfeedback_text').val();
	$('.fhq0046').hide();
	$('.fhq0049').show();

	fhq.api.feedback.insert(data).done(function(){
		fhq.ui.loadFeedback();
	}).fail(function(r){
		$('.fhq0046').show();
		$('.fhq0049').hide();
	
		console.error(r);
		var msg = '';
		if(r && r.responseJSON){
			msg = r.responseJSON.error.message;
		}else{
			msg += 'Error (' + r.status + ')';
		}
		
		fhq.ui.showModalDialog({
			'header' : fhq.t('Error'),
			'content' : msg,
			'buttons' : ''
		});
	})
};

fhq.ui.loadGames = function() {
	window.fhq.changeLocationState({'games':''});
	
	$('#content_page').html('<div class="fhq0021"></div>');
	fhq.api.games.list().done(function(r){
		console.log(r);
		
		var el = $('.fhq0021');

		for (var k in r.data) {
			if (r.data.hasOwnProperty(k)) {
				el.append(fhq.ui.gameView(r.data[k]));
			}
		}
	}).fail(function(r){
		$('#content_page').html('fail');
	});
}

fhq.ui.gameView = function(game, currentGameId) {
	var content = ''
	+ '<div class="fhq0023">'
	+ '		<div class="fhq0024">'
	+ '			<div class="fhq0025">'
	+ '				<div class="fhq0030" style="background-image: url(' + game.logo + ')" ></div>'
	+ '</div>';
	content += '		<div class="fhq0026">\n';
	content += '			<div class="fhq0029">' + game.title + ' (Maximal score: ' + game.maxscore + ')</div>';
	content += '			<div class="fhq0027">' + game.type_game + ', ' + game.date_start + ' - ' + game.date_stop + '</div>';
	content += '			<div class="fhq0027">' + fhq.t('Organizators') + ': ' + game.organizators + '</div>';
	content += '			<div class="fhq0031">' + game.description + '</div>';
	content += '			<div class="fhq0032">';
	var perms = game.permissions;
	
	if (perms['delete'] == true)
		content += '<div class="fhqbtn" onclick="formDeleteGame(' + game.id + ');">' + fhq.t('Delete') + '</div>';

	if (perms['update'] == true)
		content += '<div class="fhqbtn" onclick="formEditGame(' + game.id + ');">' + fhq.t('Edit') + '</div>';
		
	if (perms['export'] == true)
		content += '<div class="fhqbtn" onclick="fhqgui.exportGame(' + game.id + ');">' + fhq.t('Export') + '</div>';

	content += '			</div>';
	content += '		</div>';
	content += '	</div>';
	content += '</div>'
	content += '<div class="fhq0028"></div>';
	return content;
}

fhq.ui.loadFeedback = function() {
	window.fhq.changeLocationState({'feedback':''});
	$('#content_page').html('<div class="fhq0021"></div>');
	var el = $('.fhq0021');
	
	fhq.api.feedback.list().done(function(obj){
		var content = '';
		
		for (var k in obj.data.feedback) {
			content += '';
			if (obj.data.feedback.hasOwnProperty(k)) {
				var f = obj.data.feedback[k];

				content += '\n<div class="fhq0034">\n';
				content += '	<div class="fhq0035">\n';
				content += '		<div class="fhq0036"><div class="fhq0038" style="background-image: url(' + f.logo + ')"></div></div>\n';
				content += '		<div class="fhq0037">\n';
				content += '			<div class="fhq_event_caption">[' + f.type + ', ' + f.dt + ', {' + f.nick + '}]</div>';
				content += '			<div class="fhq_feedback_text"><pre>' + f.text + '</pre></div>';
				content += '			<div class="fhq_event_caption">'; 
				content += '				<div class="fhqbtn" onclick="formInsertFeedbackMessage(' + f.id + ');">Add message</div>';
				if (obj.access == true) {
					content += '				<div class="fhqbtn" onclick="deleteConfirmFeedback(' + f.id + ');">Delete</div>';
					content += '				<div class="fhqbtn" onclick="formEditFeedback(' + f.id + ');">Edit</div>';
				}
				content += '			</div>';
				
				content += '			<div class="fhq_event_caption">'; 
				/*content += '				<div class="fhqbtn" onclick="deleteConfirmEvent(' + f.id + ');">Delete</div>';
				content += '				<div class="fhqbtn" onclick="formEditEvent(' + f.id + ');">Edit</div>';*/
				
				for (var k1 in f.messages) {
					var m = f.messages[k1];
					content += '\n<div class="fhq0039">\n';
					content += '	<div class="fhq0035">\n';
					content += '		<div class="fhq0036"><div class="fhq0038" style="background-image: url(' + m.logo + ')"></div></div>\n';
					content += '		<div class="fhq0037">\n';
					content += '			<div class="fhq0040"></div>';
					content += '			<div class="fhq_event_caption">[' + m.dt + ', {' + m.nick + '}]</div>';
					content += '			<div class="fhq_feedback_text"><pre>' + m.text + '</pre></div>';
					if (obj.access == true) {
						content += '			<div class="fhq_event_caption">'; 
						content += '				<div class="fhqbtn" onclick="deleteConfirmFeedbackMessage(' + m.id + ');">Delete</div>';
						content += '				<div class="fhqbtn" onclick="formEditFeedbackMessage(' + m.id + ');">Edit</div>';
						content += '			</div>';
					}
					content += '		</div>'; // fhq_event_info_cell_content
					content += '	</div>'; // fhq_event_info_row
					content += '</div><br>'; // fhq_event_info
					/*content += '				<div class="fhqbtn" onclick="deleteConfirmEvent(' + f.id + ');">Delete</div>';
					content += '				<div class="fhqbtn" onclick="formEditEvent(' + f.id + ');">Edit</div>';*/
				}
				content += '			</div>';

				content += '		</div>'; // fhq_event_info_cell_content
				content += '	</div>'; // fhq_event_info_row
				content += '</div><br>'; // fhq_event_info
			}
			content += '';
		}
		el.html(content);
		
	}).fail(function(r){
		el.html(r.responseJSON.error.message);
	});
}

fhq.ui.loadUserInfo = function(uuid){
	fhq.ws.user({uuid: uuid}).done(function(response){
		var u = response.data;
		var pt = new FHQParamTable();
		pt.row('ID:', u.id);
		pt.row('Logo:', '<img src="' + u.logo + '">');
		pt.row('UUID:', u.uuid);
		pt.row('Email:', u.email);
		pt.row('Nick:', u.nick);
		pt.row('Role:', u.role);
		pt.row('Last IP:', u.last_ip);
		pt.row('Created:', u.dt_create);
		pt.row('Last Login:', u.dt_last_login);
		pt.skip();
		for(var p in u.profile){
			pt.row('Profile "' + p + '"', u.profile[p]);
		}
		pt.skip();
		
		$('.fhqrightinfo').html(pt.render());
	});
}

window.fhq.ui.updateUsers = function(){
	var params = {};
	params.filter_text = $('#users_filter_text').val();
	params.filter_role = $('#users_filter_role').val();
	fhq.ws.users(params).done(function(response){
		$('.fhqleftlist .users .content').html('');
		$('#users_found').html('Found: ' + response.data.length);
		for(var i in response.data){
			var u = response.data[i];
			$('.fhqleftlist .users .content').append('<div class="fhqleftitem" uuid="' + u.uuid + '"><div class="name">' + u.nick + ' (' + u.email + ')</div></div>');
		}
		$('.users .fhqleftitem').unbind('click').bind('click', function(){
			fhq.ui.loadUserInfo($(this).attr('uuid'));
		});
	})
}

window.fhq.ui.loadUsers = function(){
	$('#content_page').html('<div class="fhqrightinfo center"></div><div class="fhqleftlist"></div>');
	$('.fhqleftlist').html('');
	var list = '<div class="users">'
	+ '<div class="icon">Users</div>'
	+ '<div class="filter"><input type="text" id="users_filter_text" value="" placeholder="Email or nick.."/></div>'
	+ '<div class="filter">Role:   <select id="users_filter_role" value="">'
	+ '<option selected="" value="">*</option>'
	+ '<option value="user">User</option>'
	+ '<option value="tester">Tester</option>'
	+ '<option value="admin">Admin</option>'
	+ '</select></div>'
	+ '<div class="filter"><div class="fhqbtn" id="users_search">Search</div></div>'
	+ '<div class="filter" id="users_found"></div>'
	+ '<div class="content"></div>'
	+ '</div>';
	$('.fhqleftlist').append(list);
	$('.fhqleftlist .users .content').html('Loading...');
	$('#users_search').unbind('click').bind('click', fhq.ui.updateUsers);
	fhq.ui.updateUsers();
}


window.fhq.ui.updateQuests = function(){
	
	// todo filters
	var params = {};
	params.name_contains = $('#quests_filter_name_contains').val();
	params.subjects = $('#quests_filter_subject').val();
	var status = $('#quests_filter_status').val();
	params.open = true;
	params.completed = true;

	if(status == "open"){
		params.completed = false;
	}else if(status == "completed"){
		params.open = false;
	}
	
	// params.open
	fhq.api.quests.list(params).done(function(response){
		console.log(response);
		var previous_value = $('#quests_filter_subject').val();
		console.log(previous_value);
		$('#quests_filter_subject').html('');
		$('#quests_filter_subject').append('<option value="">*</option>');
		for(var s in response.subjects){
			$('#quests_filter_subject').append('<option value="' + s + '">' + s + ' (' + response.subjects[s] + ')</option>');
		}
		// $('#quests_filter_subject option:contains(' + previous_value + ')').prop({selected: true});
		$('#quests_filter_subject').val(previous_value);
		
		$('#quests_found').html(fhq.t('Opened') + ": " + response.status.open + "; " + fhq.t('Completed') + ": " + response.status.completed);
		var lastSubject = "";
		var len = response.data.length;
		var qs = response.data;
		var el = $('.fhqleftlist .quests .content');
		el.html('');
		for(var i = 0; i < len; i++){
			var q = qs[i];
			if(q.subject != lastSubject){
				lastSubject = q.subject;
				el.append('<div class="icon ' + q.subject + '">' + q.subject + '</div>');
			}
			$('.fhqleftlist .quests .content').append('<div class="fhqleftitem ' + q.status + '" questid="' + q.questid + '"><div class="name">' + q.name + '</div> <div class="score">+' + q.score + '</div></div>');
		}
		$('.fhqleftlist .quests .content .fhqleftitem').unbind('click').bind('click', function(e){
			fhq.ui.loadQuest($(this).attr("questid"));
		});
	}).fail(function(r){
		console.error(r);
	});
}

// TODO redesign
function createQuestRow(name, value)
{
	return '<div class="quest_info_row">\n'
	+ '\t<div class="quest_info_param">' + name + '</div>\n'
	+ '\t<div class="quest_info_value">' + value + '</div>\n'
	+ '</div>\n';
}

fhq.ui.createQuestForm = function(){
	var content = '';
	content += '<div class="quest_info_table">\n';
	content += createQuestRow('Quest UUID:', '<input type="text" id="newquest_quest_uuid" value="' + guid() + '"/>');
	content += createQuestRow('Name:', '<input type="text" id="newquest_name" value=""/>');
	content += createQuestRow('Text:', '<textarea id="newquest_text"></textarea>');
	content += createQuestRow('Score(+):', '<input type="text" id="newquest_score" value="100"/>');
	content += createQuestRow('Subject:', fhqgui.combobox('newquest_subject', 'trivia', fhq.getQuestTypes()));
	// content += createQuestRow('Author Id:', '<input type="text" id="newquest_author_id" value=""/>');
	content += createQuestRow('Author:', '<input type="text" id="newquest_author" value=""/>');
	content += createQuestRow('Answer:', '<input type="text" id="newquest_answer" value=""/>');
	content += createQuestRow('State:', fhqgui.combobox('newquest_state', 'open', fhq.getQuestStates()));
	content += createQuestRow('Description State:', '<textarea id="newquest_description_state"></textarea>');
	content += createQuestRow('', '<div class="fhqbtn" onclick="fhq.ui.createQuest();">Create</div>');
	content += '</div>'; // quest_info_table
	showModalDialog(content);
}

fhq.ui.createQuest = function() {
	var params = {};
	params["quest_uuid"] = document.getElementById("newquest_quest_uuid").value;
	params["name"] = document.getElementById("newquest_name").value;
	params["text"] = document.getElementById("newquest_text").value;
	params["score"] = document.getElementById("newquest_score").value;
	params["subject"] = document.getElementById("newquest_subject").value;
	params["idauthor"] = 0; // document.getElementById("newquest_author_id").value;
	params["author"] = document.getElementById("newquest_author").value;
	params["answer"] = document.getElementById("newquest_answer").value;
	params["state"] = document.getElementById("newquest_state").value;
	params["description_state"] = document.getElementById("newquest_description_state").value;

	fhq.api.quests.insert(params).done(function(r){
		closeModalDialog();
		fhq.ui.updateQuests();
		fhq.ui.loadQuest(r.data.quest.id);
	}).fail(function(){
		alert("fail");
	})
};

fhq.ui.importQuest = function() {
	var files = document.getElementById('importquest_zip').files;
	if (files.length == 0) {
		alert("Please select file");
		return;
	}
	/*for(i = 0; i < files.length; i++)
		alert(files[i].name);*/
	
	send_request_post_files(
		files,
		'api/quests/import/',
		createUrlFromObj({}),
		function (obj) {
			if (obj.result == "fail") {
				alert(obj.error.message);
				return;
			}
			closeModalDialog();
			fhq.ui.updateQuests();
			fhq.ui.loadQuest(obj.data.quest.id);
		}
	);
}
	
fhq.ui.importQuestForm = function(){
	var pt = new FHQParamTable();
	pt.row('', 'ZIP: <input id="importquest_zip" type="file" required/>');
	pt.row('', '<div class="fhqbtn" onclick="fhq.ui.importQuest();">Import</div>');
	pt.skip();
	showModalDialog(pt.render());
}

fhq.ui.capitalizeFirstLetter = function(s) {
    return s.charAt(0).toUpperCase() + s.slice(1);
}

fhq.ui.loadStatSubjectsQuests = function(){
	$('#content_page').html('<div class="fhq0006">Loading...</div>');
	fhq.api.quests.stats_subjects().done(function(r){
		console.log(r);
		$('.fhq0006').html('');
		var el = $('.fhq0006');
		for(var i in r.data){
			var o = r.data[i];
			el.append(''
				+ '<div class="fhq-quests-subject" subject="' + o.subject + '">'
				+ ' <div class="fhq-quests-subject-row">'
				+ ' 	<div class="fhq-quests-subject-cell logo" style="background-image: url(images/quests/' + o.subject + '.png)">'
				+ ' 	</div>'
				+ ' 	<div class="fhq-quests-subject-cell text">'
				+ fhq.ui.capitalizeFirstLetter(o.subject) + '<br>'
				+ '<div class="descr">' + fhq.t(o.subject + '_description') + '</div>'
				+ ' 	</div>'
				+ ' 	<div class="fhq-quests-subject-cell count">'
				+ '(' + o.count + ' quests)'
				+ ' 	</div>'
				+ ' </div>'
				+ ' <div class="fhq-quests-subject-row">'
				+ ' </div>'
				+ ' <div class="fhq-quests-subject-row">'
				+ ' </div>'
				+ '</div>'
				+ '<div class="fhq-quests-subject-skip"></div>'
			)
			
		}
		
		$('.fhq-quests-subject').unbind().bind('click', function(){
			window.location = '?subject=' + $(this).attr('subject');
		})
	}).fail(function(r){
		console.error(r);
		$('.fhq0006').html('Failed');
	});
}


fhq.ui.loadQuestsBySubject = function(subject){
	$('#content_page').html('<div class="fhq0005">Loading...</div>');
	var params = {};
	params.subject = subject;
	fhq.api.quests.list(params).done(function(r){
		$('.fhq0005').html('');
		for(var i in r.data){
			var q = r.data[i];
			console.log(q);
			status
			$('.fhq0005').append(''
				+ '<div class="fhq0001 ' + (q.status == "completed" ? 'fhq0060' : '') + '" questid="' + q.questid + '">'
				+ '	<div class="fhq0008">'
				+ '		<div class="fhq0002"></div>' // TODO icon quest
				+ ' 	<div class="fhq0003">' + q.name + ' (+' + q.score + ')<br>' // TODO passed quest
				+ '			<div class="fhq0004">Quest ' + q.questid + '</div>'
				+ '		</div>'
				+ ' 	<div class="fhq0007">' + fhq.t('Solved') + ': ' + q.solved + '</div>'
				+ '	</div>'
				+ '</div>'
				+ '<div class="fhq0015"></div>'
			);
		}
		
		$('.fhq0001').unbind().bind('click', function(){
			window.location = '?quest=' + $(this).attr('questid');
		});
	}).fail(function(r){
		console.error(r)
		$('.fhq0005').html('Failed');
	});
}

fhq.ui.deleteQuest = function(id){
	if (!confirm("Are you sure that wand remove this quest?"))
		return;

	var params = {};
	params.questid = id;
	send_request_post(
		'api/quests/delete.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				fhq.ui.updateQuests();
				$('.fhqrightinfo').html('Quest removed');
			} else {
				alert(obj.error.message);
			}
		}
	);
}

/* fhq_quests.js todo redesign */

function updateQuest(id)
{
	var params = {};
	params["questid"] = id;
	params["name"] = document.getElementById("editquest_name").value;
	params["text"] = document.getElementById("editquest_text").value;
	params["score"] = document.getElementById("editquest_score").value;
	params["subject"] = document.getElementById("editquest_subject").value;
	params["idauthor"] = 0;
	params["author"] = document.getElementById("editquest_author").value;
	params["answer"] = document.getElementById("editquest_answer").value;
	params["state"] = document.getElementById("editquest_state").value;
	params["description_state"] = document.getElementById("editquest_description_state").value;

	// alert(createUrlFromObj(params));

	send_request_post(
		'api/quests/update.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				fhq.ui.updateQuests();
				fhq.ui.loadQuest(id);
			} else {
				alert(obj.error.message);
			}
		}
	);
}

// http://stackoverflow.com/questions/11076975/insert-text-into-textarea-at-cursor-position-javascript
function editQuestAddLink(filepath, filename, as) {
	var t = document.getElementById('editquest_text');
	var val = '';
	if (as == 'asfile')
		val = '<a class="fhqbtn" target="_ablank" href="' + filepath + '">Download ' + filename + '</a>';
	else if (as == 'asimg')
		val = '<img width="250px" src="' + filepath + '"/>';
	else
		val = filename;
		
	//IE support
    if (document.selection) {
        t.focus();
        sel = document.selection.createRange();
        sel.text = val;
    }
    //MOZILLA and others
    else if (t.selectionStart || t.selectionStart == '0') {
        var startPos = t.selectionStart;
        var endPos = t.selectionEnd;
        t.value = t.value.substring(0, startPos)
            + val
            + t.value.substring(endPos, t.value.length);
    } else {
        t.value += val;
    }
};

function uploadQuestFiles(questid) {
	var files = document.getElementById('editquest_upload_files').files;
	/*for(i = 0; i < files.length; i++)
		alert(files[i].name);*/
	
	send_request_post_files(
		files,
		'api/quests/files_upload.php',
		createUrlFromObj({"questid": questid}),
		function (obj) {
			if (obj.result == "fail") {
				alert(obj.error.message);
				return;
			}
			alert('uploaded!');
			formEditQuest(questid);
		}
	);
}

function removeQuestFile(id, questid)
{
	var params = {};
	params["fileid"] = id;
	// alert(createUrlFromObj(params));

	send_request_post(
		'api/quests/files_remove.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				alert("removed!");
				formEditQuest(questid);
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function formEditQuest(id)
{
	closeModalDialog();
	var params = {};
	params.questid = id;
	send_request_post(
		'api/quests/get_all.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				showModalDialog(obj.error.message);
				return;
			}
			var content = '\n';

			/*content += createQuestRow('Quest UUID:', '<input type="text" id="newquest_quest_uuid" value="' + guid() + '"/>');
			content += createQuestRow('', '<div class="fhqbtn" onclick="createQuest();">Create</div>');*/
			
			if (!obj.quest) {
				showModalDialog("error");
				return;
			}
			content += '<div class="quest_info_table">\n';
			
			content += createQuestRow('Quest ID: ', obj.quest);
			content += createQuestRow('Game: ', obj.data.game_title);
			content += createQuestRow('Name:', '<input type="text" id="editquest_name" value="' + obj.data.name + '"/>');
			content += createQuestRow('Text:', '<textarea id="editquest_text">' + obj.data.text + '</textarea>');
			content += createQuestRow('Files:', '<div id="editquest_files"></div>');
			content += createQuestRow('', '<input id="editquest_upload_files" multiple required="" type="file">' 
				+ ' <div class="fhqbtn" onclick="uploadQuestFiles(' + obj.quest + ');">Upload files</div>');
			content += createQuestRow('Score(+):', '<input type="text" id="editquest_score" value="' + obj.data.score + '"/>');
			content += createQuestRow('Subject:', fhqgui.combobox('editquest_subject', obj.data.subject, fhq.getQuestTypes()));
			// content += createQuestRow('Author Id:', '<input type="text" id="editquest_authorid" value="' + obj.data.authorid + '"/>');
			content += createQuestRow('Author:', '<input type="text" id="editquest_author" value="' + obj.data.author + '"/>');
			content += createQuestRow('Answer:', '<input type="text" id="editquest_answer" value="' + obj.data.answer + '"/>');
			content += createQuestRow('State:', fhqgui.combobox('editquest_state', obj.data.state, fhq.getQuestStates()));
			content += createQuestRow('Description State:', '<textarea id="editquest_description_state">' + obj.data.description_state + '</textarea>');
			content += createQuestRow('', '<div class="fhqbtn" onclick="updateQuest(' + obj.quest + ');">Update</div>'
				+ '<div class="fhqbtn" onclick="fhq.ui.loadQuest(' + obj.quest + ');">Cancel</div>'
			);

			content += '</div>';
			content += '<div id="quest_error"><div>';
			content += '\n';
			showModalDialog(content);
			for (var k in obj.data.files) {
				var f = document.getElementById('editquest_files');
				f.innerHTML += obj.data.files[k].filename + ' '
				+ '<div class="fhqbtn" onclick="editQuestAddLink(\'' + obj.data.files[k].filepath + '\', \'' + obj.data.files[k].filename + '\', \'asfile\');">Add link as file</div> '
				+ '<div class="fhqbtn" onclick="editQuestAddLink(\'' + obj.data.files[k].filepath + '\', \'' + obj.data.files[k].filename + '\', \'asimg\');">Add link as img</div> '
				+ ' <a class="fhqbtn" target="_ablank" href="' + obj.data.files[k].filepath + '">Download</a>' 
				+ ' <div class="fhqbtn" onclick="removeQuestFile(' + obj.data.files[k].id + ', ' + obj.quest + ');">Remove</div><br>';
			}
		}
	);
}

window.fhq.ui.refreshHints = function(questid, hints, perm_edit){
	var result = "";
	var i = 1;
	for(var h in hints){
		var hint = hints[h];
		result += '<div><b>Hint ' + i + ':</b> <pre style="display: inline-block;">' + $('<div/>').text(hint.text).html() + '</pre>' + (perm_edit ? ' <div class="fhqbtn deletehint" hintid="' + hint.hintid + '">' + fhq.t('Delete') + '</div>' : '') + '</div>';
		i++;
	}
	result += (perm_edit ? '<div><input type="text" id="quest_addhinttext"/> <div class="fhqbtn" id="quest_addhint">' + fhq.t('Add') + '</div></div>' : '');

	$('#newquestinfo_hints').html(result);

	$('.deletehint').unbind().bind('click', function(e){
		var hintid = parseInt($(this).attr('hintid'),10);
		fhq.ui.deleteHint(hintid, questid);
	});

	$('#quest_addhint').unbind().bind('click', function(){
		fhq.ui.addHint(questid);
	});
}

window.fhq.ui.deleteHint = function(hintid, questid){
	fhq.ws.deletehint({"hintid": hintid}).done(function(){
		fhq.ws.hints({"questid": questid}).done(function(response){
			fhq.ui.refreshHints(questid, response.data, true);
		}).fail(function(){
			alert("Problem with get hints from ws");
		});
	}).fail(function(){
		console.error("Problem with delete hint");
	});
}

window.fhq.ui.addHint = function(questid){
	var val = $('#quest_addhinttext').val();
	fhq.ws.addhint({questid: questid, hint: val}).done(function(){
		$('#quest_addhinttext').val('');
		fhq.ws.hints({"questid": questid}).done(function(response){
			fhq.ui.refreshHints(questid, response.data, true);
		}).fail(function(){
			alert("Problem with get hints from ws");
		});
	}).fail(function(){
		console.error("Problem with add hint");
	});
}

window.fhq.ui.loadQuest = function(id){
	$('#content_page').html('<div class="fhq0009"></div>')
	var el = $('.fhq0009');
	el.html('Loading...');
	fhq.api.quests.quest(id).done(function(response){
		var questid = parseInt(id,10);
		var q = response.data;
		var perm_edit = false;
		var perm_delete = false;
		if(response.permissions){
			perm_edit = response.permissions.edit;
			perm_delete = response.permissions['delete'];
		}

		fhq.changeLocationState({quest: q.questid});
		el.html('');
		el.append(''
			+ '<div class="fhq0010">'
			+ '	<div class="fhq0012">'
			+ '		<div class="fhq0011"></div>'
			+ '		<div class="fhq0013">'
			+ ' 		<a href="?subject=' + q.subject + '">' + fhq.ui.capitalizeFirstLetter(q.subject) + '</a> / <a href="?quest=' + q.questid + '">Quest ' + q.questid + '</a>' 
			+ ' 		(' + fhq.t('Quest ' + q.status) + ')'
			+ '			<div class="fhq0014">' + q.name + ' (+' + q.score + ')</div>'
			+ '		</div>'
			+ '	</div>'
			+ '</div>');

		$('.fhq0011').css({ // game logo
			'background-image': 'url(' + q.game_logo + ')'
		});
		
		var c = '<div class="fhq0051">';
		if(response.permissions){
			var p = response.permissions;
			c += (p.edit ? '<div class="fhqbtn" id="quest_edit">' + fhq.t('Edit') + '</div>' : '');
			c += (p['delete'] ? '<div class="fhqbtn" id="quest_delete">' + fhq.t('Delete') + '</div>' : '');
			c += (p.edit ? '<div class="fhqbtn" id="quest_export">' + fhq.t('Export') + '</div>': '')
		}
		c += '<div class="fhqbtn" id="quest_report">' + fhq.t('Report an error') + '</div>';
		c += '</div>'
		el.append(c);
		
		$('#quest_report').unbind().bind('click', function(){
			fhq.ui.showFeedbackDialog(
				'error',
				fhq.t('Report an error'),
				'Game: "' + q.game_title + '"\n'
				+ 'Quest: ' + q.name + ', ID: #' + q.questid + '\n'
				+ 'Comment:\n'
			);
		});

		$('#quest_delete').unbind().bind('click', function(){
			fhq.ui.deleteQuest(q.questid);
		});
		
		$('#quest_edit').unbind().bind('click', function(){
			formEditQuest(q.questid);
		})

		$('#quest_export').unbind().bind('click', function(){
			fhqgui.exportQuest(q.questid);
		})

		el.append('<div class="fhq0051"><br>'
			+ '<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>'
			+ '<script src="//yastatic.net/share2/share.js"></script>'
			+ '<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,blogger,reddit,linkedin,lj,viber,whatsapp,skype,telegram"></div>'
			+ '</div>'
		);
		
		el.append(
			'<div class="newquestinfo_details">'
			+ '<div class="newquestinfo_details_title">' + fhq.t('Details') + '</div>'
			+ '	<div class="newquestinfo-details-left"> '
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('Subject') + ':</div>'
			+ '			<div class="newquestinfo-details-cell">' + q.subject + '</div>'
			+ '		</div>'
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('Score') + ':</div>'
			+ '			<div class="newquestinfo-details-cell">+' + q.score + '</div>'
			+ '		</div>'
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('Status') + ':</div>'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('status_' + q.status) + (q.status == 'completed' ? ' (' + q.dt_passed + ')' : '') + '</div>'
			+ '		</div>'
			+ '	</div>'
			+ '	<div class="newquestinfo-details-right"> '
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('State') + ':</div>'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('state_' + q.state) + '</div>'
			+ '		</div>'
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('Solved') + ':</div>'
			+ '			<div class="newquestinfo-details-cell">' + q.solved + ' ' + fhq.t('users_solved') + '</div>'
			+ '		</div>'
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('Author') + ':</div>'
			+ '			<div class="newquestinfo-details-cell">' + q.author + '</div>'
			+ '		</div>'
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('Copyright') + ':</div>'
			+ '			<div class="newquestinfo-details-cell"><a href="?game=' + q.gameid + '">' + q.game_title + '</a></div>'
			+ '		</div>'			
			+ '	</div>'
			+ '</div>'
		)

		el.append(
			'<div class="newquestinfo_description">'
			+ '<div class="newquestinfo_description_title">' + fhq.t('Description') + '</div>'
			+ '<pre>' + q.text + '</pre>'
			+ '</div>'
		)

		if(q.files && q.files.length > 0){
			var files1 = '';						
			for (var k in q.files) {
				files1 += '<a class="fhqbtn" href="' + q.files[k].filepath + '" target="_blank">'+ q.files[k].filename + '</a> ';
			}
			
			el.append(
				'<div class="newquestinfo_attachments">'
				+ '<div class="newquestinfo_attachments_title">' + fhq.t('Attachments') + '</div>'
				+ files1
				+ '</div>'
			)
		}

		if(q.hints && q.hints.length > 0 || fhq.isAdmin()){
			var hints = '<div class="fhq0051">'
				+ '<div class="fhq0053 hide" id="quest_show_hints">' + fhq.t('Hints') + '</div>'
				+ '<div id="newquestinfo_hints" style="display: none;">';
			hints += '</div></div>';
			el.append(hints);
			fhq.ui.refreshHints(questid, q.hints, perm_edit);
			$('#quest_show_hints').unbind().bind('click', function(){
				if($('#newquestinfo_hints').is(":visible")){
					$('#newquestinfo_hints').hide();
					$('#quest_show_hints').removeClass('show');
					$('#quest_show_hints').addClass('hide');
				}else{
					$('#newquestinfo_hints').show();
					$('#quest_show_hints').removeClass('hide');
					$('#quest_show_hints').addClass('show');
				}
			});
		}
		
		if(q.dt_passed == null){
			if(fhq.isAuth()){
				el.append(
					'<div class="newquestinfo_passquest">'
					+ '<div class="newquestinfo_passquest_title">' + fhq.t('Answer') + '</div>'
					+ '<input id="quest_answer" type="text" onkeydown="if (event.keyCode == 13) this.click();"/> '
					+ '<div class="fhqbtn" id="newquestinfo_pass">' + fhq.t('Pass the quest') + '</div>'
					+ '<div id="quest_pass_error"></div>'
					+ '</div>'
				);
				$('#newquestinfo_pass').unbind().bind('click', function(){
					var answer = $('#quest_answer').val();
					fhq.api.quests.pass(q.questid, answer).done(function(response){
						fhq.ui.loadQuest(q.questid);
					}).fail(function(r){
						$('#quest_pass_error').html(r.responseJSON.error.message);
						if(fhq.ui.isShowMyAnswers()){
							fhq.ui.updateMyAnswers(q.questid);
						}
					});
				});
				
				el.append(
					'<div class="fhq0051">'
					+ '<div class="fhq0053 hide" id="quest_show_my_answers">' + fhq.t('My Answers') + '</div>'
					+ '<pre id="newquestinfo_user_answers" style="display: none;"></pre>'
					+ '</div>'
				);
				
				$('#quest_show_my_answers').unbind().bind('click', function(){
					fhq.ui.loadMyAnswers(q.questid);
				});
			}else{
				el.append(
					'<div class="newquestinfo_passquest">'
					+ '<div class="newquestinfo_passquest_title">' + fhq.t('Answer') + '</div>'
					+ fhq.t('Please authorize for pass the quest')
					+ '</div>'
				);
			}
		}
		
		var writeups = ''
			+ '<div class="fhq0051">'
			+ '		<div class="fhq0053 hide" id="quest_show_writeups">' + fhq.t('Write Up') + '</div>'
			+ '		<div class="fhq0052" style="display: none;"></div>'
			+ '</div>'
		el.append(writeups);

		// fhq.ui.refreshHints(questid, q.hints, perm_edit);
		$('#quest_show_writeups').unbind().bind('click', function(){
			if($('.fhq0052').is(":visible")){
				$('.fhq0052').hide();
				$('#quest_show_writeups').removeClass('show');
				$('#quest_show_writeups').addClass('hide');
			}else{
				$('.fhq0052').show();
				$('#quest_show_writeups').removeClass('hide');
				$('#quest_show_writeups').addClass('show');
				fhq.ui.loadWriteUps(questid);
			}
		});
		
		el.append(
			'<div class="fhq0051">'
			+ '<div class="fhq0053 hide" id="quest_show_statistics">' + fhq.t('Statistics') + '</div>'
			+ '	<div id="statistics_content" style="display: none;">'
			+ ' <table><tr><td valign=top><canvas id="quest_chart" width="300" height="300"></canvas></td>'
			+ ' <td valign=top id="quest_stat_users"></td></tr></table>'
			+ '	</div>'
			+ '</div>'
		);

		if(q.solved != 0){
			$('#quest_show_statistics').unbind().bind('click', function(){
				if($('#statistics_content').is(":visible")){
					$('#statistics_content').hide();
					$('#quest_show_statistics').removeClass('show');
					$('#quest_show_statistics').addClass('hide');
				}else{
					$('#statistics_content').show();
					$('#quest_show_statistics').removeClass('hide');
					$('#quest_show_statistics').addClass('show');
					fhq.ui.updateQuestStatistics(q.questid);
				}
			});
		}
	}).fail(function(r){
		console.error(r);
		el.html(r.responseJSON.error.message);
	})
}

fhq.ui.loadWriteUps = function(questid){
	$('.fhq0052').html('...');
	fhq.ws.writeups({questid: questid}).done(function(r){
		if(r.data.length == 0){
			$('.fhq0052').html(fhq.t('No solutions yet'));  // TODO propose by user
		}else{
			var writeup = r.data[0];
			if(writeup.type == 'youtube_video'){
				$('.fhq0052').html('<iframe width="560" height="315" src="' + writeup.link + '" frameborder="0" allowfullscreen></iframe>');
			}else{
				$('.fhq0052').html('TODO');
			}
		}
	}).fail(function(r){
		$('.fhq0052').html(r.error);
	})
}

fhq.ui.updateMyAnswers = function(questid){
	fhq.statistics.myanswers(questid).done(function(response){
		var h = '';
		for (var i = 0; i < response.data.length; ++i) {
			var a = response.data[i];
			h += '<div class="fhq_task_tryanswer">[' + a.datetime_try + ', levenshtein: ' + a.levenshtein + '] ' + a.answer_try + '</div>';
		}
		$('#newquestinfo_user_answers').html(h);
	});
}

window.fhq.ui.isShowMyAnswers = function(){
	return $('#newquestinfo_user_answers').is(":visible");
}

window.fhq.ui.loadMyAnswers = function(questid){
	if(fhq.ui.isShowMyAnswers()){
		$('#newquestinfo_user_answers').hide();
		$('#quest_show_my_answers').removeClass('show');
		$('#quest_show_my_answers').addClass('hide');
				
	}else{
		$('#newquestinfo_user_answers').show();
		$('#quest_show_my_answers').removeClass('hide');
		$('#quest_show_my_answers').addClass('show');
		$('#newquestinfo_user_answers').html('Loading...');
		fhq.ui.updateMyAnswers(questid);
	}
}

window.fhq.ui.updateQuestStatistics = function(questid){
	fhq.api.quests.statistics(questid).done(function(response){
		var q = response.data;
		// quest_chart
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
		var ctx = document.getElementById('quest_chart').getContext("2d");
		var myNewChart = new Chart(ctx).Doughnut(data, options);
		
		// quest_stat_users
		var usrs = [];
		for (var u in q.users) {
			usrs.push(fhqgui.userIcon(q.users[u].userid, q.users[u].logo, q.users[u].nick));
		}
		$('#quest_stat_users').html('Users who solved this quest:<br>' + usrs.join(" "));
						
	});
}

fhq.ui.showFeedbackDialog = function(type, title, text){
	fhq.ui.showModalDialog(fhq.ui.templates.feedback_form(title));
	$('#feedback-type').val(type);
	$('#feedback-text').val(text);
}

fhq.ui.feedbackDialogSend = function(){
	var text = $('#feedback-text').val();
	var type = $('#feedback-type').val();
	var params = {};
	params.type = type;
	// params.from = from; // TODO
	params.text = text;
	fhq.api.feedback.insert(params).done(function(){
		fhq.ui.closeModalDialog();
	}).fail(function(response){
		if(response){
			alert(response.error.message);
		}
	})
}

fhq.ui.initChatForm = function(){
	
	$("#sendchatmessage_submit").unbind().bind('click', function(){
		var text = $('#sendchatmessage_text').val();
		if(text.trim() == ""){
			return;
		}
		$('#sendchatmessage_text').val('');
		fhq.ws.sendChatMessage({type: 'chat', message: text}); // async
	});
	
	$('#sendchatmessage_text').unbind().bind('keydown', function(event){
		if ( event.which == 13 ) {
			event.preventDefault();
			var text = $('#sendchatmessage_text').val();
			if(text.trim() == ""){
				return;
			}
			$('#sendchatmessage_text').val('');
			fhq.ws.sendChatMessage({type: 'chat', message: text}); // async
		}
	});
}


/* classbook */
window.fhq.ui.loadClassbookItem = function(link, cbid){
	console.log("link:" + link);
	$.ajax({
		url: link + "?t=" + Date.now(),
		type: 'GET'
	}).done(function(response){
		var a = link.split(".");
		var type = a[a.length-1].toUpperCase();
		var html = "";
		if(type == "MD"){
			var converter = new showdown.Converter(),
			html = converter.makeHtml(response);
		}else{
			// html
			html = response;
		}
		if(cbid != undefined){
			fhq.changeLocationState({'classbook': '', 'cbid': cbid});
		}
		$('.fhqrightinfo').html(html);
	}).fail(function(){
		$('.fhqrightinfo').html("Not found");
	})
}

window.fhq.ui.loadClassbookSubmenu = function(submenu){
	fhq.ui.classbook_numbers.push(0);
	var len = submenu.length;
	for(var i = 0; i < len; i++){
		var o = submenu[i];
		var numbers_len = fhq.ui.classbook_numbers.length;
		fhq.ui.classbook_numbers[numbers_len-1] = fhq.ui.classbook_numbers[numbers_len-1] + 1;
		var num = fhq.ui.classbook_numbers.join('.');

		if(o.id)
			fhq.classbookCache[o.id] = o;

		if(o.link && o.name){
			$('.fhqleftlist .classbook .content').append('<div class="fhqleftitem" link="' + o.link + '" cbid="' + o.id + '" ><div class="name">' + num + ' ' + o.name + '</div></div>');	
		}else if(o.name){
			$('.fhqleftlist .classbook .content').append('<div class="fhqleftitem"><div class="name">' + num + ' ' + o.name + '</div></div>');	
		}
		
		if(o.submenu != undefined){
			fhq.ui.loadClassbookSubmenu(o.submenu);
		}
	}
	fhq.ui.classbook_numbers.pop();
}

window.fhq.ui.classbookSearchLinkByID = function(cbid){
	if(fhq.classbookCache[cbid]){
		return fhq.classbookCache[cbid].link;
	}
}

window.fhq.classbookCache = {};

window.fhq.ui.loadClassbook = function(){
	$('#content_page').html(''
		+ '<div class="fhqleftlist">'
		+ '		<div class="classbook">'
		+ ' 		<div class="icon">' + fhq.t('Classbook') + '</div>'
		+ ' 		<div>'
		+ '				<div id="addclassbookitem" class="fhqbtn">Add</div>'
		+ '			</div>'
		+ '			<div class="content"></div>'
		+ '		</div>'
		+ '</div>'
		+ '<div class="fhqrightinfo">text</div>'
	);
	
	// fhq.lang();
	
	fhq.ws.classbook().done(function(r){
		fhq.ui.classbook_numbers = [];
		fhq.classbookCache = {}
		fhq.ui.loadClassbookSubmenu(r.items);

		$('.fhqleftitem').unbind('click').bind('click', function(){
			var link = $(this).attr('link');
			var cbid = $(this).attr('cbid');
			fhq.ui.loadClassbookItem(link, cbid);
		});
	});

	// fhq.changeLocationState({updatedatabase: ''});	
	// $('.classbook .content').html('')
}

window.fhq.ui.templates = window.fhq.ui.templates || {};

fhq.ui.templates.singin = function(){
	var content = ''
		+ '<div id="signin-form">'
		+ '		<!-- img src="images/logo_middle.png" /><br><br -->'
		+ '		<!-- todo replace type="text" to type="email" (html5) -->'
		+ '		<input placeholder="your@email.com" id="signin-email" value="" type="text" onkeydown="if (event.keyCode == 13) fhq.ui.signin(); else fhq.ui.cleanupSignInMessages();">'
		+ '		<br><br>'
		+ '		<input placeholder="*****" id="signin-password" value="" type="password"  onkeydown="if (event.keyCode == 13) fhq.ui.signin(); else fhq.ui.cleanupSignInMessages();">'
		+ '		<br><br>'
		+ '		<font id="signin-error-message" color="#ff0000"></font>'
		+ '</div>';
	return {
		'header' : fhq.t('Sign-in'),
		'content': content,
		'buttons': '<div class="fhqbtn" onclick="fhq.ui.signin();">' + fhq.t('Sign-in') + '</div>'
	};
}

fhq.ui.templates.singup = function(){
	var content = ''
		+ '<div id="signup-form">'
		+ '		<input placeholder="your@email.com" id="signup-email" value="" type="text" onkeydown="if (event.keyCode == 13) fhqgui.signup(); else fhqgui.cleanupSignUpMessages();"/>'
		+ '		<br><br>'
		+ '		<img src="" id="signup-captcha-image"/>'
		+ '		<div class="fhqbtn" onclick="fhqgui.refreshSignUpCaptcha();"><img src="images/refresh.svg"/></div>'
		+ '		<br><br>'
		+ '		<input placeholder="captcha" id="signup-captcha" value="" type="text" onkeydown="if (event.keyCode == 13) fhqgui.signup(); else fhqgui.cleanupSignUpMessages();"/>'
		+ '		<br><br>'
		+ '		<font id="signup-info-message"></font>'
		+ '		<font id="signup-error-message" color="#ff0000"></font>'
		+ '</div>'

	return {
		'header' : fhq.t('Sign-up'),
		'content': content,
		'buttons': '<div class="fhqbtn" onclick="fhqgui.signup();">' + fhq.t('Sign-up') + '</div>'
	};
}

fhq.ui.templates.reset_password = function(){
	var content = ''
		+ '<div id="reset-password-form">'
		+ '		<input placeholder="your@email.com" id="reset-password-email" value="" type="text" onkeydown="if (event.keyCode == 13) fhqgui.resetPassword(); else fhqgui.cleanupResetPasswordMessages();">'
		+ '		<br><br>'
		+ '		<img src="" id="reset-password-captcha-image"/>'
		+ '		<div class="fhqbtn" onclick="fhqgui.refreshResetPasswordCaptcha();"><img src="images/refresh.svg"/></div>'
		+ '		<br><br>'
		+ '		<input placeholder="captcha" id="reset-password-captcha" value="" type="text" onkeydown="if (event.keyCode == 13) fhqgui.resetPassword(); else fhqgui.cleanupResetPasswordMessages();">'
		+ '		<br><br>'
		+ '		<font id="reset-password-info-message"></font>'
		+ '		<font id="reset-password-error-message" color="#ff0000"></font>'
		+ '</div>';

	return {
		'header' : fhq.t('Reset password'),
		'content': content,
		'buttons': '<div class="fhqbtn" onclick="fhqgui.resetPassword();">' + fhq.t('Reset') + '</div>'
	};
}

fhq.ui.templates.dialog_btn_cancel = function(){
	return '<div class="fhqbtn" onclick="fhq.ui.closeModalDialog();">' + fhq.t('Cancel') + '</div>';
}

fhq.ui.templates.feedback_form = function(title_text){
	var content = ''
		+ '<div id="feedback-form">'
		+ '	<input type="hidden" id="feedback-type" value=""/>'
		+ '	<textarea id="feedback-text" type="text"></textarea>'
		+ '	<br><br>'
		+ '	<font id="signin-error-message" color="#ff0000"></font>'
		+ '</div>'
	return {
		'header' : title_text,
		'content': content,
		'buttons': '<div class="fhqbtn" onclick="fhq.ui.feedbackDialogSend();">' + fhq.t('Send') + '</div>'
	};
}


window.fhq.ui.createCopyright = function() {
	$("body").append(''
		+ '<div id="copyright">'
		+ '	<center>'
		+ '		<font face="Arial" size=2>Copyright © 2011-2017 sea-kg. | '
		+ '		<a href="http://freehackquest.com/">About</a> | '
		+ '		WS State: <font id="websocket_state">?</font>'
		+ '	</center>'
		+ '</div>'
	);
}

fhq.ui.render = function(obj){
	if(!(obj instanceof Array)){
		console.error("[RENDER] expected array ", obj);
		return "Failed render";
	}
	var res = '';
	for(var i = 0; i < obj.length; i++){
		var el = obj[i];
		res += '<div';
		res += (el.c ? ' class="' + el.c + '" ':'');
		res += (el.id ? ' id="' + el.id + '" ':'');
		res += '>';
		if(el.r){
			if(typeof(el.r) == "number" || typeof(el.r) == "boolean" || typeof(el.r) == "string"){
				res += el.r;
			}else{
				res += fhq.ui.render(el.r);
			}
		}
		res += '</div>'
	}
	return res;
}

fhq.ui.paginatorClick = function(onpage, page){
	fhq.pageParams['onpage'] = onpage;
	fhq.pageParams['page'] = page;
	fhq.changeLocationState(fhq.pageParams);
	fhq.ui.processParams();
}

fhq.ui.paginator = function(min,max,onpage,page) {
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
			pagesHtml.push('<div class="fhq0064 fhq0065">' + (pagesInt[i]+1) + '</div>');
		} else {
			pagesHtml.push('<div class="fhq0064" onclick="fhq.ui.paginatorClick(' + onpage + ',' + pagesInt[i] + ');">' + (pagesInt[i]+1) + '</div>');
		}
	}
	return pagesHtml.join(' ');
}

$(document).ready(function() {
	fhq.ui.createCopyright();
});
