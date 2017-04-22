if(!window.fhq) window.fhq = {};
if(!window.fhq.api) window.fhq.api = {};
if(!window.fhq.api.users) window.fhq.api.users = {};
if(!window.fhq.api.events) window.fhq.api.events = {};
if(!window.fhq.api.quests) window.fhq.api.quests = {};

window.fhq.createUrlFromObj = function(obj) {
	var str = "";
	for (k in obj) {
		if (str.length > 0)
			str += "&";
		str += encodeURIComponent(k) + "=" + encodeURIComponent(obj[k]);
	}
	return str;
};

window.fhq.getCurrentApiPath = function() {
	var path = location.pathname.split("/");
	path.splice(path.indexOf('index.php'), 1);	
	var newURL = location.protocol + '//' + location.host + path.join("/") + "/";
	return newURL;
};

window.fhq.setTokenToCookie = function(token) {
	var date = new Date( new Date().getTime() + (7 * 24 * 60 * 60 * 1000) ); // cookie on week
	document.cookie = "fhqtoken=" + encodeURIComponent(token) + "; path=/; expires="+date.toUTCString();
}

window.fhq.removeTokenFromCookie = function() {
	document.cookie = "fhqtoken=; path=/;";
}

window.fhq.getTokenFromCookie = function() {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + "fhqtoken".replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : '';
}

window.fhq.baseUrl = "http://freehackquest.com/";
window.fhq.client = "fhq.js";
fhq.token = fhq.getTokenFromCookie();

		
window.fhq.profile = {
	lastEventId: 0,
	bInitUserProfile: false
};

// TODO deprecated redesign to $.ajax
// post request to server Async
window.fhq.sendPostRequest_Async = function(page, params, callbackf) {
	var tmpXMLhttp = null;
	params.token = this.token;
	// alert(this.createUrlFromObj(params));

	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		tmpXMLhttp = new window.XMLHttpRequest();
	};
	tmpXMLhttp.onreadystatechange=function() {
		if (tmpXMLhttp.readyState==4 && tmpXMLhttp.status==200) {
			if(tmpXMLhttp.responseText == "")
				obj = { "result" : "fail" };
			else
			{
				try {
					var obj = JSON.parse(tmpXMLhttp.responseText);
					callbackf(obj);
				} catch(e) {
					alert(e.name + ":" + e.message + "\n stack:" + e.stack + "\n" + tmpXMLhttp.responseText);
				}
				delete obj;
				delete tmpXMLhttp;
			}
		}
	}
	tmpXMLhttp.open("POST", this.baseUrl + page, true);
	tmpXMLhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	tmpXMLhttp.send(this.createUrlFromObj(params));
};

window.fhq.sendPostRequest_Sync = function(page, params) {
	params.token = this.token;
	// alert(this.createUrlFromObj(params));
	
	var tmpXMLhttp = null;
	
	var obj = null;
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		tmpXMLhttp = new window.XMLHttpRequest();
	};
	tmpXMLhttp.onreadystatechange=function() {
		if (tmpXMLhttp.readyState==4 && tmpXMLhttp.status==200) {
			if(tmpXMLhttp.responseText == "")
				obj = { "result" : "fail" };
			else
			{
				obj = JSON.parse(tmpXMLhttp.responseText);
				delete tmpXMLhttp;
			}
		}
	}
	tmpXMLhttp.open("POST", this.baseUrl + page, false);
	tmpXMLhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	tmpXMLhttp.send(this.createUrlFromObj(params));
	return obj;
};

window.fhq.supportsHtml5Storage = function() {
	try {
		return 'localStorage' in window && window['localStorage'] !== null;
	} catch (e) {
		return false;
	}
}


window.fhq.security = new (function(t) {
	this.p = t;
	this.registration = function(email, captcha, callback) {
		var params = {};
		params.email = email;
		params.captcha = captcha;
		this.p.sendPostRequest_Async('api/security/registration.php', params, callback);
	};
	this.resetPassword = function(email, captcha, callback) {
		var params = {};
		params.email = email;
		params.captcha = captcha;
		this.p.sendPostRequest_Async('api/security/restore.php', params, callback);
	};
})(window.fhq);

fhq.isAuth = function(){
	return fhq.token != "";
}

fhq.isAdmin = function(){
	return fhq.userinfo.role == "admin";
}

fhq.security.login = function (email, password) {
	var params = {};
	params.email = email;
	params.password = password;
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/security/login.php',
		data: params
	}).done(function(r){
		if (r.result == 'ok') {
			fhq.token = r.data.token;
			fhq.userinfo = r.data.session.user;
			localStorage.setItem('userinfo', JSON.stringify(fhq.userinfo));
			fhq.setTokenToCookie(r.data.token);
			try{fhq.ws.socket.close();fhq.ws.initWebsocket()}catch(e){console.error(e)};
			d.resolve(r);
		}else{
			fhq.token = "";
			fhq.removeTokenFromCookie();
			d.reject(r);
		}
	}).fail(function(r){
		d.reject(r);
	})
	return d;
};

fhq.security.logout = function () {
	var params = {};
	params.token = fhq.token;
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/security/logout.php',
		data: params
	}).done(function(r){
		fhq.token = "";
		fhq.removeTokenFromCookie();
		localStorage.removeItem('userinfo');
		try{fhq.ws.socket.close();fhq.ws.initWebsocket()}catch(e){console.error(e)};
		d.resolve(r);
	}).fail(function(r){
		fhq.token = "";
		fhq.removeTokenFromCookie();
		d.reject(r);
	})
	return d;
};

window.fhq.games = new (function(t) {
	this.p = t;
	this.list = function() {
		var params = {};
		var obj = this.p.sendPostRequest_Sync('api/games/list.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj.data : obj.error;
	};
	this.get = function(gameid) {
		var params = {};
		params.id = gameid;
		var obj = this.p.sendPostRequest_Sync('api/games/get.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj.data : obj.error;
	};
	this.choose = function(gameid) {
		var params = {};
		params.id = gameid;
		var obj = this.p.sendPostRequest_Sync('api/games/choose.php', params);
		// alert(JSON.stringify(params));
		var bRes = obj.result == "ok";
		return bRes ? obj : obj.error;
	};
	this.export = function(gameid) {
		var win = window.open(this.p.baseUrl + 'api/games/export.php?gameid=' + gameid, '_blank');
		win.focus();
	};
})(window.fhq);

window.fhq.quests = new (function(t) {
	this.p = t;
	// return all information if you are admin
	this.get_all = function(questid) {
		var params = {};
		params.questid = questid;
		var obj = this.p.sendPostRequest_Sync('api/quests/get_all.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj.data : obj.error;
	};
	// remove quest if you are admin
	this.delete = function(questid) {
		var params = {};
		params.questid = questid;
		var obj = this.p.sendPostRequest_Sync('api/quests/delete.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj.data : obj.error;
	};
	// insert quest if you are admin
	this.insert = function(quest_uuid, name, short_text, text, score, min_score, subject, idauthor, author, answer, state, description_state) {
		var params = {};
		params.quest_uuid = quest_uuid;
		params.name = name;
		params.short_text = short_text;
		params.text = text;
		params.score = score;
		params.min_score = min_score;
		params.subject = subject;
		params.idauthor = idauthor;
		params.author = author;
		params.answer = answer;
		params.state = state;
		params.description_state = description_state;
		var obj = this.p.sendPostRequest_Sync('api/quests/insert.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj.data : obj.error;
	};
	// update quest info if you are admin
	this.update = function(questid, name, short_text, text, score, min_score, subject, idauthor, author, answer, state, description_state) {
		var params = {};
		params.questid = questid;
		params.name = name;
		params.short_text = short_text;
		params.text = text;
		params.score = score;
		params.min_score = min_score;
		params.subject = subject;
		params.idauthor = idauthor;
		params.author = author;
		params.answer = answer;
		params.state = state;
		params.description_state = description_state;
		var obj = this.p.sendPostRequest_Sync('api/quests/update.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj.data : obj.error;
	};
	// update quest info if you are admin
	this.user_answers = function(questid) {
		var params = {};
		params.questid = questid;
		var obj = this.p.sendPostRequest_Sync('api/quests/user_answers.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj.data : obj.error;
	};
	this.export = function(questid) {
		var win = window.open(this.p.baseUrl + 'api/quests/export.php?questid=' + questid, '_blank');
		win.focus();
	};
})(window.fhq);

window.fhq.api.quests.quest = function(id){
	var params = {};
	params.taskid = id;
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/quests/get/',
		data: params
	}).done(function(response){
		if (response.result == 'ok') {
			d.resolve(response);
		}else{
			d.reject(response);
		}
	}).fail(function(){
		d.reject();
	})
	return d;
}

window.fhq.api.quests.insert = function(params){
	params = params || {};
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/quests/insert/',
		data: params
	}).done(function(response){
		if (response.result == 'ok') {
			d.resolve(response);
		}else{
			d.reject(response);
		}
	}).fail(function(){
		d.reject();
	})
	return d;
}

window.fhq.api.quests.statistics = function(id){
	var params = {};
	params.questid = id;
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/quests/statistics/',
		data: params
	}).done(function(response){
		if (response.result == 'ok') {
			d.resolve(response);
		}else{
			d.reject(response);
		}
	}).fail(function(){
		d.reject();
	})

	return d;
}


window.fhq.api.quests.pass = function(questid, answer){
	var params = {};
	params.questid = questid;
	params.answer = answer;
		
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/quests/pass/',
		data: params
	}).done(function(response){
		if (response.result == 'ok') {
			d.resolve(response);
		}else{
			d.reject(response);
		}
	}).fail(function(){
		d.reject();
	})

	return d;
}

window.fhq.api.quests.list = function(params){
	params = params || {};
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/quests/list/',
		data: params
	}).done(function(response){
		if (response.result == 'ok') {
			d.resolve(response);
		}else{
			d.reject(response);
		}
	}).fail(function(){
		d.reject();
	})
	return d;
};

if(!window.fhq.api.feedback) window.fhq.api.feedback = {};

window.fhq.api.feedback.add = function(params){
	params = params || {};
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/feedback/insert/',
		data: params
	}).done(function(response){
		if (response.result == 'ok') {
			d.resolve(response);
		}else{
			d.reject(response);
		}
	}).fail(function(){
		d.reject();
	})
	return d;
}

window.fhq.api.events.count = function() {
	
	var d = $.Deferred();
	fhq.api.users.getLastEventId().done(function(lasteventid){
		var params = {};
		params['id'] = lasteventid;
		$.ajax({
			type: "POST",
			url: 'api/events/count.php',
			data: params
		}).done(function(response){
			if (response.result == 'ok') {
				d.resolve(response.data.count);
			}else{
				d.resolve(0);
			}
		}).fail(function(){
			d.resolve(0);
		})
	}).fail(function(){
		d.resolve(0);
	});
	return d;
};

window.fhq.api.users.getLastEventId = function(){
	var d = $.Deferred();
	var params = {};
	$.ajax({
		type: "POST",
		url: 'api/users/get.php',
		data: params
	}).done(function(response){
		if (response.result == 'ok') {
			fhq.profile.lastEventId = response.profile.lasteventid;
			d.resolve(fhq.profile.lastEventId);
		}else{
			d.resolve(0);
		}
	}).fail(function(){
		d.resolve(0);
	})
	return d;
};

window.fhq.users = new (function(t) {
	this.fhq = t;
	this.setLastEventId = function(id) {
		var params = {};
		params['id'] = id;
		this.fhq.sendPostRequest_Async('api/users/update_lasteventid.php', params, function(obj) {});
		this.fhq.profile.lastEventId = id;
	};
	this.get = function(userid, callback) {
		var obj = null;
		var params = {};
		params.userid = userid;
		this.fhq.sendPostRequest_Async('api/users/get.php', params, callback);
	}
	this.skills = function(userid, callback) {
		var obj = null;
		var params = {};
		params.userid = userid;
		this.fhq.sendPostRequest_Async('api/statistics/skills.php', params, callback);
	}
})(window.fhq);

fhq.users.initProfile = function(){
	var params = {};
	params.token = fhq.token;
	var d = $.Deferred();
	if(fhq.profile.bInitUserProfile){
		d.resolve();
		return d;
	}
	$.ajax({
		type: "POST",
		url: 'api/users/get.php',
		data: params
	}).done(function(r){
		if (r.result == 'ok') {
			if (r.currentUser == true) {
				fhq.profile.bInitUserProfile == true;
				fhq.profile.lastEventId = r.profile.lasteventid;
				fhq.profile.template = r.profile.template;
				fhq.profile.university = r.profile.university;
				fhq.profile.country = r.profile.country;
				fhq.profile.city = r.profile.city;
				fhq.profile.game = r.profile.game;
				fhq.userinfo = {};
				fhq.userinfo.id = r.data.userid;
				fhq.userinfo.nick = r.data.nick;
				fhq.userinfo.email = r.data.email;
				fhq.userinfo.role = r.data.role;
				fhq.userinfo.logo = r.data.logo;
				if(fhq.profile.game && r.games){
					for(var i in r.games){
						if(r.games[i].gameid == fhq.profile.game.id){
							fhq.userinfo.score = r.games[i].score
						}
					}
				}
				// fhq.userinfo.status = r.data.status;
			}
			d.resolve(r);
		}else{
			fhq.token = "";
			fhq.removeTokenFromCookie();
			d.reject(r);
		}
	}).fail(function(r){
		fhq.token = "";
		fhq.removeTokenFromCookie();
		d.reject(r);
	})
	return d;
}

if(localStorage.getItem('userinfo') != null){
	fhq.userinfo = JSON.parse(localStorage.getItem('userinfo'));
}

fhq.users.initProfile();

window.fhq.statistics = new (function(t) {
	this.p = t;
	this.answerlist = function(params, callback) {
		// TODO redesign
		if (callback)
			this.p.sendPostRequest_Async('api/statistics/answerlist.php', params, callback);
		else
			return this.p.sendPostRequest_Sync('api/statistics/answerlist.php', params);
	};
})(window.fhq);

window.fhq.statistics.myanswers = function(questid){
	var params = {};
	params.questid = questid;
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/statistics/user_answers/',
		data: params
	}).done(function(response){
		if (response.result == 'ok') {
			d.resolve(response);
		}else{
			d.reject();
		}
	}).fail(function(){
		d.reject(0);
	})
	return d;
}

window.fhq.enums = null;

window.fhq.publicInfo = function(callback) {
	this.sendPostRequest_Async('api/public/info.php', {}, callback);
}

window.fhq.initTypes = function() {
	if (this.enums != null)
		return;
	var obj = this.sendPostRequest_Sync('api/public/types.php', {});
	this.enums = obj.data;
}

// enums
window.fhq.getQuestUserStatus = function() {
	this.initTypes();
	return this.enums.questUserStatus;
};

window.fhq.getQuestUserStatusFilter = function() {
	this.initTypes();
	return this.enums.questUserStatusFilter;
};

window.fhq.getQuestTypes = function() {
	this.initTypes();
	return this.enums.questTypes;
};

window.fhq.getQuestTypesFilter = function() {
	this.initTypes();
	return this.enums.questTypesFilter;
};

window.fhq.getQuestStates = function() {
	this.initTypes();
	return this.enums.questStates;
};

window.fhq.getGameTypes = function() {
	this.initTypes();
	return this.enums.gameTypes;
};

window.fhq.getGameForms = function() {
	this.initTypes();
	return this.enums.gameForms;
};

window.fhq.getGameStates = function() {
	this.initTypes();
	return this.enums.gameStates;
};

window.fhq.getEventTypes = function() {
	this.initTypes();
	return this.enums.eventTypes;
};

window.fhq.getEventTypesFilter = function() {
	this.initTypes();
	return this.enums.eventTypesFilter;
};

window.fhq.getUserRoles = function() {
	this.initTypes();
	return this.enums.userRoles;
};

window.fhq.getUserRolesFilter = function() {
	this.initTypes();
	return this.enums.userRolesFilter;
};

window.fhq.getUserStatuses = function() {
	this.initTypes();
	return this.enums.userStatuses;
};

window.fhq.getUserStatusesFilter = function() {
	this.initTypes();
	return this.enums.userStatusesFilter;
};

window.fhq.getOnPage = function() {
	this.initTypes();
	return this.enums.onpage;
};

window.fhq.getStyles = function() {
	this.initTypes();
	return this.enums.styles;
};

window.fhq.getFeedbackTypes = function() {
	this.initTypes();
	return this.enums.feedbackTypes;
};

window.fhq.getAnswerlistTable = function() {
	this.initTypes();
	return this.enums.answerlistTable;
};

window.fhq.getAnswerlistPassedFilter = function() {
	this.initTypes();
	return this.enums.answerlistPassedFilter;
};
