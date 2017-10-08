if(!window.fhq) window.fhq = {};
if(!window.fhq.api) window.fhq.api = {};
if(!window.fhq.api.users) window.fhq.api.users = {};
if(!window.fhq.api.events) window.fhq.api.events = {};
if(!window.fhq.api.feedback) window.fhq.api.feedback = {};
if(!window.fhq.api.quests) window.fhq.api.quests = {};
if(!window.fhq.api.games) window.fhq.api.games = {};

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
fhq.cache = {};
fhq.cache.gameid = 0;

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

fhq.isAuth = function(){
	return fhq.token && fhq.token != "";
}

fhq.isAdmin = function(){
	if(fhq.userinfo){
		return fhq.userinfo.role == "admin";
	}
	return false;
}

window.fhq.games = new (function(t) {
	this.p = t;
	this.get = function(gameid) {
		var params = {};
		params.id = gameid;
		var obj = this.p.sendPostRequest_Sync('api/games/get.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj.data : obj.error;
	};
	this.export = function(gameid) {
		var win = window.open(this.p.baseUrl + 'api/games/export.php?gameid=' + gameid, '_blank');
		win.focus();
	};
})(window.fhq);

window.fhq.quests = new (function(t) {
	this.p = t;
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


fhq.api.quests.pass = function(questid, answer){
	var params = {};
	params.questid = questid;
	params.answer = answer;
	params.token = fhq.token;

	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/v1/quests/pass/',
		data: params
	}).done(function(r){
		d.resolve(r);
	}).fail(function(r){
		d.reject(r);
	});
	return d;
}

fhq.api.quests.list = function(params){
	params = params || {};
	params.gameid = fhq.cache.gameid;
	params.token = fhq.token;
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/v1/quests/list/',
		contentType: "application/json",
		data: JSON.stringify(params)
	}).done(function(response){
		d.resolve(response);
	}).fail(function(r){
		d.reject(r);
	})
	return d;
};

fhq.api.quests.stats_subjects = function(params){
	params = params || {};
	params.token = fhq.token;
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/v1/quests/stats_subjects/',
		contentType: "application/json",
		data: JSON.stringify(params)
	}).done(function(r){
		d.resolve(r);
	}).fail(function(r){
		d.reject(r);
	})
	return d;
}

/* feedback api */

fhq.api.feedback.list = function(params){
	params = params || {};
	params.token = fhq.token;
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/v1/feedback/list/',
		contentType: "application/json",
		data: JSON.stringify(params)
	}).done(function(r){
		d.resolve(r);
	}).fail(function(r){
		d.reject(r);
	})
	return d;
};

window.fhq.api.feedback.insert = function(params){
	params = params || {};
	params.token = fhq.token;
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/v1/feedback/insert/',
		contentType: "application/json",
		data: JSON.stringify(params)
	}).done(function(r){
		d.resolve(r);
	}).fail(function(r){
		d.reject(r);
	})
	return d;
}

fhq.api.cleanuptoken = function(){
	fhq.token = "";
	fhq.removeTokenFromCookie();
}

/*
fhq.api.users.login = function (email, password) {
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
			fhq.api.cleanuptoken();
			localStorage.removeItem('userinfo');
			fhq.userinfo = {};
			d.reject(r);
		}
	}).fail(function(r){
		d.reject(r);
	})
	return d;
};*/

fhq.api.users.getLastEventId = function(){
	var d = $.Deferred();
	var params = {};
	$.ajax({
		type: "POST",
		url: 'api/v1/users/profile/',
		contentType: "application/json",
		data: JSON.stringify(params)
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

fhq.api.users.logout = function () {
	var params = {};
	params.token = fhq.token;
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/security/logout.php',
		data: params
	}).done(function(r){
		try{fhq.ws.socket.close();fhq.ws.initWebsocket()}catch(e){console.error(e)};
		d.resolve(r);
	}).fail(function(r){
		fhq.token = "";
		fhq.userinfo = null;
		fhq.removeTokenFromCookie();
		d.reject(r);
	})
	return d;
};

fhq.api.users.captcha = function(){
	var d = $.Deferred();
	var params = {};
	$.ajax({
		type: "GET",
		url: 'api/v1/users/captcha/',
		data: params
	}).done(function(r){
		d.resolve(r);
	}).fail(function(r){
		d.resolve(r);
	})
	return d;
};

fhq.api.users.registration = function(data){
	data = data || {};
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/v1/users/registration/',
		contentType: "application/json",
		data: JSON.stringify(data)
	}).done(function(r){
		d.resolve(r);
	}).fail(function(r){
		d.reject(r);
	})
	return d;
}

fhq.api.users.reset_password = function(data) {
	data = data || {};
	var d = $.Deferred();
	$.ajax({
		type: "POST",
		url: 'api/v1/users/reset_password/',
		contentType: "application/json",
		data: JSON.stringify(data)
	}).done(function(r){
		d.resolve(r);
	}).fail(function(r){
		d.reject(r);
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
})(window.fhq);

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

fhq.publicInfo = function(callback) {
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

fhq.getEventTypes = function() {
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

window.fhq.getAnswerlistTable = function() {
	this.initTypes();
	return this.enums.answerlistTable;
};

window.fhq.getAnswerlistPassedFilter = function() {
	this.initTypes();
	return this.enums.answerlistPassedFilter;
};
