if(!window.fhq) window.fhq = {};

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
window.fhq.token = fhq.getTokenFromCookie();

window.fhq.profile = profile = {
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
	this.login = function (email, password) {
		var params = {};
		params.email = email;
		params.password = password;
		params.client = this.p.client;
		var obj = this.p.sendPostRequest_Sync('api/security/login.php', params);
		if (obj.result == "ok") {
			this.p.token = obj.data.token;
			this.p.setTokenToCookie(obj.data.token);
		} else {
			this.p.token = "";
			this.p.removeTokenFromCookie();
		}
		return obj;
	};
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
	
	this.logout = function () {
		var params = {};
		params.token = this.p.token;
		var obj = this.p.sendPostRequest_Sync('api/security/logout.php', params);
		this.p.token = "";
		//this.p.removeTokenFromCookie();
		return true;
	};
})(window.fhq);

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
	this.list = function() {
		var params = {};
		var obj = this.p.sendPostRequest_Sync('api/quests/list.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj : obj.error;
	};
	this.get = function(questid) {
		var params = {};
		params.taskid = questid;
		var obj = this.p.sendPostRequest_Sync('api/quests/get.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj.data : obj.error;
	};
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
	this.take = function(questid) {
		var params = {};
		params.questid = questid;
		var obj = this.p.sendPostRequest_Sync('api/quests/take.php', params);
		var bRes = obj.result == "ok";
		return bRes ? obj.data : obj.error;
	};
	this.pass = function(questid, answer) {
		var params = {};
		params.questid = questid;
		params.answer = answer;
		return $.ajax({
			type: "POST",
			url: 'api/quests/pass.php',
			data: params
		});
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

window.fhq.feedback = new (function(t) {
	this.p = t;
	this.insert = function(params, callback) {
		var obj = null;
		if (callback)
			this.p.sendPostRequest_Async('api/feedback/insert.php', params, callback);
		else
			return this.p.sendPostRequest_Sync('api/feedback/insert.php', params);
	};
})(window.fhq);

window.fhq.events = new (function(t) {
	this.fhq = t;
	this.count = function(callback) {
		var params = {};
		params['id'] = this.fhq.users.getLastEventId();
		this.fhq.sendPostRequest_Async('api/events/count.php', params, callback);
	};
})(window.fhq);

window.fhq.users = new (function(t) {
	this.fhq = t;
	this.initProfile = function() {
		if (this.fhq.profile.bInitUserProfile == true)
			return;
		var params = {};
		var obj = this.fhq.sendPostRequest_Sync('api/users/get.php', params);
		if (obj.result == 'ok') {
			if (obj.currentUser == true) {
				this.fhq.profile.lastEventId = obj.profile.lasteventid;
				this.fhq.profile.template = obj.profile.template;
				this.fhq.profile.university = obj.profile.university;
				this.fhq.profile.country = obj.profile.country;
				this.fhq.profile.city = obj.profile.city;
				this.fhq.profile.game = obj.profile.game;
				this.fhq.userid = obj.data.userid;
				this.fhq.nick = obj.data.nick;
				this.fhq.email = obj.data.email;
				this.fhq.role = obj.data.role;
				this.fhq.status = obj.data.status;
				this.fhq.profile.bInitUserProfile == true;
			}
		};
	};

	this.getLastEventId = function() {
		this.fhq.users.initProfile();
		return this.fhq.profile.lastEventId;
	};
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

window.fhq.statistics = new (function(t) {
	this.p = t;
	this.answerlist = function(params, callback) {
		if (callback)
			this.p.sendPostRequest_Async('api/statistics/answerlist.php', params, callback);
		else
			return this.p.sendPostRequest_Sync('api/statistics/answerlist.php', params);
	};
})(window.fhq);

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
