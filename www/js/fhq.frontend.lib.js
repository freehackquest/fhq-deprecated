function FHQFrontEndLib() {
	// helpers function
	this.createUrlFromObj = function(obj) {
		var str = "";
		for (k in obj) {
			if (str.length > 0)
				str += "&";
			str += encodeURIComponent(k) + "=" + encodeURIComponent(obj[k]);
		}
		return str;
	};
	
	this.getCurrentApiPath = function() {
		var path = location.pathname.split("/");
		path.splice(path.indexOf('index.php'), 1);	
		var newURL = location.protocol + '//' + location.host + path.join("/") + "/";
		return newURL;
	};

	this.setTokenToCookie = function(token) {
		var date = new Date( new Date().getTime() + (7 * 24 * 60 * 60 * 1000) ); // cookie on week
		document.cookie = "fhqtoken=" + encodeURIComponent(token) + "; path=/; expires="+date.toUTCString();
	}
	
	this.removeTokenFromCookie = function() {
		document.cookie = "fhqtoken=; path=/;";
	}
	
	this.getTokenFromCookie = function() {
		var matches = document.cookie.match(new RegExp(
			"(?:^|; )" + "fhqtoken".replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : '';
	}

	this.baseUrl = "http://fhq.keva.su/";
	this.token = this.getTokenFromCookie();
	this.client = "FHQFrontEndLib.js";
	this.profile = {
		lastEventId: 0,
		bInitUserProfile: false
	};

	// post request to server Async
	this.sendPostRequest_Async = function(page, params, callbackf) {
		var tmpXMLhttp = null;
		params.token = this.token;
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
						alert(tmpXMLhttp.responseText);
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

	// post request to server Sync
	this.sendPostRequest_Sync = function(page, params) {
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

	this.security = new (function(t) {
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
		this.logout = function () {
			var params = {};
			params.token = this.p.token;
			var obj = this.p.sendPostRequest_Sync('api/security/logout.php', params);
			this.p.token = "";
			//this.p.removeTokenFromCookie();
			return true;
		};
	})(this);

	
	this.games = new (function(t) {
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
	})(this);
	
	this.quests = new (function(t) {
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
			var obj = this.p.sendPostRequest_Sync('api/quests/pass.php', params);
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
	})(this);
	
	this.feedback = new (function(t) {
		this.p = t;
		this.insert = function(params, callback) {
			var obj = null;
			if (callback)
				this.p.sendPostRequest_Async('api/feedback/insert.php', params, callback);
			else
				return this.p.sendPostRequest_Sync('api/feedback/insert.php', params);
		};
		this.get = function(gameid) {
			var params = {};
			params.id = gameid;
			var obj = this.p.sendPostRequest_Sync('api/games/get.php', params);
			var bRes = obj.result == "ok";
			return bRes ? obj.data : obj.error;
		};
		this.update = function(gameid) {
			var params = {};
			params.id = gameid;
			var obj = this.p.sendPostRequest_Sync('api/games/choose.php', params);
			// alert(JSON.stringify(params));
			var bRes = obj.result == "ok";
			return bRes ? obj : obj.error;
		};
		this.delete = function(gameid) {
			var params = {};
			params.id = gameid;
			var obj = this.p.sendPostRequest_Sync('api/games/choose.php', params);
			// alert(JSON.stringify(params));
			var bRes = obj.result == "ok";
			return bRes ? obj : obj.error;
		};
	})(this);
	
	this.events = new (function(t) {
		this.fhq = t;
		this.count = function(callback) {
			var params = {};
			params['id'] = this.fhq.users.getLastEventId();
			this.fhq.sendPostRequest_Async('api/events/count.php', params, callback);
		};
	})(this);

	this.users = new (function(t) {
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
	})(this);

	this.enums = null;
	
	this.initTypes = function() {
		if (this.enums != null)
			return;
		var obj = this.sendPostRequest_Sync('api/settings/types.php', {});
		this.enums = obj.data;
	}

	// enums
	this.getQuestTypes = function() {
		this.initTypes();
		return this.enums.questTypes;
	};
	
	this.getQuestTypesFilter = function() {
		this.initTypes();
		return this.enums.questTypesFilter;
	};

	this.getQuestStates = function() {
		this.initTypes();
		return this.enums.questStates;
	};

	this.getGameTypes = function() {
		this.initTypes();
		return this.enums.gameTypes;
	};

	this.getGameForms = function() {
		this.initTypes();
		return this.enums.gameForms;
	};

	this.getGameStates = function() {
		this.initTypes();
		return this.enums.gameStates;
	};
	
	this.getEventTypes = function() {
		this.initTypes();
		return this.enums.eventTypes;
	};

	this.getUserRoles = function() {
		this.initTypes();
		return this.enums.userRoles;
	};
	
	this.getUserRolesFilter = function() {
		this.initTypes();
		return this.enums.userRolesFilter;
	};
	
	this.getUserStatuses = function() {
		this.initTypes();
		return this.enums.userStatuses;
	};
	
	this.getUserStatusesFilter = function() {
		this.initTypes();
		return this.enums.userStatusesFilter;
	};
	
	this.getOnPage = function() {
		this.initTypes();
		return this.enums.onpage;
	};
	
	this.getStyles = function() {
		this.initTypes();
		return this.enums.styles;
	};
	
	this.getFeedbackTypes = function() {
		this.initTypes();
		return this.enums.feedbackTypes;
	};

	this.getAnswerlistTable = function() {
		this.initTypes();
		return this.enums.answerlistTable;
	};
	
	this.getAnswerlistPassedFilter = function() {
		this.initTypes();
		return this.enums.answerlistPassedFilter;
	};
};
