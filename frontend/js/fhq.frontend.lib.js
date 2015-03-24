/*
  other implementation
*/

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
	}
	this.baseUrl = "http://fhq.keva.su/";
	this.token = "";
	this.client = "FHQFrontEndLib.js";

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
					alert(tmpXMLhttp.responseText);
					var obj = JSON.parse(tmpXMLhttp.responseText);
					callbackf(obj);
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

	this.auth = new (function(t) {
		this.p = t;
		this.sign_in = function (email, password) {
			var params = {};
			params.email = email;
			params.password = password;
			params.client = this.p.client;
			var obj = this.p.sendPostRequest_Sync('api/auth/sign_in.php', params);
			// alert(JSON.stringify(obj));
			var bRes = obj.result == "ok";
			if (bRes)
				this.p.token = obj.token;
			else
				this.p.token = "";
			return bRes;
		};
		this.sign_out = function () {
			var params = {};
			var obj = this.p.sendPostRequest_Sync('api/auth/sign_out.php', params);
			this.p.token = "";
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
};
