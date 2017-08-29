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
		if (evt.keyCode == 27){
			fhq.ui.closeModalDialog();
		}
	}
}

fhq.ui.showError = function(msg){
	fhq.ui.showModalDialog({
		'header' : fhq.t('Error'),
		'content' : msg,
		'buttons' : ''
	});
}

fhq.ui.showLoading = function(){
	$('.fhq0104').show();
}

fhq.ui.hideLoading = function(){
	setTimeout(function(){
		$('.fhq0104').hide();
	},1000);
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
		window.location.reload();
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

fhq.ui.loadTopPanel = function(){
	var toppanel = $('.fhqtopmenu_toppanel_container');
	toppanel.html('');
	// logo
	toppanel.append('<a class="fhq-menu-logo" href="./?">'
		+ '<img class="fhq_btn_menu_img" src="images/fhq2016_200x150.png"/> '
		+ '</a>')
	
	toppanel.append('<div id="btnmenu_quests" class="fhq0041">' + fhq.t('Quests') + '</div>')
	toppanel.append('<div id="btnmenu_scoreboard" class="fhq0041">' + fhq.t('Scoreboard') + '</div>');
	toppanel.append('<div id="btnmenu_news" class="fhq0041">' + fhq.t('News') + '</div>');
	toppanel.append('<div id="btnmenu_more" class="fhq0041">' + fhq.t('Other')
		+ '		<div class="fhq0109"></div>'
		+ '		<div class="fhq0110"></div>'
		+ '</div>');

	// more
	$('.fhq0110').append('<div class="fhq0045" onclick="fhq.ui.loadFeedback();">' + fhq.t('Feedback') + '</div>');
	$('.fhq0110').append('<div class="fhq0045" onclick="fhq.ui.loadGames();">' + fhq.t('Games') + '</div>');
	$('.fhq0110').append('<div class="fhq0045" onclick="fhq.ui.loadTools();">' + fhq.t('Tools') + '</div>');
	$('.fhq0110').append('<div class="fhq0045" onclick="fhq.ui.loadClassbook();">' + fhq.t('Classbook') + '</div>');
	$('.fhq0110').append('<div class="fhq0045" onclick="fhq.ui.loadRatingOfUsers();">' + fhq.t('Users') + '</div>');
	$('.fhq0110').append('<div class="fhq0045" onclick="fhq.ui.loadApiPage();">' + fhq.t('API') + '</div>');

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
		$('.account-panel').append('<div class="fhq-simple-btn" onclick="fhq.ui.loadUserProfile();">' + fhq.t('Your Profile') + '</div>');
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
		$('.fhq0044').append('<div class="fhq0045" onclick="fhq.ui.loadCreateNews();">' + fhq.t('Create News') + '</div>');
		$('.fhq0044').append('<div class="fhq0045" onclick="fhq.ui.loadCreateQuestForm();">' + fhq.t('Create Quest') + '</div>');
	}else{
		// TODO prepare quest
	}
	

	$('#btnmenu_user').unbind().bind('click', function(e){
		$('.accout-panel').show();
	});
	
	$('#btnmenu_colorscheme').unbind().bind('click', function(){
		if ($('body').hasClass('dark')) {
			fhq.ui.setLightColorScheme();
		} else {
			fhq.ui.setDarkColorScheme();
		}
	})
	
	$('#btnmenu_quests').unbind().bind('click', function(){
		fhq.changeLocationState({'quests':''});
		fhq.ui.loadStatSubjectsQuests();
	})
	
	$('#btnmenu_scoreboard').unbind().bind('click', function(){
		fhq.ui.loadScoreboard();
	})
	
	
	$('#btnmenu_news').unbind().bind('click', function(){
		fhq.ui.loadPageNews();
	})
	
	$('#btnmenu_more').unbind().bind('click', function(){
		// window.fhq.changeLocationState({'more':''});
		// fhq.ui.loadPageMore();
		$('.fhq0109').show();
		$('.fhq0110').show();
		
	});
	
	$('.fhq0109').unbind().bind('click', function(e){
		e.stopPropagation();
		e.preventDefault();
		fhq.ui.closeMoreMenu();
		return true;
	})
	
	$('#btnmenu_about').unbind().bind('click', function(){
		fhq.ui.loadPageAbout();
	})
	
	$('#btnmenu_plus').unbind().bind('click', function(){
		$('.fhq0043').show();
		$('.fhq0044').show();
	});

	$('.fhq0043').unbind().bind('click', function(e){
		e.stopPropagation();
		e.preventDefault();
		fhq.ui.closeAddMenu();
		return true;
	})
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
		self.showChatMessage(response.message, response.user, response.dt);
	}

	this.showChatMessage = function(m,u,d){
		self.messageLastId++;
		var id = 'message' + self.messageLastId;
		self.showedMessages.push(id);
		m = $('<div/>').text(m).html();
		u = $('<div/>').text(u).html();
		d = $('<div/>').text(d).html();
		var newel = $( '<div id="' + id + '" class="message_chat">' + m  + '<div class="message-chat-user">' + u + ' [' + new Date(Date.parse(d)) + ']</div>');
		$( "body" ).append( newel );
		newel.bind('click', function(){
			$( "#" + id).remove();
			self.showedMessages = jQuery.grep(self.showedMessages, function(value) { return value != id; });
			self.updatePostionMessages();
		});
		setTimeout(function(){self.updatePostionMessages();}, 1000);
		if(fhq.ui.chatSoundOn){
			document.getElementById('income_msg_sound').play();
		}
	}

	this.openQuestInNewTab = function(questid) {
		var win = window.open('?questid=' + questid, '_blank');
		win.focus();
	}

	this.openUserInNewTab = function(userid) {
		var win = window.open('?userid=' + userid, '_blank');
		win.focus();
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

fhq.ui.chatSoundOn = true;

if(localStorage.getItem('colorscheme') == null){
	localStorage.setItem('colorscheme', 'light');
}

fhq.ui.applyColorScheme = function(){
	if(localStorage.getItem('colorscheme') == 'dark'){
		fhq.ui.setDarkColorScheme();
	}else{
		fhq.ui.setLightColorScheme();
	}
}

fhq.ui.setDarkColorScheme = function(){
	$('body').addClass('dark');
	localStorage.setItem('colorscheme', 'dark');
	$('#jointothedarkside').html(fhq.t('You are on the dark side. Turn back?'));
	$('#btnmenu_colorscheme img').attr({'src': 'images/menu/lightside_150x150.png'})
}

fhq.ui.setLightColorScheme = function(){
	$('body').removeClass('dark');
	localStorage.setItem('colorscheme', 'light');
	$('#jointothedarkside').html(fhq.t('Join the dark side...'));
	$('#btnmenu_colorscheme img').attr({'src': 'images/menu/darkside_150x150.png'})
	
}

fhq.ui.closeMoreMenu = function(){
	setTimeout(function(){
		$('.fhq0109').hide();
		$('.fhq0110').hide();
	},100);
}

fhq.ui.closeAddMenu = function(){
	setTimeout(function(){
		$('.fhq0044').hide();
		$('.fhq0043').hide();
	},100);
}

fhq.ui.pageHandlers = {};

fhq.ui.processParams = function() {
	fhq.ui.pageHandlers["quests"] = fhq.ui.loadStatSubjectsQuests;
	fhq.ui.pageHandlers["user"] = fhq.ui.loadUserProfile;
	fhq.ui.pageHandlers["classbook"] = fhq.ui.loadClassbook;
	fhq.ui.pageHandlers["about"] = fhq.ui.loadPageAbout;
	fhq.ui.pageHandlers["games"] = fhq.ui.loadGames;
	fhq.ui.pageHandlers["scoreboard"] = fhq.ui.loadScoreboard;
	fhq.ui.pageHandlers["news"] = fhq.ui.loadPageNews;
	fhq.ui.pageHandlers["quest"] = fhq.ui.loadQuest;
	fhq.ui.pageHandlers["subject"] = fhq.ui.loadQuestsBySubject;
	fhq.ui.pageHandlers["new_feedback"] = fhq.ui.loadNewFeedback;
	fhq.ui.pageHandlers["create_news"] = fhq.ui.loadCreateNews;
	fhq.ui.pageHandlers["tools"] = fhq.ui.loadTools;
	fhq.ui.pageHandlers["tool"] = fhq.ui.loadTool;
	fhq.ui.pageHandlers["serverinfo"] = fhq.ui.loadServerInfo;
	fhq.ui.pageHandlers["answerlist"] = fhq.ui.loadAnswerList;
	fhq.ui.pageHandlers["feedback"] = fhq.ui.loadFeedback;
	fhq.ui.pageHandlers["api"] = fhq.ui.loadApiPage;
	fhq.ui.pageHandlers["new_quest"] = fhq.ui.loadCreateQuestForm;
	fhq.ui.pageHandlers["edit_quest"] = fhq.ui.loadEditQuestForm;


	function renderPage(){
		fhq.ui.loadTopPanel();
		fhq.ui.initChatForm();
		var processed = false;
		for(var p in fhq.ui.pageHandlers){
			if(fhq.containsPageParam(p)){
				processed = true;
				console.log("Processed: " + p);
				fhq.ui.pageHandlers[p](fhq.pageParams[p]);
				break;
			}
		}
		
		if(!processed){
			fhq.ui.loadStatSubjectsQuests();
		}
	}

	fhq.ws.user().done(renderPage).fail(renderPage);
}


fhq.ui.onwsclose = function(){
	$('.message_chat').remove();
	fhq.ui.showLoading();
}

fhq.ui.loadCreateNews = function(){
	fhq.changeLocationState({'create_news':''});
	fhq.ui.closeAddMenu();
	
	$('#content_page').html('<div class="fhq0046"></div>')
	$('#content_page').append('<div class="fhq0049"></div>')
	var el = $('.fhq0046');
	el.append('<h1>' + fhq.t("News") + '</h1>');

	el.append('<div class="fhq0048">' + fhq.t("Type") + ':</div>');
	el.append(''
		+ '<select class="fhq0047" id="create_news_type">'
		+ '	<option value="info">' + fhq.t("Information") + '</option>'
		+ '	<option value="users">' + fhq.t("Users") + '</option>'
		+ '	<option value="games">' + fhq.t("Games") + '</option>'
		+ '	<option value="quests">' + fhq.t("Quests") + '</option>'
		+ '	<option value="warning">' + fhq.t("Warning") + '</option>'
		+ '</select>');
	
	el.append('<div class="fhq0048">' + fhq.t("Message") + ':</div>');
	el.append('<textarea id="create_news_text"></textarea><br><br>');
	el.append('<div class="fhqbtn" onclick="fhq.ui.insertNews()">' + fhq.t("Create") + '</div>');
	
}

fhq.ui.insertNews = function(){
	var data = {};

	data.type = $('#create_news_type').val();
	data.message = $('#create_news_text').val();
	$('.fhq0046').hide();
	$('.fhq0049').show();

	fhq.ws.createpublicevent(data).done(function(){
		fhq.ui.loadPageNews();
	}).fail(function(r){
		$('.fhq0046').show();
		$('.fhq0049').hide();
	
		console.error(r);
		var msg = r.error;
		fhq.ui.showModalDialog({
			'header' : fhq.t('Error'),
			'content' : msg,
			'buttons' : ''
		});
	})
};

fhq.ui.loadServerInfo = function(){
	fhq.changeLocationState({'serverinfo':''});
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
	$("#content_page").html('<div class="fhq0067"></div>');
	var el = $('.fhq0067');

	el.append('<h1>' + fhq.t('About') +  '</h1>');
	el.append('<div class="fhq0069"><b>FreeHackQuest</b> - ' + fhq.t('This is an open source platform for competitions in computer security.') +  '</div>');
	el.append('<div class="fhq0070">' + fhq.t('Statistics') +  '</div>');
	
	el.append('<div class="fhq0072">'
		+ '<div class="fhq0073">'
		+ '		<div class="fhq0074">' + fhq.t('Quests') + '</div>'
		+ '		<div class="fhq0074">' + fhq.t('All attempts') + '</div>'
		+ '		<div class="fhq0074">' + fhq.t('Already solved') + '</div>'
		+ '		<div class="fhq0074">' + fhq.t('Users online') + '</div>'
		+ '</div>'
		+ '<div class="fhq0073">'
		+ '		<div class="fhq0074" id="statistics-count-quests">...</div>'
		+ '		<div class="fhq0074" id="statistics-all-attempts">...</div>'
		+ '		<div class="fhq0074" id="statistics-already-solved">...</div>'
		+ '		<div class="fhq0074" id="statistics-users-online">...</div>'
		+ '</div>'
		+ '</div><br><br><br>'
		+ '<div>' + fhq.t('Playing with us') + '</div> <div id="statistics-playing-with-us">...</div>'
	);

	el.append('<div class="fhq0070">' + fhq.t('Top 10') +  '</div>');
	el.append('<div id="winners"></div>');

	
	el.append('<div class="fhq0070">' + fhq.t('Contacts') +  '</div>');
	el.append('<div id="contacts">'
		+ '<br><br>'
		+ '<a href="//plus.google.com/u/0/108776719447039644581?prsrc=3" rel="publisher" target="_top" style="text-decoration:none;">'
		+ '<img src="//ssl.gstatic.com/images/icons/gplus-32.png" alt="Google+" style="border:0;width:32px;height:32px;"/>'
		+ '</a>'
		+ '<br><br>'
		+ '<a href="https://twitter.com/freehackquest" class="twitter-follow-button" data-show-count="false">Follow @freehackquest</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>'
		+ '<br><br>'
		+ '<a href="https://telegram.me/freehackquest" target="_blank"><img height=30px src="https://telegram.org/img/tgme/Logo_1x.png"/></a>'
		+ '<br><br>'
		+ 'Email: freehackquest@gmail.com'
		+ '<br><br>'
		+ '<a href="https://ctftime.org/team/16804" target="_blank"><img height=30px src="https://ctftime.org/static/images/CTFTIME-flat-logo-true.png"/></a>'
		+ '</div>');
	
	el.append('<div class="fhq0070">' + fhq.t('Distribution') +  '</div>');
	el.append('<div id="distribtion">'
		+ '<h3>' + fhq.t('License') + '</h3>'
		+ 'The MIT License (MIT)<br>'
		+ '<br>'
		+ 'Copyright (c) 2012-2017 sea-kg<br>'
		+ '<br>'
		+ 'Permission is hereby granted, free of charge, to any person obtaining a copy of<br>'
		+ 'this software and associated documentation files (the "Software"), to deal in<br>'
		+ 'the Software without restriction, including without limitation the rights to<br>'
		+ 'use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of<br>'
		+ 'the Software, and to permit persons to whom the Software is furnished to do so,<br>'
		+ 'subject to the following conditions:<br>'
		+ '<br>'
		+ 'The above copyright notice and this permission notice shall be included in all<br>'
		+ 'copies or substantial portions of the Software.<br>'
		+ '<br>'
		+ 'THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR<br>'
		+ 'IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS<br>'
		+ 'FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR<br>'
		+ 'COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER<br>'
		+ 'IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN<br>'
		+ 'CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.<br>'
		
		+ '<h3>' + fhq.t('Virtual Machine') + '</h3>'
		+ 'You can download <a href=\"http://dist.freehackquest.com/" target="_blank">virtual machine (ova)</a> and up in local network.<br>'
		+ '<i>' + fhq.t('If you found old version please contact me by mrseakg@gmail.com for get newest version') + '</i><br>'
		+ '<h3>' + fhq.t('Deb package') + '</h3>'
		+ 'Please select your architecture <a href="http://dist.freehackquest.com/backend/" target="_blank">Backend</a>'
		+ '<h3>' + fhq.t('Source code') + '</h3>'
		+ '<a href="http://github.com/freehackquest/fhq" target="_blank">http://github.com/freehackquest/fhq</a><br>'
		+ '<i>FrontEnd and Some part of server</i>'
		+ '<br><br>'
		+ '<a href="http://github.com/freehackquest/backend/" target="_blank">http://github.com/freehackquest/backend</a><br>'
		+ '<i>Backend</i>'
		+ '<br><br>'
		+ '</div>');
	
	el.append('<div class="fhq0070">' + fhq.t('Developers and designers') +  '</div>');
	el.append('<div id="devs_disgns">'
		+ 'Evgenii Sopov'
		+ '</div>')

	el.append('<div class="fhq0070">' + fhq.t('Thanks for') +  '</div>');
	var thanks_for = [
		'<a href="http://www.chartjs.org/docs/" target="_blank">Charts.js</a>',
		'Sergey Belov (found xss!)',
		'Igor Polyakov',
		'Maxim Samoilov (Nitive)',
		'Dmitrii Mukovkin',
		'Team Keva',
		'Alexey Gulyaev',
		'Alexander Menschikov',
		'Ilya Bokov',
		'Extrim Code',
		'Taisiya Lebedeva'
	];
	el.append('<div id="thanks_for">'
		+ thanks_for.join(', ')
		+ '</div>');


	el.append('<div class="fhq0070">' + fhq.t('Donate') +  '</div>');
	el.append('<div id="donate-form"></div>');
	el.append('<div class="fhq0071"></div>');

	$("#content_page").append('<div class="fhq0071"></div>');

	fhq.ui.loadCities();
	
	$.get('donate.html', function(result){
		$('#donate-form').html(result);
	});
	
	fhq.ui.applyColorScheme();
}


fhq.ui.loadCities = function() {
		fhq.ws.getPublicInfo().done(function(response){
			$('#statistics-users-online').text(response.connectedusers);
		});
		// TODO redesign to ws
		fhq.publicInfo(function(response){
			if (response.result == "fail") {
				$('#cities').html('Fail');
			} else {
				$('#statistics-count-quests').text(response.data.quests.count);
				$('#statistics-all-attempts').text(response.data.quests.attempts);
				$('#statistics-already-solved').text(response.data.quests.solved);

				var cities = [];
				for (var k in response.data.cities){
					cities.push(response.data.cities[k].city + ' (' + response.data.cities[k].cnt + ')');
				}

				$('#statistics-playing-with-us').removeClass('preloading');
				$('#statistics-playing-with-us').text(cities.join(", "));
				// TODO integrate frame in page
				$('#statistics-playing-with-us').append('<br><br><a href="map.php" target="_blank">On Map</a>');

				var content = '<div class="fhq0072">'
				+ '<div class="fhq0073">'
				+ '	<div class="fhq0074">Place</div> '
				+ '	<div class="fhq0074">Rating</div> '
				+ '	<div class="fhq0074">User</div>'
				+ '</div>';
				for (var k in response.data.winners) {
					var winner = response.data.winners[k];
					content += ''
					+ '<div class="fhq0073">'
					+ '	<div class="fhq0074">' + winner.place + '</div> '
					+ '	<div class="fhq0074">(+' + winner.rating + '):</div> '
					+ '	<div class="fhq0074">' + winner.user + '</div>'
					+ '</div>';
				}
				content += '</div>';
				$('#winners').html(content);
			}
		});
	};
	

fhq.ui.loadPageNews = function(){
	fhq.ui.showLoading();
	var onpage = 5;
	if(fhq.containsPageParam("onpage")){
		onpage = parseInt(fhq.pageParams['onpage'], 10);
	}

	var page = 0;
	if(fhq.containsPageParam("page")){
		page = parseInt(fhq.pageParams['page'], 10);
	}
	
	window.fhq.changeLocationState({'news': '', 'onpage': onpage, 'page': page});
	$("#content_page").html('<div class="fhq0057"></div>');
	$('.fhq0057').append('<h1>' + fhq.t('News') + '</h1>');
	$('.fhq0057').append('<div class="fhq0063"></div>');

	fhq.ws.publiceventslist({'onpage': onpage, 'page': page}).done(function(r){
		$('.fhq0063').append(fhq.ui.paginator(0, r.count, r.onpage, r.page));
		for(var i in r.data){
			var ev = r.data[i];
			$('.fhq0057').append(fhq.ui.templates.newsRow(ev));
		}
		fhq.ui.hideLoading();
	}).fail(function(r){
		console.error(r);
		$('.fhq0057').append(r.error);
	})
}

fhq.ui.deleteNews = function(id){
	fhq.ui.closeModalDialog();
	fhq.ws.deletepublicevent({'eventid': id}).done(function(r){
		fhq.ui.processParams();
	}).fail(function(r){
		console.error(r);
	});
}

fhq.ui.deleteNewsConfirm = function(id){
	fhq.ui.confirmDialog(fhq.t('Are you sure delete news') + ' #' + id + ' ?', 'fhq.ui.deleteNews(' + id + ');');
}

fhq.ui.editNews = function(id){
	alert("TODO " + id);
}

fhq.ui.loadScoreboard = function(){

	fhq.ui.showLoading();

	$("#content_page").html('');

	var onpage = 5;
	if(fhq.containsPageParam("onpage")){
		onpage = parseInt(fhq.pageParams['onpage'], 10);
	}

	var page = 0;
	if(fhq.containsPageParam("page")){
		page = parseInt(fhq.pageParams['page'], 10);
	}
	
	window.fhq.changeLocationState({'scoreboard':'', 'onpage': onpage, 'page': page});

	var params = {};
	params.onpage = onpage;
	params.page = page;

	fhq.ws.scoreboard(params).done(function(r){
			$("#content_page").html('<div class="fhq0087"></div>');
			
			for (var k in r.data) {
				var arr = [];
				var row = r.data[k];
				var first_user_logo = ''
				for (var k2 in row.users) {
					var u = row.users[k2];
					first_user_logo = u.logo;
					arr.push(fhqgui.userIcon(u.userid, u.logo, u.nick));
				}
				
				$('.fhq0087').append(''
					+ '<div class="fhq0088">'
					+ '  <div class="fhq0090" id="place' + k + '"></div>'
					+ '  <div class="fhq0090"><h1>' + row.place + '</h2> [' + row.rating + ' P]</div>'
					+ '  <div class="fhq0091">' + arr.join(' ') + '</div>'
					
					+ '</div>');
				
				if(row.users.length == 1)	{
					$('#place' + k).css({'background-image': 'url(' + u.logo + ')'});
				}else{
					$('#place' + k).css({'background-image': 'url(files/users/0.png)'});
				}
				$('.fhq0087').append('<div class="fhq0092"></div>');
			}
			fhq.ui.hideLoading();
		}
	);
}

fhq.ui.loadApiPage = function() {
	fhq.ui.closeMoreMenu();
	window.fhq.changeLocationState({'api':''});
	$('#content_page').html('<h1>API</h1><div class="fhq0086"></div><div class="fhq0078"></div>');
	var el = $('.fhq0078');
	el.html("Loading...");
	fhq.ws.api().done(function(r){
		el.html("");
		$('.fhq0086').html('<div class="fhq0097"><h3>Connect</h3>'
			+ 'Connection string: ws://freehackquest.com:' + r.data.port + '/ <br> '
			+ 'Or if enabled ssl: wss://freehackquest.com:' + r.data.ssl_port + '/ - with ssl</p>'
			+ '<p>For example: <pre>var socket = new WebSocket("wss://freehackquest.com:' + r.data.ssl_port + '/");</pre></p>'
			+ '<h3>Start communication with server</h3>'
			+ '<p>Fisrt command must be hello and next login if you have api token'
			+ '<p>For example: <pre>socket.send(JSON.stringify({cmd: "hello"}))</pre>'
			+ '<h3>Implemnetation</h3>'
			+ '<p>You can find this: <a href="https://freehackquest.com/js/fhq.ws.js" target="_blank">https://freehackquest.com/js/fhq.ws.js</a></p>'
			+ '</div>'
		);

		for(var i in r.data.handlers){
			var h = r.data.handlers[i];
			var c = ''
				+ '<div class="fhq0075">'
				+ '	<div class="fhq0076">'
				+ '		<div class="fhq0079">' + h.cmd + '</div>'
				+ '		<div class="fhq0080">' + h.description + '</div>'
				+ '	</div>'
				
			c +=  '	<div class="fhq0098">'
				+ '		<div class="fhq0081">' + fhq.t('Access') + '</div>'
				+ '		<div class="fhq0085">Unauthorized: ' + (h.access_unauthorized ? 'allow': 'deny') + '</div>'
				+ '		<div class="fhq0085">User: ' + (h.access_user ? 'allow': 'deny') + '</div>'
				+ '		<div class="fhq0085">Tester: ' + (h.access_tester ? 'allow': 'deny') + '</div>'
				+ '		<div class="fhq0085">Admin: ' + (h.access_admin ? 'allow': 'deny') + '</div>'
				+ '	</div>';
				
			c +=  '	<div class="fhq0077">';
			
			
			if(h.inputs.length != 0){
				c += '		<div class="fhq0081">' + fhq.t('Input\'s parameters') + '</div>';
						
				for(var i1 in h.inputs){
					var inp = h.inputs[i1];
					c += '<div class="fhq0085"><b>' + inp.type + '</b> "' + inp.name + '" (' + inp.restrict + ') - <i>' + inp.description + '</i></div>'
				}
			}

			c += '	</div>'
				+ '	<div class="fhq0082">'
				+ '		<div class="fhq0083">Errors</div>'
			
			for(var i1 in h.errors){
				c += '<div class="fhq0084">' + h.errors[i1] + '</div>'
			}
				
			c += '	</div>'
				+ '</div>'
			
			el.append(c);
		}
		el.append('<div class="fhq0071"></div>');
	}).fail(function(r){
		console.error(r);
	})
}

fhq.ui.loadUserProfile = function(userid) {
	userid = userid | (fhq.userinfo ? fhq.userinfo.id : userid); 
	fhq.ui.showLoading();
	window.fhq.changeLocationState({'user':userid});

	$('#content_page').html('<div class="fhq0009"></div>')
	var el = $('.fhq0009');
	el.html('Loading...');
	
	fhq.ws.user({userid: userid}).done(function(user){
		el.html('');
		el.append(''
			+ '<div class="fhq0010">'
			+ '	<div class="fhq0012">'
			+ '		<div class="fhq0011"></div>'
			+ '		<div class="fhq0013">'
			+ '			<div class="fhq0014">' + user.data.nick + ' (Rating: ' + user.data.rating + ')</div>'
			+ ' 		User ' + user.data.status + '.'
			+ ' 		User has ' + user.data.role + ' privileges.'
			+ ' 		From ' + user.profile.city
			+ '		</div>'
			+ '	</div>'
			+ '</div>');

		$('.fhq0011').css({ // game logo
			'background-image': 'url(' + user.data.logo + ')'
		});

		if(fhq.isAdmin()){
			/*var c = '<div class="fhq0051">';
			c += '<div class="fhqbtn" id="quest_edit">' + fhq.t('Edit') + '</div>';
			c += '<div class="fhqbtn" id="quest_delete">' + fhq.t('Delete') + '</div>';
			c += '<div class="fhqbtn" id="quest_export">' + fhq.t('Export') + '</div>';
			c += '<div class="fhqbtn" id="quest_report">' + fhq.t('Report an error') + '</div>';
			c += '</div>'
			el.append(c);*/
		}


		var converter = new showdown.Converter();
		el.append(
			'<div class="fhq0101">'
			+ '<div class="fhq0102">' + fhq.t('About user') + '</div>'
			+ converter.makeHtml(user.data.about == '' ? fhq.t('Missing information') : user.data.about)
			+ '</div>'
		)

		el.append(
			'<div class="fhq0101">'
			+ '<div class="fhq0102">' + fhq.t('Skills') + '</div>'
			+ '<div class="fhq0116">Loading...</div>'
			+ '</div>'
		);
		
		
		fhq.ws.user_skills({userid: user.data.id}).done(function(r){
			
			$('.fhq0116').html('');
			console.log(r);
			var anim = {};
			for(var subject in r.skills_max){
				var user_s = r.skills_user[subject] ? r.skills_user[subject] : 0;
				var max_s = r.skills_max[subject];
				var procent = Math.floor((user_s / max_s)*100);
				anim[subject] = procent;
				$('.fhq0116').append('<div class="fhq0117">'
					+ '	<div class="fhq0118">' + subject + ' </div>'
					+ '	<div class="fhq0119 ' + subject + '">'
					+ '		<div class="fhq0121"></div>'
					+ '	</div>'
					+ '	<div class="fhq0122">' + procent + '%</div>'
					+ '</div>'
					+ '<div class="fhq0120"></div>');
			}
			setTimeout(function(){
				for(var subject in anim){
					$('.fhq0119.' + subject + ' .fhq0121').css({'width': anim[subject] + '%' });
				}
			},1000);
		
		}).fail(function(r){
			console.error(r);
		});

		fhq.ui.hideLoading();
	}).fail(function(r){
		el.html(r.error);
		fhq.ui.hideLoading();
		return;
	});
}

fhq.ui.loadNewFeedback = function() {
	window.fhq.changeLocationState({'new_feedback':''});
	fhq.ui.closeAddMenu();
	
	$('#content_page').html('<div class="fhq0046"></div>')
	$('#content_page').append('<div class="fhq0049"></div></div>')
	var el = $('.fhq0046');
	el.append('<h1>' + fhq.t("Feedback") + '</h1>');
	
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

fhq.ui.confirmDialog = function(msg, onclick_yes){
	fhq.ui.showModalDialog({
		'header' : fhq.t('Confirm'),
		'content' : msg,
		'buttons' : '<div class="fhqbtn" onclick="' + onclick_yes + '">' + fhq.t('Yes') + '</div>'
	});
}

fhq.ui.loadGames = function() {
	fhq.ui.showLoading();
	fhq.ui.closeMoreMenu();
	window.fhq.changeLocationState({'games':''});

	$('#content_page').html('<div class="fhq0021"></div>');
	fhq.ws.games().done(function(r){
		console.log(r);
		var el = $('.fhq0021');

		for (var k in r.data) {
			if (r.data.hasOwnProperty(k)) {
				el.append(fhq.ui.gameView(r.data[k]));
			}
		}
		fhq.ui.hideLoading();
	}).fail(function(r){
		console.error(r);
		$('#content_page').html('fail');
		fhq.ui.hideLoading();
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
	
	if (fhq.isAdmin())
		content += '<div class="fhqbtn" onclick="formDeleteGame(' + game.id + ');">' + fhq.t('Delete') + '</div>';

	if (fhq.isAdmin())
		content += '<div class="fhqbtn" onclick="formEditGame(' + game.id + ');">' + fhq.t('Edit') + '</div>';
		
	if (fhq.isAdmin())
		content += '<div class="fhqbtn" onclick="fhqgui.exportGame(' + game.id + ');">' + fhq.t('Export') + '</div>';

	content += '			</div>';
	content += '		</div>';
	content += '	</div>';
	content += '</div>'
	content += '<div class="fhq0028"></div>';
	return content;
}

fhq.ui.loadFeedback = function() {
	fhq.ui.showLoading();
	fhq.ui.closeMoreMenu();
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
				}
				content += '			</div>';

				content += '		</div>'; // fhq_event_info_cell_content
				content += '	</div>'; // fhq_event_info_row
				content += '</div><br>'; // fhq_event_info
			}
			content += '';
		}
		el.html(content);
		fhq.ui.hideLoading();
	}).fail(function(r){
		console.error(r);
		el.html(r.responseJSON.error.message);
		fhq.ui.hideLoading();
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
	fhq.ui.closeMoreMenu();
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
function createQuestRow(name, value) {
	return '<div class="quest_info_row">\n'
	+ '\t<div class="quest_info_param">' + name + '</div>\n'
	+ '\t<div class="quest_info_value">' + value + '</div>\n'
	+ '</div>\n';

}


fhq.ui.loadCreateQuestForm = function(){
	setTimeout(function(){
		$('.fhq0043').hide();
		$('.fhq0044').hide();
	},500);
	window.fhq.changeLocationState({'new_quest':''});
	$('#content_page').html('<div class="fhq0021"></div>');
	var el = $('.fhq0021');
	el.append('<h1>' + fhq.t('Create Quest') + '</h1>');
	var form = ''
		+ '<div class="fhq0093">'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('UUID') + '</div>'
		+ '		<div class="fhq0096"><input style="width: 300px" type="text" readonly id="newquest_quest_uuid" value="' + guid() + '"/></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Game') + '</div>'
		+ '		<div class="fhq0096"><select id="newquest_gameid"></select></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Name') + '</div>'
		+ '		<div class="fhq0096"><input type="text" id="newquest_name"></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Text') + ' (Use markdown format)</div>'
		+ '		<div class="fhq0096"><textarea id="newquest_text"></textarea></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Score') + ' (+)</div>'
		+ '		<div class="fhq0096"><input type="text" id="newquest_score" value="100"/></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Subject') + '</div>'
		+ '		<div class="fhq0096">' + fhqgui.combobox('newquest_subject', 'trivia', fhq.getQuestTypes()) + '</div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Answer') + '</div>'
		+ '		<div class="fhq0096"><input type="text" id="newquest_answer" value=""/> (' + fhq.t('Answer format') + ' <input type="text" id="newquest_answerformat" value=""/>)</div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Copyright') + '</div>'
		+ '		<div class="fhq0096"><input type="text" id="newquest_copyright" value=""/></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('State') + '</div>'
		+ '		<div class="fhq0096">' + fhqgui.combobox('newquest_state', 'open', fhq.getQuestStates()) + '</div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Description State') + '</div>'
		+ '		<div class="fhq0096"><textarea id="newquest_description_state"></textarea></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095"></div>'
		+ '		<div class="fhq0096"><div class="fhqbtn" onclick="fhq.ui.createQuest();">Create</div></div>'
		+ ' </div>'
		+ '</div>'
		+ '<div class="fhq0115"></div>'
	el.append(form);
	
	fhq.ws.games().done(function(r){
		for(var i in r.data){
			$('#newquest_gameid').append('<option value="' + r.data[i]["id"] + '">' + r.data[i]["title"] + '</option>');
		}
	})
}

fhq.ui.createQuest = function() {
	var params = {};
	params["uuid"] = $("#newquest_quest_uuid").val();
	params["gameid"] = parseInt($("#newquest_gameid").val(),10);
	params["name"] = $("#newquest_name").val();
	params["text"] = $("#newquest_text").val();
	params["score"] = parseInt($("#newquest_score").val(),10);
	params["subject"] = $("#newquest_subject").val();
	params["copyright"] = $("#newquest_copyright").val();
	params["answer"] = $("#newquest_answer").val();
	params["answer_format"] = $("#newquest_answerformat").val();
	params["state"] = $("#newquest_state").val();
	params["description_state"] = $("#newquest_description_state").val();

	fhq.ws.createquest(params).done(function(r){
		fhq.ui.loadQuest(r.questid);
	}).fail(function(r){
		fhq.ui.showError(r.error);
	});
};

fhq.ui.deleteQuest = function(id){
	if (!confirm("Are you sure that wand remove this quest?"))
		return;

	var params = {};
	params.questid = parseInt(id,10);
	fhq.ws.deletequest(params).done(function(r){
		fhq.ui.loadQuestsBySubject(r.subject);
	}).fail(function(r){
		fhq.ui.showError(r.error);
	});
}

fhq.ui.loadEditQuestForm = function(questid){
	window.fhq.changeLocationState({'edit_quest':questid});
	$('#content_page').html('<div class="fhq0021"></div>');
	var el = $('.fhq0021');
	el.append('<h1>' + fhq.t('Edit Quest') + ' #' + questid + '</h1>');
	var form = ''
		+ '<div class="fhq0093">'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Game') + '</div>'
		+ '		<div class="fhq0096"><select id="editquest_gameid"></select></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Name') + '</div>'
		+ '		<div class="fhq0096"><input type="text" id="editquest_name"></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Text') + '</div>'
		+ '		<div class="fhq0096"><textarea id="editquest_text"></textarea></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Score') + ' (+)</div>'
		+ '		<div class="fhq0096"><input type="text" id="editquest_score" value="100"/></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Subject') + '</div>'
		+ '		<div class="fhq0096">' + fhqgui.combobox('editquest_subject', 'trivia', fhq.getQuestTypes()) + '</div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Answer') + '</div>'
		+ '		<div class="fhq0096"><input type="text" id="editquest_answer" value=""/> (' + fhq.t('Answer format') + ' <input type="text" id="editquest_answerformat" value=""/>)</div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Author') + '</div>'
		+ '		<div class="fhq0096"><input type="text" id="editquest_author" value=""/></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Copyright') + '</div>'
		+ '		<div class="fhq0096"><input type="text" id="editquest_copyright" value=""/></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('State') + '</div>'
		+ '		<div class="fhq0096">' + fhqgui.combobox('editquest_state', 'open', fhq.getQuestStates()) + '</div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095">' + fhq.t('Description State') + '</div>'
		+ '		<div class="fhq0096"><textarea id="editquest_description_state"></textarea></div>'
		+ ' </div>'
		+ '	<div class="fhq0094">'
		+ '		<div class="fhq0095"></div>'
		+ '		<div class="fhq0096">'
		+ '			<div class="fhqbtn" onclick="fhq.ui.updateQuest(' + questid + ');">Update</div>'
		+ '			<div class="fhqbtn" onclick="fhq.ui.loadQuest(' + questid + ');">Cancel</div>'
		+ '		</div>'
		+ ' </div>'
		+ '</div>'
	el.append(form);
	
	fhq.ws.quest({'questid': parseInt(questid)}).done(function(r){
		// if admin
		$('#editquest_name').val(r.quest.name);
		$('#editquest_state').val(r.quest.state);
		$('#editquest_score').val(r.quest.score);
		$('#editquest_description_state').val(r.quest.description_state);
		$('#editquest_state').val(r.quest.state);
		$('#editquest_copyright').val(r.quest.copyright);
		$('#editquest_subject').val(r.quest.subject);
		$('#editquest_answer').val(r.quest.answer);
		$('#editquest_answerformat').val(r.quest.answer_format);
		$('#editquest_text').val(r.quest.text);
		$('#editquest_description_state').val(r.quest.description_state);
		$('#editquest_author').val(r.quest.author);

		fhq.ws.games().done(function(rg){
			for(var i in rg.data){
				$('#editquest_gameid').append('<option value="' + rg.data[i]["id"] + '">' + rg.data[i]["title"] + '</option>');
			}
			$('#editquest_gameid').val(r.quest.gameid);
		})
	}).fail(function(r){
		console.error(r);
	});
}

fhq.ui.updateQuest = function(questid) {
	var params = {};
	params['questid'] = parseInt(questid,10);
	params["gameid"] = parseInt($("#editquest_gameid").val(),10);
	params["name"] = $("#editquest_name").val();
	params["text"] = $("#editquest_text").val();
	params["score"] = parseInt($("#editquest_score").val(),10);
	params["subject"] = $("#editquest_subject").val();
	params["copyright"] = $("#editquest_copyright").val();
	params["answer"] = $("#editquest_answer").val();
	params["answer_format"] = $("#editquest_answerformat").val();
	params["author"] = $("#editquest_author").val();
	params["state"] = $("#editquest_state").val();
	params["description_state"] = $("#editquest_description_state").val();

	fhq.ws.updatequest(params).done(function(r){
		fhq.ui.loadQuest(questid);
	}).fail(function(r){
		fhq.ui.showError(r.error);
	});
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
	fhq.ui.showLoading();
	$('#content_page').html('<div class="fhq0006">Loading...</div>');
	fhq.api.quests.stats_subjects().done(function(r){
		console.log(r);
		$('.fhq0006').html('');
		var el = $('.fhq0006');
		for(var i in r.data){
			var o = r.data[i];
			el.append(''
				+ '<div class="fhq0111" subject="' + o.subject + '">'
				+ ' <div class="fhq0114">'
				+ ' 	<div class="fhq-quests-subject-cell logo" style="background-image: url(images/quests/' + o.subject + '_150x150.png)">'
				+ ' 	</div>'
				+ ' 	<div class="fhq-quests-subject-cell text">'
				+ fhq.ui.capitalizeFirstLetter(o.subject) + '<br>'
				+ '<div class="fhq0112">' + fhq.t(o.subject + '_description') + '</div>'
				+ ' 	</div>'
				+ ' 	<div class="fhq-quests-subject-cell count">'
				+ '(' + o.count + ' quests)'
				+ ' 	</div>'
				+ ' </div>'
				+ '</div>'
				+ '<div class="fhq0113"></div>'
			);
		}
		
		$('.fhq0111').unbind().bind('click', function(){
			fhq.ui.loadQuestsBySubject($(this).attr('subject'));
		})
		fhq.ui.hideLoading();
	}).fail(function(r){
		console.error(r);
		$('.fhq0006').html('Failed');
	});
}

fhq.ui.loadQuestsBySubject = function(subject){
	fhq.ui.showLoading();
	fhq.changeLocationState({'subject':subject});
	
	$('#content_page').html('<div class="fhq0005">Loading...</div>');
	var params = {};
	params.subject = subject;
	fhq.api.quests.list(params).done(function(r){
		$('.fhq0005').html('');
		for(var i in r.data){
			var q = r.data[i];
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
			fhq.ui.loadQuest($(this).attr('questid'));
		});
		fhq.ui.hideLoading();
	}).fail(function(r){
		console.error(r)
		$('.fhq0005').html('Failed');
	});
}


/* fhq_quests.js todo redesign */

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

window.fhq.ui.refreshHints = function(questid, hints, perm_edit){
	var result = "";
	var i = 1;
	for(var h in hints){
		var hint = hints[h];
		result += '<div><b>Hint ' + i + ':</b> <pre style="display: inline-block;">' + $('<div/>').text(hint.text).html() + '</pre>' + (fhq.isAdmin() ? ' <div class="fhqbtn deletehint" hintid="' + hint.id + '">' + fhq.t('Delete') + '</div>' : '') + '</div>';
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
	fhq.ui.showLoading();
	$('#content_page').html('<div class="fhq0009"></div>')
	var el = $('.fhq0009');
	el.html('Loading...');
	var questid = parseInt(id,10);
	fhq.ws.quest({'questid': questid}).done(function(response){
		console.log(response);
		var q = response.quest;
		var g = response.game;
		var fi = response.files;
		var hi = response.hints;

		fhq.changeLocationState({quest: q.id});
		el.html('');
		el.append(''
			+ '<div class="fhq0010">'
			+ '	<div class="fhq0012">'
			+ '		<div class="fhq0011"></div>'
			+ '		<div class="fhq0013">'
			+ ' 		<a href="?subject=' + q.subject + '">' + fhq.ui.capitalizeFirstLetter(q.subject) + '</a> / <a href="?quest=' + q.id + '">Quest ' + q.id + '</a>' 
			+ ' 		(' + (q.completed ? fhq.t('Quest completed') : fhq.t('Quest open')) + ')'
			+ '			<div class="fhq0014">' + q.name + ' (+' + q.score + ')</div>'
			+ '		</div>'
			+ '	</div>'
			+ '</div>');

		$('.fhq0011').css({ // game logo
			'background-image': 'url(' + g.logo + ')'
		});
		
		var c = '<div class="fhq0051">';
		if(fhq.isAdmin()){
			c += '<div class="fhqbtn" id="quest_edit">' + fhq.t('Edit') + '</div>';
			c += '<div class="fhqbtn" id="quest_delete">' + fhq.t('Delete') + '</div>';
			c += '<div class="fhqbtn" id="quest_export">' + fhq.t('Export') + '</div>';
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
			fhq.ui.deleteQuest(q.id);
		});
		
		$('#quest_edit').unbind().bind('click', function(){
			fhq.ui.loadEditQuestForm(q.id);
		})

		$('#quest_export').unbind().bind('click', function(){
			fhqgui.exportQuest(q.id);
		})

		el.append('<div class="fhq0051"><br>'
			+ '<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>'
			+ '<script src="//yastatic.net/share2/share.js"></script>'
			+ '<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,blogger,reddit,linkedin,lj,viber,whatsapp,skype,telegram"></div>'
			+ '</div>'
		);
		
		el.append(
			'<div class="fhq0101">'
			+ '<div class="fhq0102">' + fhq.t('Details') + '</div>'
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
			+ '			<div class="newquestinfo-details-cell">' + (q.completed ? fhq.t('status_completed') + ' (' + q.dt_passed + ')' : fhq.t('status_open')) + '</div>'
			+ '		</div>'
			+ '	</div>'
			+ '	<div class="newquestinfo-details-right"> '
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('State') + ':</div>'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('state_' + q.state) + '</div>'
			+ '		</div>'
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('Solved') + ':</div>'
			+ '			<div class="newquestinfo-details-cell">' + q.count_user_solved + ' ' + fhq.t('users_solved') + '</div>'
			+ '		</div>'
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('Author') + ':</div>'
			+ '			<div class="newquestinfo-details-cell">' + q.author + '</div>'
			+ '		</div>'
			+ '		<div class="newquestinfo-details-row">'
			+ '			<div class="newquestinfo-details-cell">' + fhq.t('Copyright') + ':</div>'
			+ '			<div class="newquestinfo-details-cell">' + q.copyright + '</div>'
			+ '		</div>'			
			+ '	</div>'
			+ '</div>'
		)

		var converter = new showdown.Converter();
		

		el.append(
			'<div class="fhq0101">'
			+ '<div class="newquestinfo_description_title">' + fhq.t('Description') + '</div>'
			+ converter.makeHtml(q.text)
			+ '</div>'
		)

		if(fi.length > 0){
			var files1 = '';						
			for (var k in fi) {
				files1 += '<a class="fhqbtn" href="' + fi[k].filepath + '" target="_blank">'+ fi[k].filename + '</a> ';
			}
			
			el.append(
				'<div class="fhq0101">'
				+ '<div class="newquestinfo_attachments_title">' + fhq.t('Attachments') + '</div>'
				+ files1
				+ '</div>'
			)
		}

		if(hi.length > 0 || fhq.isAdmin()){
			var hints = '<div class="fhq0051">'
				+ '<div class="fhq0053 hide" id="quest_show_hints">' + fhq.t('Hints') + '</div>'
				+ '<div id="newquestinfo_hints" style="display: none;">';
			hints += '</div></div>';
			el.append(hints);
			fhq.ui.refreshHints(q.id, hi, fhq.isAdmin());
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
		
		if(!q.completed){
			if(fhq.isAuth()){
				el.append(
					'<div class="fhq0101">'
					+ '<div class="newquestinfo_passquest_title">' + fhq.t('Answer') + '</div>'
					+ '<div class="fhq0099">'
					+ '		<input id="quest_answer" type="text" onkeydown="if (event.keyCode == 13) this.click();"/> '
					+ '		<div class="fhq0100">' + fhq.t('Answer format') + ': ' + q.answer_format + '</div>'
					+ '</div>'
					+ '<div class="fhq0099">'
					+ '		<div class="fhqbtn" id="newquestinfo_pass">' + fhq.t('Pass the quest') + '</div>'
					+ '		<div id="quest_pass_error"></div>'
					+ '</div>'
					+ '</div>'
				);
				
				$('#newquestinfo_pass').unbind().bind('click', function(){
					var answer = $('#quest_answer').val();
					// TODO change to ws
					fhq.api.quests.pass(q.id, answer).done(function(response){
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
					'<div class="fhq0101">'
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
		el.append('<div class="fhq0115"></div>');
		fhq.ui.hideLoading();
	}).fail(function(r){
		console.error(r);
		el.html(r.responseJSON.error.message);
	})
}

fhq.ui.loadWriteUps = function(questid){
	fhq.ui.showLoading();
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
		fhq.ui.hideLoading();
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
	fhq.ui.showLoading();
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
				label: "Correct"
			},
			{
				value: q.tries,
				color: "#9f9f9f",
				highlight: "#606060",
				label: "Incorrect answers"
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
		fhq.ui.hideLoading();		
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
	
	
	$('#sendchatmessage_trigger').unbind().bind('click', function(event){
		if($('#sendchatmessage_trigger').hasClass('hide')){
			$('#sendchatmessage_trigger').removeClass('hide');
			$('.message_chat').show();
			$('.sendchatmessage-form').css({'width': '300px'});
			$('#sendchatmessage_text').show();
			$('#sendchatmessage_submit').show();
		}else{
			$('#sendchatmessage_trigger').addClass('hide');
			$('.sendchatmessage-form').css({'width': '30px'});
			$('.message_chat').hide();
			$('#sendchatmessage_text').hide();
			$('#sendchatmessage_submit').hide();
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
	fhq.ui.closeMoreMenu();
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

fhq.ui.templates.newsRow = function(ev){
	var imgpath = '';
	if (ev.type == 'users')
		imgpath = 'images/menu/user.png';
	else if (ev.type == 'quests')
		imgpath = 'images/menu/quests_150x150.png';
	else if (ev.type == 'warning')
		imgpath = 'images/menu/warning.png';
	else if (ev.type == 'info')
		imgpath = 'images/menu/news.png';
	else if (ev.type == 'games')
		imgpath = 'images/menu/games_150x150.png';
	else
		imgpath = 'images/menu/default.png'; // default

	var r = [{
		'c': 'fhq0017',
		'r': [{
			c: 'fhq0018',
			r: [{
				c: 'fhq0019',
				s: 'background-image: url(' + imgpath + ')',
			},{
				c: 'fhq0020',
				r: [ ev.message,{
						c: 'fhq0065',
						r: '[' + ev.type + ', ' + ev.dt + ']'
				}, {
					c: 'fhq0068',
					a: fhq.isAdmin(),
					r: [{
						c: 'fhqbtn',
						click: 'fhq.ui.editNews(' + ev.id + ')',
						r: fhq.t('Edit')
					},{
						c: 'fhqbtn',
						click: 'fhq.ui.deleteNewsConfirm(' + ev.id + ')',
						r: fhq.t('Delete')
					}]
				}]
			}]
		}]
	}];
	return fhq.ui.render(r);
}

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
		if(typeof(el) == "undefined"){
			console.error("Element is undefined");
		}else if(typeof(el) == "string"){
			res += el;
		}else{
			var a = true;
			if(el.a !== undefined){
				a = el.a;
			}
			if (a){
				res += '<div';
				res += (el.c ? ' class="' + el.c + '" ':'');
				res += (el.id ? ' id="' + el.id + '" ':'');
				res += (el.s ? ' style="' + el.s + '" ':'');
				res += (el.click ? ' onclick="' + el.click + '" ':'');
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
		}
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
	pagesHtml.push('<div class="fhq0066">' + fhq.t('Found') + ': ' + (max-min) + '</div>');
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

