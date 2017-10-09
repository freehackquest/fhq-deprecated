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
	
	fhq.ws.login({email: email,password: password}).done(function(r){
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
		//window.location.reload();
	}).fail(function(r){
		$("#signin-error-message").html(r.error);
	})
}

fhq.ui.signout = function(){
	$('.message_chat').remove();
	fhq.token = "";
	fhq.userinfo = null;
	fhq.removeTokenFromCookie();
	localStorage.removeItem('userinfo');
	fhq.ui.processParams();
}

fhq.ui.updateMenu = function(){
	// localization
	$('#btnmenu_quests .nav-link').html(fhq.t('Quests'));
	$('#btnmenu_scoreboard .nav-link').html(fhq.t('Scoreboard'));
	$('#btnmenu_news .nav-link').html(fhq.t('News'));
	$('#btnmenu_about .nav-link').html(fhq.t('About'));
	$('#btnmenu_other .nav-link').html(fhq.t('Other'));

	$('#btnmenu_feedback').html(fhq.t('Feedback'));
	$('#btnmenu_map').html(fhq.t('Map'));
	$('#btnmenu_chat').html(fhq.t('Chat'));
	$('#btnmenu_games').html(fhq.t('Games'));
	$('#btnmenu_tools').html(fhq.t('Tools'));
	$('#btnmenu_classbook').html(fhq.t('Classbook'));
	$('#btnmenu_apidocs').html(fhq.t('FreeHackQuest API'));
	
	// users/unauth menu
	$('#btnmenu_newfeedback').html(fhq.t('New Feedback'));
	
	$('#btnmenu_creategame').html(fhq.t('Create Game'));
	$('#btnmenu_importgame').html(fhq.t('Import Game'));
	$('#btnmenu_createnews').html(fhq.t('Create News'));
	$('#btnmenu_createquest').html(fhq.t('Create Quest'));

	$('#btnmenu_users').html(fhq.t('Users'));
	$('#btnmenu_users2').html(fhq.t('Users') + "2");
	$('#btnmenu_answerlist').html(fhq.t('Answer List'));
	$('#btnmenu_serverinfo').html(fhq.t('Server Info'));
	$('#btnmenu_serversettings').html(fhq.t('Server Settings'));
	
	$('#btnmenu_signin').html(fhq.t('Sign-in'));
	$('#btnmenu_signin_with_google').html(fhq.t('Sign-in with Google'));
	$('#btnmenu_signup').html(fhq.t('Sign-up'));
	$('#btnmenu_restore_password').html(fhq.t('Forgot password?'));
	
	
	$('#btnmenu_user_profile').html(fhq.t('Your Profile'));
	$('#btnmenu_user_logout').html(fhq.t('Sign-out'));
	
	if (fhq.isAdmin()){
		$('.admin-menu').show();
	}else{
		$('.admin-menu').hide();
	}
	
	if(!fhq.isAuth()){
		$('.unauth-menu').show();
		$('.auth-menu').hide();
		$('#user_img').attr({'src' : 'images/menu/user.png'})
		$('#user_nick').html(fhq.t('Account'));
	}else{
		$('.unauth-menu').hide();
		$('.auth-menu').show();
		if(fhq.userinfo){
			$('#user_img').attr({'src' : fhq.userinfo.logo})
			$('#user_nick').html(fhq.userinfo.nick);
		}else{
			$('#user_img').attr({'src' : 'images/menu/user.png'})
			$('#user_nick').html(fhq.t('Account'));
		}
	}
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
		var win = window.open('?user=' + userid, '_blank');
		win.focus();
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

fhq.ui.pageHandlers = {};

fhq.ui.processParams = function() {
	fhq.ui.pageHandlers["quests"] = fhq.ui.loadStatSubjectsQuests;
	fhq.ui.pageHandlers["user"] = fhq.ui.loadUserProfile;
	fhq.ui.pageHandlers["classbook"] = fhq.ui.loadClassbook;
	fhq.ui.pageHandlers["about"] = fhq.ui.loadPageAbout;
	fhq.ui.pageHandlers["registration"] = fhq.ui.loadRegistrationPage;
	fhq.ui.pageHandlers["games"] = fhq.ui.loadGames;
	fhq.ui.pageHandlers["game_create"] = fhq.ui.loadFormCreateGame;
	fhq.ui.pageHandlers["scoreboard"] = fhq.ui.loadScoreboard;
	fhq.ui.pageHandlers["map"] = fhq.ui.loadMapPage;
	fhq.ui.pageHandlers["news"] = fhq.ui.loadPageNews;
	fhq.ui.pageHandlers["quest"] = fhq.ui.loadQuest;
	fhq.ui.pageHandlers["subject"] = fhq.ui.loadQuestsBySubject;
	fhq.ui.pageHandlers["feedback_add"] = fhq.ui.loadFeedbackAdd;
	fhq.ui.pageHandlers["create_news"] = fhq.ui.loadCreateNews;
	fhq.ui.pageHandlers["tools"] = fhq.ui.loadTools;
	fhq.ui.pageHandlers["tool"] = fhq.ui.loadTool;
	fhq.ui.pageHandlers["serverinfo"] = fhq.ui.loadServerInfo;
	fhq.ui.pageHandlers["answerlist"] = fhq.ui.loadAnswerList;
	fhq.ui.pageHandlers["feedback"] = fhq.ui.loadFeedback;
	fhq.ui.pageHandlers["api"] = fhq.ui.loadApiPage;
	fhq.ui.pageHandlers["new_quest"] = fhq.ui.loadCreateQuestForm;
	fhq.ui.pageHandlers["edit_quest"] = fhq.ui.loadEditQuestForm;
	fhq.ui.pageHandlers["server_settings"] = fhq.ui.loadServerSettings;
	fhq.ui.pageHandlers["chat"] = fhq.ui.loadChatPage;

	function renderPage(){
		fhq.ui.updateMenu();
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
			
			if(fhq.containsPageParam("users")){
				createPageUsers();
				updateUsers();
			}else{
				fhq.ui.loadStatSubjectsQuests();
			}
		}
	}

	fhq.ws.user().done(renderPage).fail(renderPage);
}

fhq.ui.createGame = function()  {
	fhq.ui.showLoading();

	var data = {};
	data["uuid"] = $("#newgame_uuid").val();
	data["logo"] = $("#newgame_logo").val();
	data["name"] = $("#newgame_name").val();
	data["state"] = $("#newgame_state").val();
	data["form"] = $("#newgame_form").val();
	data["type"] = $("#newgame_type").val();
	data["date_start"] = $("#newgame_date_start").val();
	data["date_stop"] = $("#newgame_date_stop").val();
	data["date_restart"] = $("#newgame_date_restart").val();
	data["description"] = $("#newgame_description").val();
	data["organizators"] = $("#newgame_organizators").val();

	fhq.ws.game_create(data).done(function(r){
		fhq.ui.hideLoading();
		fhq.ui.loadGames();
	}).fail(function(err){
		fhq.ui.hideLoading();
		console.error(err);
	})
		
	/*send_request_post(
		'api/games/insert.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				closeModalDialog();
				fhqgui.loadGames();
			} else {
				alert(obj.error.message);
			}
		}
	);*/
};

fhq.ui.loadFormCreateGame = function() {
	fhq.changeLocationState({'game_create':''});
	var el = $('#content_page');
	el.html('');
	fhq.ui.hideLoading();
	
	el.html(''
		+ '<div class="card">'
		+ '		<div class="card-header">New Game</div>'
		+ '		<div class="card-body">'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_uuid" class="col-sm-2 col-form-label">UUID</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<input type="text" class="form-control" value="' + guid() + '" id="newgame_uuid">'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_logo" class="col-sm-2 col-form-label">Logo</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<input type="text" class="form-control" value="" id="newgame_logo">'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_name" class="col-sm-2 col-form-label">Name</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<input type="text" class="form-control" value="" id="newgame_name">'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_state" class="col-sm-2 col-form-label">State</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<select class="form-control" value="" id="newgame_state">'
		+ '						<option value="original">Original</option>'
		+ '						<option value="copy">Copy</option>'
		+ '						<option value="unlicensed-copy">Unlicensed Copy</option>'
		+ '					</select>'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_form" class="col-sm-2 col-form-label">Form</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<select class="form-control" value="" id="newgame_form">'
		+ '						<option value="online">Online</option>'
		+ '						<option value="offline">Offline</option>'
		+ '					</select>'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_type" class="col-sm-2 col-form-label">Type</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<select class="form-control" value="" id="newgame_type">'
		+ '						<option value="jeopardy">Jeopardy</option>'
		+ '					</select>'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_date_start" class="col-sm-2 col-form-label">Date Start</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<input type="text" class="form-control" id="newgame_date_start" value="0000-00-00 00:00:00">'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_date_stop" class="col-sm-2 col-form-label">Date Stop</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<input type="text" class="form-control" id="newgame_date_stop" value="0000-00-00 00:00:00">'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_date_restart" class="col-sm-2 col-form-label">Date Restart</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<input type="text" class="form-control" id="newgame_date_restart" value="0000-00-00 00:00:00">'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_description" class="col-sm-2 col-form-label">Description</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<textarea type="text" class="form-control" style="height: 150px" value="" id="newgame_description"></textarea>'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_organizators" class="col-sm-2 col-form-label">Organizators</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<input type="text" class="form-control" value="" id="newgame_organizators">'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label class="col-sm-2 col-form-label"></label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<div class="btn btn-danger" onclick="fhq.ui.createGame();">Create</div>'
		+ '				</div>'
		+ '			</div>'
		+ '		</div>'
		+ '</div>'
	);
	
	$('#newgame_date_start').datetimepicker({
		format:'Y-m-d H:i:s',
		inline:false
	});
	
	$('#newgame_date_stop').datetimepicker({
		format:'Y-m-d H:i:s',
		inline:false
	});
	
	$('#newgame_date_restart').datetimepicker({
		format:'Y-m-d H:i:s',
		inline:false
	});
}


/* Registration */

fhq.ui.registry = function() {
	$('#registration_error').html('');
	var data = {};
	data.email = $('#registration_email').val();
	data.country = $('#registration_country').val();
	data.region = $('#registration_region').val();
	data.city = $('#registration_city').val();
	data.university = $('#registration_university').val();
	fhq.ui.showLoading();
	fhq.ws.registration(data).done(function(r){
		console.log(r);
		
		$('#signup-email').val('');
		$('#signup-captcha').val('');
		$('#signup-info-message').html('');
		$('#signup-error-message').html('');
		fhq.ui.hideLoading();
		$('#content_page').html('Please check your mailbox (also look in spam)');
	}).fail(function(r){
		console.error(r);
		$('#registration_error').html(fhq.t(r.error));
		fhq.ui.hideLoading();
	})
		
}

fhq.ui.loadRegistrationPage = function() {
	fhq.ui.hideLoading();
	fhq.changeLocationState({'registration':''});
	$('#content_page').html('');
	
	$('#content_page').append(''
		+ '	<div class="form-group row">'
		+ ' 	<div class="col-sm-4"></div>'
		+ ' 	<div class="col-sm-4">'
		+ '			<h1 class="text-center">Registration</h1>'
		+ '		</div>'
		+ ' 	<div class="col-sm-4"></div>'
		+ '	</div>'
		+ '	<div class="form-group row">'
		+ ' 	<div class="col-sm-4"></div>'
		+ ' 	<div class="col-sm-4">'
		+ '			<label for="registration_email" class="col-form-label">E-mail (required):</label>'
		+ '			<input type="email" placeholder="your@email.com" class="form-control" value="" id="registration_email"/>'
		+ '		</div>'
		+ ' 	<div class="col-sm-4"></div>'
		+ '	</div>'
		+ '	<div class="form-group row">'
		+ ' 	<div class="col-sm-4"></div>'
		+ ' 	<div class="col-sm-4">'
		+ '			<label for="registration_country" class="col-form-label">Country:</label>'
		+ '			<input type="text" placeholder="country" class="form-control" value="" id="registration_country"/>'
		+ '		</div>'
		+ ' 	<div class="col-sm-4"></div>'
		+ '	</div>'
		+ '	<div class="form-group row">'
		+ ' 	<div class="col-sm-4"></div>'
		+ ' 	<div class="col-sm-4">'
		+ '			<label for="registration_region" class="col-form-label">Region:</label>'
		+ '			<input type="text" placeholder="region" class="form-control" value="" id="registration_region"/>'
		+ '		</div>'
		+ ' 	<div class="col-sm-4"></div>'
		+ '	</div>'
		+ '	<div class="form-group row">'
		+ ' 	<div class="col-sm-4"></div>'
		+ ' 	<div class="col-sm-4">'
		+ '			<label for="registration_city" class="col-form-label">City:</label>'
		+ '			<input type="text" placeholder="city" class="form-control" value="" id="registration_city"/>'
		+ '		</div>'
		+ ' 	<div class="col-sm-4"></div>'
		+ '	</div>'
		+ '	<div class="form-group row">'
		+ ' 	<div class="col-sm-4"></div>'
		+ ' 	<div class="col-sm-4">'
		+ '			<label for="registration_university" class="col-form-label">University:</label>'
		+ '			<input type="text" placeholder="university" class="form-control" value="" id="registration_university"/>'
		+ '		</div>'
		+ ' 	<div class="col-sm-4"></div>'
		+ '	</div>'
		+ '	<div class="form-group row">'
		+ ' 	<div class="col-sm-4"></div>'
		+ ' 	<div class="col-sm-4 text-center">'
		+ '			<div class="btn btn-success" onclick="fhq.ui.registry();">Registry</div>'
		+ '		</div>'
		+ ' 	<div class="col-sm-4"></div>'
		+ '	</div>'
		+ '	<div class="form-group row">'
		+ ' 	<div class="col-sm-4"></div>'
		+ ' 	<div class="col-sm-4 text-center" id="registration_error">'
		+ '		</div>'
		+ ' 	<div class="col-sm-4"></div>'
		+ '	</div>'
	);
}


fhq.ui.onwsclose = function(){
	$('.message_chat').remove();
	fhq.ui.showLoading();
}


fhq.ui.loadServerSettings = function(idelem) {
	fhq.changeLocationState({'server_settings':''});
	var el = $('#content_page');
	el.html('');
	
	fhq.ws.serversettings().done(function(r){
		fhq.ui.hideLoading();
		console.log(r);
		for(var name in r.data){
			var sett = r.data[name];
			var groupid = 'settings_group_' + sett.group;
			if($('#' + groupid).length == 0){
				el.append(''
					+ '<div class="card">'
					+ '  <div class="card-header">' + fhq.t(groupid) + '</div>'
					+ '  <div class="card-body">'
					+ '   <div id="' + groupid + '">'
					+ '   </div>'
					+ '  </div>'
					+ '</div><br>'
				);
			}
			
			var settid = 'setting_name_' + sett.name;
			
			var input_type = 'text';
			if(sett.type == 'integer'){
				$('#' + groupid).append(''
					+ '<div class="form-group row">'
					+ '	<label for="' + settid + '" class="col-sm-2 col-form-label">' + fhq.t(settid) + '</label>'
					+ '	<div class="col-sm-7">'
					+ '		<input type="number" readonly class="form-control" id="' + settid + '">'
					+ '	</div>'
					+ '	<div class="col-sm-2">'
					+ '		<div class="btn btn-danger edit-settings" groupid="' + groupid + '" setttype="' + sett.type + '" settname="' + sett.name + '" settid="' + settid + '">Edit</div>'
					+ '	</div>'
					+ '</div>'
				);
				$('#' + settid).val(sett.value);
			}else if(sett.type == 'password'){
				$('#' + groupid).append(''
					+ '<div class="form-group row">'
					+ '	<label for="' + settid + '" class="col-sm-2 col-form-label">' + fhq.t(settid) + '</label>'
					+ '	<div class="col-sm-7">'
					+ '		<input type="password" readonly class="form-control" id="' + settid + '">'
					+ '	</div>'
					+ '	<div class="col-sm-2">'
					+ '		<div class="btn btn-danger edit-settings" groupid="' + groupid + '" setttype="' + sett.type + '" settname="' + sett.name + '" settid="' + settid + '">Edit</div>'
					+ '	</div>'
					+ '</div>'
				);
				$('#' + settid).val(sett.value);
			}else if(sett.type == 'string'){
				$('#' + groupid).append(''
					+ '<div class="form-group row">'
					+ '	<label for="' + settid + '" class="col-sm-2 col-form-label">' + fhq.t(settid) + '</label>'
					+ '	<div class="col-sm-7">'
					+ '		<input type="text" readonly class="form-control" id="' + settid + '">'
					+ '	</div>'
					+ '	<div class="col-sm-2">'
					+ '		<div class="btn btn-danger edit-settings" groupid="' + groupid + '" setttype="' + sett.type + '" settname="' + sett.name + '" settid="' + settid + '">Edit</div>'
					+ '	</div>'
					+ '</div>'
				);
				$('#' + settid).val(sett.value);
			}else if(sett.type == 'boolean'){
				$('#' + groupid).append(''
					+ '<div class="form-group row">'
					+ '	<label for="' + settid + '" class="col-sm-2 col-form-label">' + fhq.t(settid) + '</label>'
					+ '	<div class="col-sm-7">'
					+ '		<select disabled class="form-control" id="' + settid + '">'
					+ '			<option name="no">no</option>'
					+ '			<option name="yes">yes</option>'
					+ '		<select class="form-control">'
					+ '	</div>'
					+ '	<div class="col-sm-2">'
					+ '		<div class="btn btn-danger edit-settings" groupid="' + groupid + '" setttype="' + sett.type + '" settname="' + sett.name + '" settid="' + settid + '">Edit</div>'
					+ '	</div>'
					+ '</div>'
				);
				$('#' + settid).val(sett.value == true ? 'yes' : 'no');
			}
		}
		
		
		$('.edit-settings').unbind().bind('click', function(){
			$('#modalSettings').modal('show');
			
			var setttype = $(this).attr('setttype');
			var settname = $(this).attr('settname');
			var settid = $(this).attr('settid');
			var groupid = $(this).attr('groupid');
			
			var val = $('#' + settid).val();
			
			$('#modalSettings .modal-body').html('');
			$('#modalSettings .modal-body').append('<h3>' + fhq.t(groupid) + '/' + fhq.t(settid) + '</h3>')
			
			if(setttype == 'string'){
				$('#modalSettings .modal-body').append(
					'<input type="text" class="form-control" id="modalSettings_newval">'
					+ '<p id="modalSettings_error"></p>'
				);
				$('#modalSettings_newval').val(val);
			}else if(setttype == 'boolean'){
				$('#modalSettings .modal-body').append(''
					+ '		<select class="form-control" id="modalSettings_newval">'
					+ '			<option name="no">no</option>'
					+ '			<option name="yes">yes</option>'
					+ '		<select class="form-control">'
					+ '<p id="modalSettings_error"></p>'
				);
				$('#modalSettings_newval').val(val);
				
					
			}else if(setttype == 'password'){
				$('#modalSettings .modal-body').append(
					'<input type="password" class="form-control" id="modalSettings_newval">'
					+ '<p id="modalSettings_error"></p>'
				);
				$('#modalSettings_newval').val('');
			}else if(setttype == 'integer'){
				$('#modalSettings .modal-body').append(
					'<input type="number" class="form-control" id="modalSettings_newval">'
					+ '<p id="modalSettings_error"></p>'
				);
				$('#modalSettings_newval').val(val);
			}
			
			$('#modalSettings .save-setting').unbind().bind('click', function(){
				$('#modalSettings_newval').attr({'readonly': true});
				$('#modalSettings_newval').attr({'disabled': true});
				$('#modalSettings_error').html('');
				var data = {};
				data.name = settname;
				data.value = $('#modalSettings_newval').val();

				fhq.ws.update_server_settings(data).done(function(r){
					if(setttype != 'password'){
						$('#' + settid).val(data.value);
					}
					$('#modalSettings').modal('hide');
				}).fail(function(err){
					console.error(err);
					$('#modalSettings_newval').removeAttr('readonly');
					$('#modalSettings_newval').removeAttr('disabled');
					$('#modalSettings_error').html(err.error);
				})
				
			});
			// modalSettings
			
		});
		
	}).fail(function(err){
		fhq.ui.hideLoading();
		console.error(err);
	})
	
	
	// 
	/*
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
	);*/
}


fhq.ui.loadChatPage = function(){
	fhq.changeLocationState({'chat':''});
	fhq.ui.hideLoading();
	var el = $('#content_page');
	el.html('<h1>' + fhq.t("Chat") + '</h1>');
	
	/*
	$('#content_page').append('<div class="fhq0049"></div>')
	var el = $('.fhq0046');
	el.append();

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
	el.append('<div class="fhqbtn" onclick="fhq.ui.insertNews()">' + fhq.t("Create") + '</div>');*/
	
}


fhq.ui.loadCreateNews = function(){
	fhq.changeLocationState({'create_news':''});
	
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
	fhq.ui.hideLoading();
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
	fhq.ui.hideLoading();
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
	fhq.ui.hideLoading();
	var el = $('#content_page');
	el.html('');
	
	el.append(''
		+ '<div class="card">'
		+ '	<div class="card-header">' + fhq.t('About') + '</div>'
		+ '	<div class="card-body">'
		+ '		<strong>FreeHackQuest</strong> - ' + fhq.t('This is an open source platform for competitions in computer security.')
		+ '	</div>'
		+ '</div><br>'
		+ '<div class="card">'
		+ '	<div class="card-header">' + fhq.t('Statistics') + '</div>'
		+ '	<div class="card-body">'
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
		+ '	</div>'
		+ '</div><br>'
		+ '<div class="card">'
		+ '	<div class="card-header">' + fhq.t('Playing with us') + '</div>'
		+ '	<div class="card-body">'
		+ '		<div id="statistics-playing-with-us">...</div>'
		+ '	</div>'
		+ '</div><br>'
		+ '<div class="card">'
		+ '	<div class="card-header">' + fhq.t('Top 10') + '</div>'
		+ '	<div class="card-body">'
		+ '		<div id="winners"></div>'
		+ '	</div>'
		+ '</div><br>'
		+ '<div class="card">'
		+ '	<div class="card-header">' + fhq.t('Contacts') + '</div>'
		+ '	<div class="card-body">'
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
		+ '</div>'
		+ '	</div>'
		+ '</div><br>'
		+ '<div class="card">'
		+ '	<div class="card-header">' + fhq.t('Distribution') + '</div>'
		+ '	<div class="card-body">'
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
		+ '</div>'
		+ '	</div>'
		+ '</div><br>'
		+ '<div class="card">'
		+ '	<div class="card-header">' + fhq.t('Developers and designers') + '</div>'
		+ '	<div class="card-body">'
		+ '		<ul>'
		+ '			<li>Evgenii Sopov</li>'
		+ '			<li>Used bootstrap-4</li>'
		+ '		<ul>'
		+ '	</div>'
		+ '</div><br>'
		+ '<div class="card">'
		+ '	<div class="card-header">' + fhq.t('Thanks for') + '</div>'
		+ '	<div class="card-body">'
		+ '		<ul>'
		+ '			<li><a href="http://www.chartjs.org/docs/" target="_blank">Charts.js</a></li>'
		+ '			<li>Sergey Belov (found xss!)</li>'
		+ '			<li>Igor Polyakov</li>'
		+ '			<li>Maxim Samoilov (Nitive)</li>'
		+ '			<li>Dmitrii Mukovkin</li>'
		+ '			<li>Team Keva</li>'
		+ '			<li>Alexey Gulyaev</li>'
		+ '			<li>Alexander Menschikov</li>'
		+ '			<li>Ilya Bokov</li>'
		+ '			<li>Extrim Code</li>'
		+ '			<li>Taisiya Lebedeva</li>'
		+ '		<ul>'
		+ '	</div>'
		+ '</div><br>'
		+ '<div class="card">'
		+ '	<div class="card-header">' + fhq.t('Donate') + '</div>'
		+ '	<div class="card-body">'
		+ '		<div id="donate-form"></div>'
		+ '	</div>'
		+ '</div><br>'
	);

	fhq.ui.loadCities();
	
	$.get('donate.html', function(result){
		$('#donate-form').html(result);
	});
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
	var el = $("#content_page");
	el.html('Loading...');

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
		el.html('<h1>' + fhq.t('Scoreboard') + '</h1>');
		el.append('<div class="fhq0087"></div>');
		
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
	});
}

fhq.ui.loadApiPage = function() {
	window.fhq.changeLocationState({'api':''});
	var el = $('#content_page');
	
	el.html('<h1>FreeHackQuest API</h1>Loading...');
	fhq.ws.api().done(function(r){
		el.html('<h1>FreeHackQuest API (version: ' + r.version + ')</h1>');
		fhq.ui.hideLoading();
		
		// <div class="card">  <div class="card-body">    <h4 class="card-title">Admin</h4>    <h6 class="card-subtitle mb-2 text-muted">(10 quests)</h6>    <p class="card-text">Администрирование</p>	   <button subject="admin" type="button" class="open-subject btn btn-default">Открыть</button>	   <button subject="admin" type="button" class="best-subject-users btn btn-default">Best users</button>  </div></div>
		
		el.append(''
			+ '<div class="card">'
			+ '	<div class="card-header">Connection</div>'
			+ '	<div class="card-body">'
			+ '		Connection string: ws://' + fhq.ws.hostname + ':' + fhq.ws.port + '/ <br> '
			+ '		Or if enabled ssl: wss://' + fhq.ws.hostname + ':' + r.data.ssl_port + '/ - with ssl</p>'
			+ '		<p>For example: <br><code>var socket = new WebSocket("wss://freehackquest.com:' + r.data.ssl_port + '/");</code></p>'
			+ '	</div>'
			+ '</div><br>'
			+ '<div class="card">'
			+ '	<div class="card-header">Start communication with server</div>'
			+ '	<div class="card-body">'
			+ '		<p>Fisrt command must be hello and next login if you have api token'
			+ '		<p>For example: <br><code>socket.send(JSON.stringify({cmd: "hello", "m": "m100"}))</code>'
			+ '	</div>'
			+ '</div><br>'
			+ '<div class="card">'
			+ '	<div class="card-header">' + fhq.t('Implementation') + '</div>'
			+ '	<div class="card-body">'
			+ '		<p>You can find this:</p>'
			+ '		<p>Config: <a href="https://freehackquest.com/js/fhq.ws.js" target="_blank">https://freehackquest.com/js/fhq.ws.config.js</a></p>'
			+ '		<p>Wrapper by api: <a href="https://freehackquest.com/js/fhq.ws.js" target="_blank">https://freehackquest.com/js/fhq.ws.js</a></p>'
			+ '	</div>'
			+ '</div><br>'
		);

		for(var i in r.data.handlers){
			var h = r.data.handlers[i];
			
			var ins = '';
			if(h.inputs.length != 0){
				ins += '<p>' + fhq.t('Input\'s parameters') + ':</p><ul>';
				for(var i1 in h.inputs){
					var inp = h.inputs[i1];
					ins += '<li><strong>' + inp.type + '</strong> "' + inp.name + '" (' + inp.restrict + ') - <i>' + inp.description + '</i></li>'
				}
				ins += '</ul>';
			}
			
			el.append(''
				+ '<div class="card">'
				+ '	<div class="card-header">' + h.cmd + '</div>'
				+ '	<div class="card-body">'
				+ '		<h4 class="card-title"><code>cmd: ' + h.cmd + '</code></h4>'
				+ '		<p class="card-text">' + h.description + ' </p>'
				+ '		<p>' + fhq.t('Access') + ':</p><ul>'
				+ '			<li>' + fhq.t('Unauthorized') + ': ' + (h.access_unauthorized ? 'allow': 'deny') + '</li>'
				+ '			<li>' + fhq.t('User') + ': ' + (h.access_user ? 'allow': 'deny') + '</li>'
				+ '			<li>' + fhq.t('Tester') + ': ' + (h.access_tester ? 'allow': 'deny') + '</li>'
				+ '			<li>' + fhq.t('Admin') + ': ' + (h.access_admin ? 'allow': 'deny') + '</li>'
				+ '		</ul>'
				+ ins
				+ '	</div>'
				+ '</div><br>'
			);
		}
	}).fail(function(r){
		fhq.ui.hideLoading();
		console.error(r);
	})
}

fhq.ui.loadUserProfile = function(userid) {
	if(!userid){
		userid = fhq.userinfo ? fhq.userinfo.id : userid;
	}else{
		userid = parseInt(userid,10);
	}

	fhq.ui.showLoading();
	window.fhq.changeLocationState({'user':userid});

	var el = $('#content_page');
	el.html('Loading...')
	
	fhq.ws.user({userid: userid}).done(function(user){
		fhq.ui.hideLoading();
		
		var converter = new showdown.Converter();
		el.html('');
		el.append(''
			+ '<div class="card">'
			+ '  <div class="card-body card-left-img " style="background-image: url(' + user.data.logo + ')">'
			+ '    <h4 class="card-title">' + user.data.nick + ' (Rating: ' + user.data.rating + ')</h4>'
			+ '    <h6 class="card-subtitle mb-2 text-muted">User ' + user.data.status + '. User has ' + user.data.role + ' privileges.</h6>'
			+ '    <p class="card-text"> '
			+ '		</p>'
			+ '  </div>'
			+ '</div><br>'
			+ '<div class="card">'
			+ '	<div class="card-header">' + fhq.t('Location') + '</div>'
			+ '	<div class="card-body">'
			+ '		<p>' + fhq.t('Country') + ': ' + user.data.country + '</p>'
			+ '		<p>' + fhq.t('Region') + ': ' + user.data.region + '</p>'
			+ '		<p>' + fhq.t('City') + ': ' + user.data.city + '</p>'
			+ '		<p>' + fhq.t('University') + ': ' + user.data.university + '</p>'
			+ '	</div>'
			+ '</div><br>'
			+ '<div class="card">'
			+ '	<div class="card-header">' + fhq.t('About user') + '</div>'
			+ '	<div class="card-body">'
			+ '		<p>' + converter.makeHtml(user.data.about == '' ? fhq.t('Missing information') : user.data.about) + '</p>'
			+ '	</div>'
			+ '</div><br>'
			+ '<div class="card">'
			+ '	<div class="card-header">' + fhq.t('Skills') + '</div>'
			+ '	<div class="card-body">'
			+ '		<p id="user_skills">Loading...</p>'
			+ '	</div>'
			+ '</div><br>'
		);
		if(user.access){
			el.append(''
				+ '<div class="card">'
				+ '	<div class="card-header">' + fhq.t('Change password') + '</div>'
				+ '	<div class="card-body">'
				+ '		<div class="form-group row">'
				+ '			<label for="old_password" class="col-sm-2 col-form-label">Old password</label>'
				+ ' 		<div class="col-sm-10">'
				+ '				<input type="password" class="form-control" value="" id="old_password">'
				+ '			</div>'
				+ '		</div>'
				+ '		<div class="form-group row">'
				+ '			<label for="new_password" class="col-sm-2 col-form-label">New password</label>'
				+ ' 		<div class="col-sm-10">'
				+ '				<input type="password" class="form-control" value="" id="new_password">'
				+ '			</div>'
				+ '		</div>'
				+ '		<div class="form-group row">'
				+ '			<label class="col-sm-2 col-form-label"></label>'
				+ ' 		<div class="col-sm-10">'
				+ '				<div class="btn btn-danger" id="change_password">Change password</div>'
				+ '				<p id="change_password_info"></p>'
				+ '			</div>'
				+ '		</div>'
				+ '	</div>'
				+ '</div><br>'
			);
			
			$('#change_password').unbind().bind('click', function(){
				$('#change_password_info').html('Send request...');
				var data = {};
				data.password_old = $('#old_password').val();
				data.password_new = $('#new_password').val();
				fhq.ws.user_change_password(data).done(function(){
					$('#old_password').val('');
					$('#new_password').val('');
					$('#change_password_info').html('Changed');
				}).fail(function(err){
					$('#old_password').val('');
					$('#new_password').val('');
					$('#change_password_info').html(err.error);
				});
			});
		}

			
		


		if(fhq.isAdmin()){
			/*var c = '<div class="fhq0051">';
			c += '<div class="fhqbtn" id="quest_edit">' + fhq.t('Edit') + '</div>';
			c += '<div class="fhqbtn" id="quest_delete">' + fhq.t('Delete') + '</div>';
			c += '<div class="fhqbtn" id="quest_export">' + fhq.t('Export') + '</div>';
			c += '<div class="fhqbtn" id="quest_report">' + fhq.t('Report an error') + '</div>';
			c += '</div>'
			el.append(c);*/
		}
		
		fhq.ws.user_skills({userid: user.data.id}).done(function(r){
			
			$('#user_skills').html('');
			console.log(r);
			var anim = {};
			for(var subject in r.skills_max){
				var user_s = r.skills_user[subject] ? r.skills_user[subject] : 0;
				var max_s = r.skills_max[subject];
				var procent = Math.floor((user_s / max_s)*100);
				anim[subject] = procent;
				$('#user_skills').append('<div class="fhq0117">'
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
		fhq.ui.hideLoading();
		el.html(r.error);
		return;
	});
}

fhq.ui.loadFeedbackAdd = function() {
	window.fhq.changeLocationState({'feedback_add':''});
	fhq.ui.hideLoading();
	var el = $('#content_page');
	el.html(''
		+ '<div class="card">'
		+ '		<div class="card-header">' + fhq.t("Feedback") + '</div>'
		+ '		<div class="card-body">'
		+ '			<div class="form-group row">'
		+ '				<label for="newfeedback_type" class="col-sm-2 col-form-label">' + fhq.t("Target") + '</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<select class="form-control" id="newfeedback_type">'
		+ '						<option value="question">' + fhq.t("question") + '</option>'
		+ '						<option value="complaint">' + fhq.t("complaint") + '</option>'
		+ '						<option value="defect">' + fhq.t("defect") + '</option>'
		+ '						<option value="error">' + fhq.t("error") + '</option>'
		+ '						<option value="approval">' + fhq.t("approval") + '</option>'
		+ '						<option value="proposal">' + fhq.t("proposal") + '</option>'
		+ '					</select>'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row" id="feedback_from_field">'
		+ '				<label for="newfeedback_from" class="col-sm-2 col-form-label">' + fhq.t("From") + '</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<input type="email" placeholder="youmail@domain.com" class="form-control" value="" id="newfeedback_from">'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newfeedback_text" class="col-sm-2 col-form-label">' + fhq.t("Message") + '</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<textarea type="text" placeholder="Message" class="form-control" style="height: 150px" value="" id="newfeedback_text"></textarea>'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="newgame_description" class="col-sm-2 col-form-label"></label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<div class="btn btn-danger" id="newfeedback_send" >' + fhq.t("Send") +' </div>'
		+ '				</div>'
		+ '			</div>'
		+ '		</div>'
		+ '</div>'
	);
	
	if(fhq.userinfo){
		$('#newfeedback_from').attr({'readonly': ''});
		$('#newfeedback_from').val(fhq.userinfo.email);
	}
	
	$('#newfeedback_send').unbind().bind('click', function(){
		fhq.ui.showLoading();
		
		var data = {};
		data.type = $('#newfeedback_type').val();
		data.from = $('#newfeedback_from').val();
		data.text = $('#newfeedback_text').val();
		
		fhq.ws.feedback_add(data).done(function(r){
			el.html('Thanks!');
			fhq.ui.hideLoading();
		}).fail(function(r){
			fhq.ui.hideLoading();
			fhq.ui.showModalDialog({
				'header' : fhq.t('Error'),
				'content' : r.error,
				'buttons' : ''
			});
		})
	});
}

fhq.ui.confirmDialog = function(msg, onclick_yes){
	fhq.ui.showModalDialog({
		'header' : fhq.t('Confirm'),
		'content' : msg,
		'buttons' : '<div class="fhqbtn" onclick="' + onclick_yes + '">' + fhq.t('Yes') + '</div>'
	});
}

fhq.ui.loadGames = function() {
	fhq.ui.showLoading();
	window.fhq.changeLocationState({'games':''});
	var el = $('#content_page');
	
	el.html('');
	fhq.ws.games().done(function(r){
		console.log(r);
		for (var k in r.data) {
			if (r.data.hasOwnProperty(k)) {
				var game = r.data[k];
				var buttons = '';
				var perms = game.permissions;
	
				if (fhq.isAdmin())
					buttons += '<div class="btn btn-danger" onclick="formDeleteGame(' + game.id + ');">' + fhq.t('Delete') + '</div>';

				if (fhq.isAdmin())
					buttons += ' <div class="btn btn-danger" onclick="formEditGame(' + game.id + ');">' + fhq.t('Edit') + '</div>';
					
				if (fhq.isAdmin())
					buttons += ' <div class="btn btn-danger" onclick="fhqgui.exportGame(' + game.id + ');">' + fhq.t('Export') + '</div>';
				
				el.append(''
					+ '<div class="card">'
					+ '		<div class="card-body card-left-img admin" style="background-image: url(' + game.logo + ')">'
					+ '			<h4 class="card-title">' + game.title +' (' + fhq.t('Maximal score') + ': ' + game.maxscore + ')</h4>'
					+ '			<h6 class="card-subtitle mb-2 text-muted">' + game.type_game + ', ' + game.date_start + ' - ' + game.date_stop + '</h6>'
					+ '			<h6 class="card-subtitle mb-2 text-muted">' + fhq.t('Organizators') + ': ' + game.organizators + '</h6>'
					+ '			<p class="card-text">' + game.description + '</p>'
					+ '			<p class="card-text">' + buttons + '</p>'
					+ '		</div>'
					+ '</div>'
				);
				
				  // <div class="card-body card-left-img admin" style="background-image: url(images/quests/admin_150x150.png)">    
					// <h4 class="card-title">Admin</h4>    <h6 class="card-subtitle mb-2 text-muted">(10 quests)</h6>    <p class="card-text">Администрирование</p>	   <button subject="admin" type="button" class="open-subject btn btn-default">Открыть</button>  </div></div>
				
				
				// el.append(fhq.ui.gameView(r.data[k]));
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
	
	

	content += '			</div>';
	content += '		</div>';
	content += '	</div>';
	content += '</div>'
	content += '<div class="fhq0028"></div>';
	return content;
}

fhq.ui.loadFeedback = function() {
	fhq.ui.showLoading();
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
	fhq.changeLocationState({'quests':''});
	fhq.ui.showLoading();
	var el = $('#content_page');
	el.html('Loading...');
	fhq.ws.quests_subjects().done(function(r){
		console.log(r);
		el.html('');
		for(var i in r.data){
			var o = r.data[i];
			el.append(''
				+ '<div class="card">'
				+ '  <div class="card-body card-left-img ' + o.subject + '" style="background-image: url(images/quests/' + o.subject + '_150x150.png)">'
				+ '    <h4 class="card-title">' + fhq.ui.capitalizeFirstLetter(o.subject) + '</h4>'
				+ '    <h6 class="card-subtitle mb-2 text-muted">(' + o.count + ' quests)</h6>'
				+ '    <p class="card-text">' + fhq.t(o.subject + '_description') + '</p>'
				+ '	   <button subject="' + o.subject + '" type="button" class="open-subject btn btn-default">' + fhq.t('Open') + '</button>'
				// + '	   <button subject="' + o.subject + '" type="button" class="best-subject-users btn btn-default">' + fhq.t('Best users') + '</button>'
				+ '  </div>'
				+ '</div><br>'
			);
		}
		
		$('.open-subject').unbind().bind('click', function(){
			fhq.ui.loadQuestsBySubject($(this).attr('subject'));
		})
		
		$('.best-subject-users').unbind().bind('click', function(){
			// fhq.ui.loadQuestsBySubject($(this).attr('subject'));
			alert("TODO");
		})
		fhq.ui.hideLoading();
	}).fail(function(r){
		fhq.ui.hideLoading();
		console.error(r);
		el.html('Failed');
	});
}

fhq.ui.loadQuestsBySubject = function(subject){
	fhq.ui.showLoading();
	fhq.changeLocationState({'subject':subject});
	var el = $('#content_page');

	el.html('<div class="fhq0005">Loading...</div>');
	var params = {};
	params.subject = subject;
	fhq.ws.quests(params).done(function(r){
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
		fhq.ui.hideLoading();
		console.error(r)
		$('.fhq0005').html('Failed');
	});
}

fhq.ui.map = null;
fhq.ui.map_from_server = null;
fhq.ui.map_markers = [];
fhq.ui.map_init = function() {
	console.log("fhq.ui.map_init begin");
	fhq.ui.map_markers = [];
	
	var fhq_main_server = new google.maps.LatLng(50.7374, 7.09821);

	fhq.ui.map = new google.maps.Map(document.getElementById('map'), {
		center: fhq_main_server,
		zoom: 3
	});
		
	for(var i = 0; i < fhq.ui.map_from_server.data.length; i++){
		var t = fhq.ui.map_from_server.data[i];
		for(var y = 0; y < t.count; y++){
			fhq.ui.map_markers.push(new google.maps.Marker({
				position: new google.maps.LatLng(t.lat, t.lng),
				map: fhq.ui.map
			}));
		}
	}

	fhq.ui.map_markers.push(new google.maps.Marker({
		position: fhq_main_server,
		map: fhq.ui.map,
		label: 'fhq'
	}));
	var markerCluster = new MarkerClusterer(fhq.ui.map, fhq.ui.map_markers, 
		{imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
}

fhq.ui.loadMapPage = function(subject){
	fhq.ui.showLoading();
	fhq.changeLocationState({'map':''});
	var el = $('#content_page');

	el.html('Loading...');

	fhq.ws.getmap().done(function(r){
		fhq.ui.hideLoading();
		fhq.ui.map_from_server = r;
		r.map_key
		
		el.html('<h1>' + fhq.t('Map') + '</h1><div id="map"></div>');
		if($('#google_map_api').length == 0){
			$('head').append('<script id="google_map_api" src="https://maps.googleapis.com/maps/api/js?key=' + r.google_map_api_key + '&callback=fhq.ui.map_init" async defer></script>');
		}else{
			fhq.ui.map_init();
		}

	}).fail(function(r){
		fhq.ui.hideLoading();
		console.error(r)
		el.html('Failed');
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
				'GameID: "' + q.gameid + '"\n'
				+ 'Quest: ' + q.name + ', ID: #' + q.id + '\n'
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
					fhq.ws.quest_pass({questid: q.id, answer: answer}).done(function(r){
						fhq.ui.loadQuest(q.id);
					}).fail(function(r){
						$('#quest_pass_error').html(r.error);
						/*if(fhq.ui.isShowMyAnswers()){
							fhq.ui.updateMyAnswers(q.questid);
						}*/
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
	if(fhq.userinfo){
		$('#feedback-from').attr({'readonly': ''});
		$('#feedback-from').val(fhq.userinfo.email);
	}
	$('#feedback-text').val(text);
}

fhq.ui.feedbackDialogSend = function(){
	var text = $('#feedback-text').val();
	var from = $('#feedback-from').val();
	var type = $('#feedback-type').val();
	var params = {};
	params.type = type;
	params.from = from;
	params.text = text;
	fhq.ui.showLoading();
	fhq.ws.feedback_add(params).done(function(){
		fhq.ui.closeModalDialog();
		fhq.ui.hideLoading();
	}).fail(function(r){
		alert(r.error);
		fhq.ui.hideLoading();
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
	fhq.changeLocationState({'classbook':''});
	fhq.ui.hideLoading();
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
		+ '<div class="card" id="feedback-form">'
		+ '		<div class="card-header">' + fhq.t("Feedback") + '</div>'
		+ '		<div class="card-body">'
		+ '			<div class="form-group row hide">'
		+ '				<label for="feedback-type" class="col-sm-2 col-form-label">' + fhq.t("Target") + '</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<select class="form-control" id="feedback-type">'
		+ '						<option value="error">' + fhq.t("error") + '</option>'
		+ '					</select>'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row" id="feedback_from_field">'
		+ '				<label for="feedback-from" class="col-sm-2 col-form-label">' + fhq.t("From") + '</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<input type="email" placeholder="youmail@domain.com" class="form-control" value="" id="feedback-from">'
		+ '				</div>'
		+ '			</div>'
		+ '			<div class="form-group row">'
		+ '				<label for="feedback-text" class="col-sm-2 col-form-label">' + fhq.t("Message") + '</label>'
		+ ' 			<div class="col-sm-10">'
		+ '					<textarea type="text" placeholder="Message" class="form-control" style="height: 150px" value="" id="feedback-text"></textarea>'
		+ '				</div>'
		+ '			</div>'
		+ '		</div>'
		+ '</div>'
	
	return {
		'header' : title_text,
		'content': content,
		'buttons': '<div class="btn btn-danger" onclick="fhq.ui.feedbackDialogSend();">' + fhq.t('Send') + '</div>'
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

